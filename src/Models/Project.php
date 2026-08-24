<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Project
{
    public const PLAN_LIMITS = [
        'libre' => 3,
        'complet' => 999,
        'foyer' => 999,
    ];

    public static function findForUser(int $id, int $userId): ?array
    {
        return Database::fetch('SELECT * FROM projects WHERE id = ? AND user_id = ? LIMIT 1', [$id, $userId]);
    }

    public static function allForUser(int $userId, ?string $status = null): array
    {
        if ($status) {
            return Database::fetchAll(
                'SELECT * FROM projects WHERE user_id = ? AND status = ? ORDER BY updated_at DESC',
                [$userId, $status]
            );
        }
        return Database::fetchAll(
            'SELECT * FROM projects WHERE user_id = ? ORDER BY FIELD(status, "actif", "scenario", "archive"), updated_at DESC',
            [$userId]
        );
    }

    public static function recents(int $userId, int $limit = 3): array
    {
        return Database::fetchAll(
            'SELECT id, name, status FROM projects WHERE user_id = ? AND status != "archive" ORDER BY updated_at DESC LIMIT ' . (int) $limit,
            [$userId]
        );
    }

    public static function activeCount(int $userId): int
    {
        $row = Database::fetch(
            'SELECT COUNT(*) AS n FROM projects WHERE user_id = ? AND status != "archive"',
            [$userId]
        );
        return (int) ($row['n'] ?? 0);
    }

    public static function create(int $userId, string $name, array $payload, string $status = 'actif'): array
    {
        $totals = self::summarize($payload);
        Database::query(
            'INSERT INTO projects (user_id, name, slug, status, horizon, payload, monthly_in, monthly_out, monthly_saved, unassigned, projection, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
            [
                $userId,
                $name,
                slugify($name) . '-' . bin2hex(random_bytes(3)),
                $status,
                (int) ($payload['horizon'] ?? 60),
                json_encode($payload, JSON_UNESCAPED_UNICODE),
                $totals['monthly_in'],
                $totals['monthly_out'],
                $totals['monthly_saved'],
                $totals['unassigned'],
                $totals['projection'],
            ]
        );
        return self::findForUser((int) Database::lastId(), $userId) ?? [];
    }

    public static function updateCircuit(int $id, int $userId, string $name, array $payload): void
    {
        $totals = self::summarize($payload);
        Database::query(
            'UPDATE projects SET name = ?, horizon = ?, payload = ?, monthly_in = ?, monthly_out = ?, monthly_saved = ?, unassigned = ?, projection = ?, updated_at = NOW()
             WHERE id = ? AND user_id = ?',
            [
                $name,
                (int) ($payload['horizon'] ?? 60),
                json_encode($payload, JSON_UNESCAPED_UNICODE),
                $totals['monthly_in'],
                $totals['monthly_out'],
                $totals['monthly_saved'],
                $totals['unassigned'],
                $totals['projection'],
                $id,
                $userId,
            ]
        );
    }

    public static function setStatus(int $id, int $userId, string $status): void
    {
        Database::query(
            'UPDATE projects SET status = ?, updated_at = NOW() WHERE id = ? AND user_id = ?',
            [$status, $id, $userId]
        );
    }

    public static function delete(int $id, int $userId): void
    {
        Database::query('DELETE FROM projects WHERE id = ? AND user_id = ?', [$id, $userId]);
    }

    public static function log(int $userId, string $message, ?int $projectId = null): void
    {
        Database::query(
            'INSERT INTO activity_logs (user_id, project_id, message, created_at) VALUES (?, ?, ?, NOW())',
            [$userId, $projectId, $message]
        );
    }

    public static function activity(int $userId, int $limit = 8): array
    {
        return Database::fetchAll(
            'SELECT a.*, p.name AS project_name FROM activity_logs a
             LEFT JOIN projects p ON p.id = a.project_id
             WHERE a.user_id = ? ORDER BY a.created_at DESC LIMIT ' . (int) $limit,
            [$userId]
        );
    }

    public static function emptyPayload(): array
    {
        return [
            'horizon' => 60,
            'nodes' => [],
            'edges' => [],
        ];
    }

    public static function summarize(array $payload): array
    {
        $nodes = $payload['nodes'] ?? [];
        $edges = $payload['edges'] ?? [];
        $horizon = (int) ($payload['horizon'] ?? 60);

        $legacyAmounts = false;
        foreach ($nodes as $node) {
            if (($node['kind'] ?? '') !== 'revenu' && (float) ($node['amount'] ?? 0) > 0) {
                $legacyAmounts = true;
                break;
            }
        }
        if ($edges === [] && $legacyAmounts) {
            return self::summarizeLegacy($payload);
        }

        $byId = [];
        $outs = [];
        $indeg = [];
        foreach ($nodes as $node) {
            $id = (string) ($node['id'] ?? '');
            if ($id === '') {
                continue;
            }
            $byId[$id] = $node;
            $outs[$id] = [];
            $indeg[$id] = 0;
        }
        foreach ($edges as $edge) {
            $from = (string) ($edge['from'] ?? '');
            $to = (string) ($edge['to'] ?? '');
            if (!isset($byId[$from], $byId[$to])) {
                continue;
            }
            $value = (float) ($edge['value'] ?? $edge['amount'] ?? 0);
            $mode = $edge['mode'] ?? ($value > 0 ? 'fixe' : 'reste');
            $outs[$from][] = ['to' => $to, 'mode' => $mode, 'value' => $value, 'amt' => 0.0];
            $indeg[$to]++;
        }

        $queue = [];
        foreach ($indeg as $id => $degree) {
            if ($degree === 0) {
                $queue[] = $id;
            }
        }
        $order = [];
        $seen = [];
        while ($queue) {
            $id = array_shift($queue);
            if (isset($seen[$id])) {
                continue;
            }
            $seen[$id] = true;
            $order[] = $id;
            foreach ($outs[$id] as $edge) {
                $indeg[$edge['to']]--;
                if ($indeg[$edge['to']] === 0) {
                    $queue[] = $edge['to'];
                }
            }
        }
        foreach ($byId as $id => $_) {
            if (!isset($seen[$id])) {
                $order[] = $id;
            }
        }

        $inflow = array_fill_keys(array_keys($byId), 0.0);
        $kept = [];
        foreach ($order as $id) {
            $node = $byId[$id] ?? null;
            if (!$node) {
                continue;
            }
            $available = ($node['kind'] ?? '') === 'revenu'
                ? max(0.0, (float) ($node['amount'] ?? 0))
                : $inflow[$id];
            $list = $outs[$id];
            $remaining = $available;
            foreach ($list as $i => $edge) {
                if ($edge['mode'] !== 'fixe') {
                    continue;
                }
                $ask = max(0.0, $edge['value']);
                $amt = min($ask, max(0.0, $remaining));
                $list[$i]['amt'] = $amt;
                $remaining -= $amt;
            }
            foreach ($list as $i => $edge) {
                if ($edge['mode'] !== 'pct') {
                    continue;
                }
                $ask = $available * max(0.0, $edge['value']) / 100;
                $amt = min($ask, max(0.0, $remaining));
                $list[$i]['amt'] = $amt;
                $remaining -= $amt;
            }
            $rest = [];
            foreach ($list as $i => $edge) {
                if ($edge['mode'] === 'reste') {
                    $rest[] = $i;
                }
            }
            if ($rest) {
                $share = max(0.0, $remaining) / count($rest);
                foreach ($rest as $i) {
                    $list[$i]['amt'] = $share;
                }
                $remaining -= $share * count($rest);
            }
            $kept[$id] = max(0.0, $remaining);
            foreach ($list as $edge) {
                $inflow[$edge['to']] += $edge['amt'];
            }
            $outs[$id] = $list;
        }

        $in = 0.0;
        $out = 0.0;
        $saved = 0.0;
        $leftover = 0.0;
        $projection = 0.0;
        foreach ($byId as $id => $node) {
            $kind = $node['kind'] ?? '';
            $outAmt = 0.0;
            foreach ($outs[$id] as $edge) {
                $outAmt += $edge['amt'];
            }
            if ($kind === 'revenu') {
                $in += max(0.0, (float) ($node['amount'] ?? 0));
            } elseif ($kind === 'depense') {
                $out += ($kept[$id] ?? 0) + $outAmt;
            } elseif ($kind === 'livret') {
                $saved += $kept[$id] ?? 0;
                $balance = max(0.0, (float) ($node['start'] ?? 0));
                $cap = (float) ($node['cap'] ?? 0);
                $cap = $cap > 0 ? $cap : INF;
                $add = $kept[$id] ?? 0;
                $rate = max(0.0, (float) ($node['rate'] ?? 0));
                for ($month = 1; $month <= $horizon; $month++) {
                    $balance += $balance * ($rate / 100) / 12;
                    $balance = min($cap, $balance + $add);
                }
                $projection += $balance;
            } else {
                $leftover += $kept[$id] ?? 0;
            }
        }

        return [
            'monthly_in' => $in,
            'monthly_out' => $out,
            'monthly_saved' => $saved,
            'unassigned' => $leftover,
            'projection' => $projection > 0 ? $projection : $saved * $horizon,
        ];
    }

    private static function summarizeLegacy(array $payload): array
    {
        $in = 0.0;
        $out = 0.0;
        $saved = 0.0;
        foreach ($payload['nodes'] ?? [] as $node) {
            $amount = (float) ($node['amount'] ?? 0);
            $kind = $node['kind'] ?? '';
            if ($kind === 'revenu') {
                $in += $amount;
            } elseif ($kind === 'depense') {
                $out += $amount;
            } elseif (in_array($kind, ['livret', 'repartiteur'], true)) {
                $saved += $amount;
            }
        }
        $horizon = (int) ($payload['horizon'] ?? 60);
        return [
            'monthly_in' => $in,
            'monthly_out' => $out,
            'monthly_saved' => $saved,
            'unassigned' => max(0, $in - $out - $saved),
            'projection' => $saved * $horizon,
        ];
    }
}
