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
        $in = 0.0;
        $out = 0.0;
        $saved = 0.0;
        foreach ($nodes as $node) {
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
        $unassigned = max(0, $in - $out - $saved);
        $horizon = (int) ($payload['horizon'] ?? 60);
        return [
            'monthly_in' => $in,
            'monthly_out' => $out,
            'monthly_saved' => $saved,
            'unassigned' => $unassigned,
            'projection' => $saved * $horizon,
        ];
    }
}
