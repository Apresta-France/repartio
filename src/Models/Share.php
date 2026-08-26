<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Share
{
    public static function findForProject(int $projectId, int $userId): ?array
    {
        return Database::fetch(
            'SELECT * FROM circuit_shares WHERE project_id = ? AND user_id = ? LIMIT 1',
            [$projectId, $userId]
        );
    }

    public static function findPublic(string $slug): ?array
    {
        return Database::fetch(
            'SELECT s.*, p.payload, p.horizon, p.name AS project_name, p.status AS project_status,
                    u.first_name AS owner_name
             FROM circuit_shares s
             INNER JOIN projects p ON p.id = s.project_id
             INNER JOIN users u ON u.id = s.user_id
             WHERE s.slug = ? AND s.enabled = 1 AND p.status != "archive"
             LIMIT 1',
            [$slug]
        );
    }

    public static function create(int $projectId, int $userId, string $title): array
    {
        Database::query(
            'INSERT INTO circuit_shares (project_id, user_id, slug, title, enabled, created_at, updated_at)
             VALUES (?, ?, ?, ?, 1, NOW(), NOW())',
            [$projectId, $userId, self::uniqueToken(), $title]
        );
        return self::findForProject($projectId, $userId) ?? [];
    }

    public static function update(int $id, int $userId, string $title, bool $enabled = true): void
    {
        Database::query(
            'UPDATE circuit_shares SET title = ?, enabled = ?, updated_at = NOW()
             WHERE id = ? AND user_id = ?',
            [$title, $enabled ? 1 : 0, $id, $userId]
        );
    }

    public static function setEnabled(int $id, int $userId, bool $enabled): void
    {
        Database::query(
            'UPDATE circuit_shares SET enabled = ?, updated_at = NOW() WHERE id = ? AND user_id = ?',
            [$enabled ? 1 : 0, $id, $userId]
        );
    }

    public static function uniqueToken(): string
    {
        do {
            $token = bin2hex(random_bytes(8));
        } while (self::slugTaken($token));

        return $token;
    }

    public static function slugTaken(string $slug, ?int $exceptId = null): bool
    {
        if ($exceptId) {
            $row = Database::fetch(
                'SELECT id FROM circuit_shares WHERE slug = ? AND id != ? LIMIT 1',
                [$slug, $exceptId]
            );
        } else {
            $row = Database::fetch('SELECT id FROM circuit_shares WHERE slug = ? LIMIT 1', [$slug]);
        }
        return $row !== null;
    }

    public static function parseEmails(string $raw): array
    {
        $parts = preg_split('/[\s,;]+/', $raw) ?: [];
        $emails = [];
        foreach ($parts as $part) {
            $email = mb_strtolower(trim($part));
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            $emails[$email] = $email;
        }
        return array_values($emails);
    }

    public static function logSend(int $shareId, string $email): void
    {
        Database::query(
            'INSERT INTO circuit_share_sends (share_id, email, sent_at) VALUES (?, ?, NOW())',
            [$shareId, mb_strtolower($email)]
        );
    }

    public static function sendsForShare(int $shareId): array
    {
        return Database::fetchAll(
            'SELECT email, MAX(sent_at) AS sent_at, COUNT(*) AS n
             FROM circuit_share_sends
             WHERE share_id = ?
             GROUP BY email
             ORDER BY sent_at DESC',
            [$shareId]
        );
    }

    public static function publicUrl(array $share): string
    {
        return app_url('/p/' . $share['slug']);
    }
}
