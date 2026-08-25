<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Project
{
    public const HORIZON_MIN = 1;
    public const HORIZON_MAX = 600;

    public static function clampHorizon(mixed $horizon, int $fallback = 60, int $max = self::HORIZON_MAX): int
    {
        $value = (int) $horizon;
        $cap = max(self::HORIZON_MIN, min(self::HORIZON_MAX, $max));
        if ($value < self::HORIZON_MIN) {
            return min($fallback, $cap);
        }
        return min($cap, $value);
    }

    public static function clampHorizonForUser(mixed $horizon, ?array $user): int
    {
        return self::clampHorizon(
            $horizon,
            Plan::defaultHorizon($user),
            Plan::horizonMax($user)
        );
    }

    public static function planLimit(array $user): int
    {
        return Plan::circuitLimit($user);
    }

    public static function atPlanLimit(array $user): bool
    {
        return self::activeCount((int) $user['id']) >= self::planLimit($user);
    }

    public static function planChangePath(string $reason = 'circuits'): string
    {
        $reason = in_array($reason, ['circuits', 'invitations'], true) ? $reason : 'circuits';
        return '/app/forfait?raison=' . $reason;
    }

    public static function planLimitMessage(array $user, string $wanted = ''): string
    {
        $limit = self::planLimit($user);
        $label = Plan::label($user);
        $wantedBit = $wanted !== '' ? ' pour « ' . $wanted . ' »' : '';
        $next = Plan::nextLabel($user);
        $upgrade = $next !== null ? ', ou passez en ' . $next : '';

        return 'Votre forfait ' . $label . ' autorise ' . $limit . ' circuit' . ($limit > 1 ? 's' : '')
            . $wantedBit . '. Archivez-en un' . $upgrade . ' pour libérer un emplacement.';
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch('SELECT * FROM projects WHERE id = ? LIMIT 1', [$id]);
    }

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
        $name = mb_substr($name, 0, 180);
        $owner = User::find($userId);
        $payload['horizon'] = self::clampHorizonForUser($payload['horizon'] ?? null, $owner);
        $totals = self::summarize($payload);
        Database::query(
            'INSERT INTO projects (user_id, name, slug, status, horizon, payload, monthly_in, monthly_out, monthly_saved, unassigned, projection, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
            [
                $userId,
                $name,
                slugify($name) . '-' . bin2hex(random_bytes(3)),
                $status,
                $payload['horizon'],
                self::encodePayload($payload),
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
        if (!self::findForUser($id, $userId)) {
            return;
        }
        self::updateById($id, $name, $payload);
    }

    public static function updateById(int $id, string $name, array $payload): void
    {
        $name = mb_substr($name, 0, 180);
        $project = self::findById($id);
        $owner = $project ? User::find((int) $project['user_id']) : null;
        $payload['horizon'] = self::clampHorizonForUser($payload['horizon'] ?? null, $owner);
        $totals = self::summarize($payload);
        Database::query(
            'UPDATE projects SET name = ?, horizon = ?, payload = ?, monthly_in = ?, monthly_out = ?, monthly_saved = ?, unassigned = ?, projection = ?, updated_at = NOW()
             WHERE id = ?',
            [
                $name,
                $payload['horizon'],
                self::encodePayload($payload),
                $totals['monthly_in'],
                $totals['monthly_out'],
                $totals['monthly_saved'],
                $totals['unassigned'],
                $totals['projection'],
                $id,
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

    public static function setStatusById(int $id, string $status): void
    {
        Database::query(
            'UPDATE projects SET status = ?, updated_at = NOW() WHERE id = ?',
            [$status, $id]
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

    public static function emptyPayload(?int $horizon = null): array
    {
        return [
            'horizon' => $horizon ?? 24,
            'nodes' => [],
            'edges' => [],
        ];
    }

    public static function blockCount(array $payload): int
    {
        $n = 0;
        foreach ($payload['nodes'] ?? [] as $node) {
            $kind = (string) ($node['kind'] ?? '');
            if ($kind !== '' && $kind !== 'note' && $kind !== 'groupe') {
                $n++;
            }
        }
        return $n;
    }

    public static function thumb(array $payload): array
    {
        $colors = [
            'revenu' => 'oklch(0.62 0.12 192)',
            'compte' => 'oklch(0.32 0.09 265)',
            'repartiteur' => 'oklch(0.68 0.18 38)',
            'livret' => 'oklch(0.48 0.11 240)',
            'depense' => 'oklch(0.55 0.16 25)',
        ];
        $nodes = [];
        foreach ($payload['nodes'] ?? [] as $node) {
            $kind = (string) ($node['kind'] ?? '');
            if ($kind === '' || $kind === 'note' || $kind === 'groupe' || ($node['id'] ?? '') === '') {
                continue;
            }
            $nodes[] = $node;
        }
        if (count($nodes) > 18) {
            $nodes = array_slice($nodes, 0, 18);
        }
        if ($nodes === []) {
            return ['wires' => [], 'dots' => []];
        }

        $xs = array_map(static fn (array $n): float => (float) ($n['x'] ?? 0), $nodes);
        $ys = array_map(static fn (array $n): float => (float) ($n['y'] ?? 0), $nodes);
        $minX = min($xs);
        $maxX = max($xs);
        $minY = min($ys);
        $maxY = max($ys);
        $spanX = max(80.0, $maxX - $minX);
        $spanY = max(60.0, $maxY - $minY);
        $padX = 18.0;
        $padY = 22.0;
        $areaW = 300 - $padX * 2;
        $areaH = 136 - $padY * 2;

        $map = [];
        $dots = [];
        foreach ($nodes as $node) {
            $kind = (string) ($node['kind'] ?? 'compte');
            $x = $padX + ((float) ($node['x'] ?? 0) - $minX) / $spanX * $areaW;
            $y = $padY + ((float) ($node['y'] ?? 0) - $minY) / $spanY * $areaH;
            $width = match ($kind) {
                'revenu' => 30,
                'livret', 'depense' => 46,
                default => 38,
            };
            $rx = max(6, min(300 - $width - 6, $x));
            $ry = max(10, min(110, $y));
            $map[(string) $node['id']] = [$rx + $width * 0.5, $ry + 8];
            $dots[] = [$rx, $ry, $width, $colors[$kind] ?? $colors['compte']];
        }

        $wires = [];
        foreach ($payload['edges'] ?? [] as $edge) {
            $from = $map[(string) ($edge['from'] ?? '')] ?? null;
            $to = $map[(string) ($edge['to'] ?? '')] ?? null;
            if (!$from || !$to) {
                continue;
            }
            $mx = ($from[0] + $to[0]) / 2;
            $wires[] = sprintf(
                'M%.1f %.1f C%.1f %.1f %.1f %.1f %.1f %.1f',
                $from[0],
                $from[1],
                $mx,
                $from[1],
                $mx,
                $to[1],
                $to[0],
                $to[1]
            );
        }

        return ['wires' => $wires, 'dots' => $dots];
    }

    public static function summarize(array $payload): array
    {
        $nodes = $payload['nodes'] ?? [];
        $edges = $payload['edges'] ?? [];
        $horizon = self::clampHorizon($payload['horizon'] ?? 60);

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
                $leftover += $kept[$id] ?? 0;
            } elseif ($kind === 'depense') {
                $out += ($kept[$id] ?? 0) + $outAmt;
            } elseif (in_array($kind, ['groupe', 'note'], true)) {
                continue;
            } elseif ($kind === 'livret') {
                $add = $kept[$id] ?? 0;
                $balance = max(0.0, (float) ($node['start'] ?? 0));
                $cap = (float) ($node['cap'] ?? 0);
                $hasCap = $cap > 0;
                $rate = max(0.0, (float) ($node['rate'] ?? 0));
                $firstDeposit = $add;
                for ($month = 1; $month <= $horizon; $month++) {
                    $balance += $balance * ($rate / 100) / 12;
                    $deposit = $hasCap ? min($add, max(0.0, $cap - $balance)) : $add;
                    if ($month === 1) {
                        $firstDeposit = $deposit;
                    }
                    $balance += $deposit;
                }
                $saved += $firstDeposit;
                $leftover += max(0.0, $add - $firstDeposit);
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

    private static function nodeAmount(array $node): float
    {
        if (($node['kind'] ?? '') === 'depense' && !empty($node['items']) && is_array($node['items'])) {
            $sum = 0.0;
            foreach ($node['items'] as $item) {
                $sum += max(0.0, (float) ($item['amount'] ?? 0));
            }
            return $sum;
        }
        return max(0.0, (float) ($node['amount'] ?? 0));
    }

    private static function summarizeLegacy(array $payload): array
    {
        $in = 0.0;
        $out = 0.0;
        $saved = 0.0;
        foreach ($payload['nodes'] ?? [] as $node) {
            $amount = self::nodeAmount($node);
            $kind = $node['kind'] ?? '';
            if ($kind === 'revenu') {
                $in += $amount;
            } elseif ($kind === 'depense') {
                $out += $amount;
            } elseif (in_array($kind, ['livret', 'repartiteur'], true)) {
                $saved += $amount;
            }
        }
        $horizon = self::clampHorizon($payload['horizon'] ?? 60);
        return [
            'monthly_in' => $in,
            'monthly_out' => $out,
            'monthly_saved' => $saved,
            'unassigned' => max(0, $in - $out - $saved),
            'projection' => $saved * $horizon,
        ];
    }

    private static function encodePayload(array $payload): string
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        if (!is_string($json)) {
            throw new \RuntimeException('Impossible d’enregistrer le circuit.');
        }
        return $json;
    }
}
