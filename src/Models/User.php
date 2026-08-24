<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class User
{
    public static function find(int $id): ?array
    {
        return Database::fetch('SELECT * FROM users WHERE id = ? LIMIT 1', [$id]);
    }

    public static function findByEmail(string $email): ?array
    {
        return Database::fetch('SELECT * FROM users WHERE email = ? LIMIT 1', [mb_strtolower($email)]);
    }

    public static function create(array $data): array
    {
        Database::query(
            'INSERT INTO users (first_name, email, password_hash, plan, created_at, updated_at)
             VALUES (?, ?, ?, ?, NOW(), NOW())',
            [
                $data['first_name'],
                mb_strtolower($data['email']),
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['plan'] ?? 'libre',
            ]
        );
        return self::find((int) Database::lastId()) ?? [];
    }

    public static function updateProfile(int $id, string $firstName, string $email): void
    {
        $firstName = mb_substr($firstName, 0, 120);
        $email = mb_strtolower($email);
        $current = self::find($id);
        $changed = $current && mb_strtolower((string) $current['email']) !== $email;
        Database::query(
            'UPDATE users SET first_name = ?, email = ?' . ($changed ? ', email_verified_at = NULL' : '') . ', updated_at = NOW() WHERE id = ?',
            [$firstName, $email, $id]
        );
    }

    public static function updatePassword(int $id, string $password): void
    {
        Database::query(
            'UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?',
            [password_hash($password, PASSWORD_DEFAULT), $id]
        );
    }

    public static function markVerified(int $id): void
    {
        Database::query('UPDATE users SET email_verified_at = NOW(), updated_at = NOW() WHERE id = ?', [$id]);
    }

    public static function touchLogin(int $id): void
    {
        Database::query('UPDATE users SET last_login_at = NOW() WHERE id = ?', [$id]);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM account_members WHERE member_id = ?', [$id]);
        Database::query('DELETE FROM users WHERE id = ?', [$id]);
    }

    public static function count(): int
    {
        $row = Database::fetch('SELECT COUNT(*) AS n FROM users');
        return (int) ($row['n'] ?? 0);
    }
}
