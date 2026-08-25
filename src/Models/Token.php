<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Token
{
    public static function create(int $userId, string $purpose, int $minutes): string
    {
        Database::query(
            'DELETE FROM auth_tokens WHERE user_id = ? AND purpose = ?',
            [$userId, $purpose]
        );
        $raw = bin2hex(random_bytes(32));
        $minutes = max(1, $minutes);
        Database::query(
            'INSERT INTO auth_tokens (user_id, token_hash, purpose, expires_at, created_at) VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL ' . $minutes . ' MINUTE), NOW())',
            [$userId, hash('sha256', $raw), $purpose]
        );
        return $raw;
    }

    public static function peek(string $raw, string $purpose): ?array
    {
        return Database::fetch(
            'SELECT * FROM auth_tokens WHERE token_hash = ? AND purpose = ? AND expires_at > NOW() LIMIT 1',
            [hash('sha256', $raw), $purpose]
        );
    }

    public static function consume(string $raw, string $purpose): ?array
    {
        $hash = hash('sha256', $raw);
        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                'SELECT * FROM auth_tokens WHERE token_hash = ? AND purpose = ? AND expires_at > NOW() LIMIT 1 FOR UPDATE'
            );
            $stmt->execute([$hash, $purpose]);
            $row = $stmt->fetch();
            if ($row === false) {
                $pdo->rollBack();
                return null;
            }
            $delete = $pdo->prepare('DELETE FROM auth_tokens WHERE id = ?');
            $delete->execute([(int) $row['id']]);
            $pdo->commit();
            return $row;
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
