<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class CircuitLive
{
    private const COLORS = [
        'oklch(0.55 0.16 25)',
        'oklch(0.46 0.13 265)',
        'oklch(0.48 0.12 152)',
        'oklch(0.50 0.14 300)',
        'oklch(0.52 0.13 70)',
        'oklch(0.48 0.11 200)',
        'oklch(0.50 0.13 15)',
        'oklch(0.44 0.10 240)',
    ];

    public static function colorFor(int $userId): string
    {
        return self::COLORS[$userId % count(self::COLORS)];
    }

    public static function ensure(int $projectId, int $userId, string $name, array $payload): int
    {
        $row = self::find($projectId);
        if ($row) {
            return (int) $row['revision'];
        }
        $json = self::encode($payload);
        try {
            Database::query(
                'INSERT INTO circuit_live (project_id, user_id, revision, name, payload, updated_at)
                 VALUES (?, ?, 1, ?, ?, NOW())',
                [$projectId, $userId, mb_substr($name, 0, 180), $json]
            );
            return 1;
        } catch (\PDOException $e) {
            $row = self::find($projectId);
            if ($row) {
                return (int) $row['revision'];
            }
            throw $e;
        }
    }

    public static function isAhead(array $project, ?array $live = null): bool
    {
        $live ??= self::find((int) $project['id']);
        if (!$live) {
            return false;
        }
        $livePayload = json_decode((string) $live['payload'], true);
        $saved = json_decode((string) $project['payload'], true);
        if (!is_array($livePayload)) {
            return false;
        }
        if (!is_array($saved)) {
            return true;
        }
        return !CircuitVersion::same((string) $live['name'], $livePayload, (string) $project['name'], $saved);
    }

    /**
     * @return array{name:string,payload:array,revision:int,ahead:bool}
     */
    public static function workingCopy(array $project, array $owner): array
    {
        $payload = json_decode((string) $project['payload'], true) ?: Project::emptyPayload();
        $payload['horizon'] = Project::clampHorizonForUser($payload['horizon'] ?? null, $owner);
        $live = self::find((int) $project['id']);
        if ($live) {
            $livePayload = json_decode((string) $live['payload'], true);
            if (is_array($livePayload)) {
                $livePayload['horizon'] = Project::clampHorizonForUser($livePayload['horizon'] ?? null, $owner);
                $liveName = trim((string) $live['name']);
                return [
                    'name' => $liveName !== '' ? $liveName : (string) $project['name'],
                    'payload' => $livePayload,
                    'revision' => (int) $live['revision'],
                    'ahead' => self::isAhead($project, $live),
                ];
            }
            return [
                'name' => (string) $project['name'],
                'payload' => $payload,
                'revision' => (int) $live['revision'],
                'ahead' => false,
            ];
        }
        return [
            'name' => (string) $project['name'],
            'payload' => $payload,
            'revision' => self::ensure((int) $project['id'], (int) $project['user_id'], (string) $project['name'], $payload),
            'ahead' => false,
        ];
    }

    public static function find(int $projectId): ?array
    {
        return Database::fetch('SELECT * FROM circuit_live WHERE project_id = ? LIMIT 1', [$projectId]);
    }

    public static function publish(int $projectId, int $userId, string $name, array $payload): int
    {
        $name = mb_substr($name, 0, 180);
        $json = self::encode($payload);
        $row = self::find($projectId);
        if ($row && (string) $row['name'] === $name && (string) $row['payload'] === $json) {
            return (int) $row['revision'];
        }
        if ($row) {
            Database::query(
                'UPDATE circuit_live SET user_id = ?, revision = revision + 1, name = ?, payload = ?, updated_at = NOW()
                 WHERE project_id = ?',
                [$userId, $name, $json, $projectId]
            );
            $updated = self::find($projectId);
            return $updated ? (int) $updated['revision'] : (int) $row['revision'] + 1;
        }
        try {
            Database::query(
                'INSERT INTO circuit_live (project_id, user_id, revision, name, payload, updated_at)
                 VALUES (?, ?, 1, ?, ?, NOW())',
                [$projectId, $userId, $name, $json]
            );
            return 1;
        } catch (\PDOException $e) {
            $existing = self::find($projectId);
            if ($existing) {
                return self::publish($projectId, $userId, $name, $payload);
            }
            throw $e;
        }
    }

    public static function heartbeat(int $projectId, int $userId, string $clientId, float $x, float $y, bool $visible = true): void
    {
        $clientId = mb_substr($clientId, 0, 40);
        $x = max(-50000.0, min(50000.0, $x));
        $y = max(-50000.0, min(50000.0, $y));
        $flag = $visible ? 1 : 0;
        Database::query(
            'INSERT INTO circuit_presence (project_id, user_id, client_id, cursor_x, cursor_y, cursor_visible, last_seen)
             VALUES (?, ?, ?, ?, ?, ?, NOW())
             ON DUPLICATE KEY UPDATE
                cursor_x = IF(VALUES(cursor_visible) = 1, VALUES(cursor_x), cursor_x),
                cursor_y = IF(VALUES(cursor_visible) = 1, VALUES(cursor_y), cursor_y),
                cursor_visible = VALUES(cursor_visible),
                last_seen = NOW()',
            [$projectId, $userId, $clientId, $x, $y, $flag]
        );
        self::sweep($projectId);
    }

    /**
     * @return list<array{user_id:int,client_id:string,first_name:string,color:string,x:float,y:float}>
     */
    public static function peers(int $projectId, int $userId, string $clientId): array
    {
        $rows = Database::fetchAll(
            'SELECT p.user_id, p.client_id, p.cursor_x, p.cursor_y, u.first_name
             FROM circuit_presence p
             INNER JOIN users u ON u.id = p.user_id
             WHERE p.project_id = ?
               AND p.cursor_visible = 1
               AND p.last_seen > DATE_SUB(NOW(), INTERVAL 8 SECOND)
               AND NOT (p.user_id = ? AND p.client_id = ?)',
            [$projectId, $userId, $clientId]
        );
        $peers = [];
        foreach ($rows as $row) {
            $uid = (int) $row['user_id'];
            $peers[] = [
                'user_id' => $uid,
                'client_id' => (string) $row['client_id'],
                'first_name' => (string) $row['first_name'],
                'color' => self::colorFor($uid),
                'x' => (float) $row['cursor_x'],
                'y' => (float) $row['cursor_y'],
            ];
        }
        return $peers;
    }

    public static function authorName(int $userId): string
    {
        if ($userId <= 0) {
            return 'Quelqu’un';
        }
        $user = User::find($userId);
        return $user ? (string) $user['first_name'] : 'Quelqu’un';
    }

    private static function sweep(int $projectId): void
    {
        Database::query(
            'DELETE FROM circuit_presence
             WHERE project_id = ? AND last_seen < DATE_SUB(NOW(), INTERVAL 20 SECOND)',
            [$projectId]
        );
    }

    private static function encode(array $payload): string
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        if (!is_string($json)) {
            throw new \RuntimeException('Impossible de synchroniser le circuit.');
        }
        return $json;
    }
}
