<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class CircuitVersion
{
    public const KEEP = 50;

    public static function snapshot(int $projectId, int $userId, string $name, array $payload): void
    {
        $name = mb_substr($name, 0, 180);
        $json = self::encode($payload);
        $last = Database::fetch(
            'SELECT name, payload FROM circuit_versions WHERE project_id = ? ORDER BY created_at DESC, id DESC LIMIT 1',
            [$projectId]
        );
        if ($last && (string) $last['name'] === $name && (string) $last['payload'] === $json) {
            return;
        }
        Database::query(
            'INSERT INTO circuit_versions (project_id, user_id, name, payload, created_at)
             VALUES (?, ?, ?, ?, NOW())',
            [$projectId, $userId, $name, $json]
        );
        self::prune($projectId);
    }

    public static function same(string $nameA, array $payloadA, string $nameB, array $payloadB): bool
    {
        return mb_substr($nameA, 0, 180) === mb_substr($nameB, 0, 180)
            && self::encode($payloadA) === self::encode($payloadB);
    }

    public static function snapshotIfDue(int $projectId, int $userId, string $name, array $payload, int $minSeconds = 120): void
    {
        $recent = Database::fetch(
            'SELECT id FROM circuit_versions
             WHERE project_id = ? AND user_id = ?
               AND created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)
             ORDER BY created_at DESC, id DESC
             LIMIT 1',
            [$projectId, $userId, $minSeconds]
        );
        if ($recent) {
            return;
        }
        self::snapshot($projectId, $userId, $name, $payload);
    }

    public static function listForProject(int $projectId): array
    {
        return Database::fetchAll(
            'SELECT v.id, v.user_id, v.name, v.created_at, u.first_name
             FROM circuit_versions v
             LEFT JOIN users u ON u.id = v.user_id
             WHERE v.project_id = ?
             ORDER BY v.created_at DESC, v.id DESC
             LIMIT ' . self::KEEP,
            [$projectId]
        );
    }

    public static function findForProject(int $id, int $projectId): ?array
    {
        return Database::fetch(
            'SELECT * FROM circuit_versions WHERE id = ? AND project_id = ? LIMIT 1',
            [$id, $projectId]
        );
    }

    private static function prune(int $projectId): void
    {
        Database::query(
            'DELETE FROM circuit_versions
             WHERE project_id = ?
               AND id NOT IN (
                 SELECT id FROM (
                   SELECT id FROM circuit_versions
                   WHERE project_id = ?
                   ORDER BY created_at DESC, id DESC
                   LIMIT ' . self::KEEP . '
                 ) AS keep_rows
               )',
            [$projectId, $projectId]
        );
    }

    private static function encode(array $payload): string
    {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        if (!is_string($json)) {
            throw new \RuntimeException('Impossible d’enregistrer cette version.');
        }
        return $json;
    }
}
