<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Token
{
    public static function create(int $userId, string $purpose, int $minutes): string
    {
        $raw = bin2hex(random_bytes(32));
        Database::query(
            'INSERT INTO auth_tokens (user_id, token_hash, purpose, expires_at, created_at) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL ? MINUTE), NOW())',
            [$userId, hash('sha256', $raw), $purpose, $minutes]
        );
        return $raw;
    }

    public static function consume(string $raw, string $purpose): ?array
    {
        $hash = hash('sha256', $raw);
        $row = Database::fetch(
            'SELECT * FROM auth_tokens WHERE token_hash = ? AND purpose = ? AND expires_at > NOW() LIMIT 1',
            [$hash, $purpose]
        );
        if (!$row) {
            return null;
        }
        Database::query('DELETE FROM auth_tokens WHERE id = ?', [(int) $row['id']]);
        return $row;
    }
}
