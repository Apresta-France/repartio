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
            'INSERT INTO users (first_name, email, password_hash, plan, role, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, NOW(), NOW())',
            [
                $data['first_name'],
                mb_strtolower($data['email']),
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['plan'] ?? 'libre',
                ($data['role'] ?? '') === 'admin' ? 'admin' : 'user',
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
        if ($changed) {
            Database::query('DELETE FROM auth_tokens WHERE user_id = ? AND purpose = ?', [$id, 'verify']);
        }
    }

    public static function updatePlan(int $id, string $plan): void
    {
        Database::query(
            'UPDATE users SET plan = ?, updated_at = NOW() WHERE id = ?',
            [$plan, $id]
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

    public static function countAdmins(): int
    {
        $row = Database::fetch('SELECT COUNT(*) AS n FROM users WHERE role = ?', ['admin']);
        return (int) ($row['n'] ?? 0);
    }

    public static function countSince(string $datetime): int
    {
        $row = Database::fetch('SELECT COUNT(*) AS n FROM users WHERE created_at >= ?', [$datetime]);
        return (int) ($row['n'] ?? 0);
    }

    public static function countVerified(): int
    {
        $row = Database::fetch('SELECT COUNT(*) AS n FROM users WHERE email_verified_at IS NOT NULL');
        return (int) ($row['n'] ?? 0);
    }

    /**
     * @return array{rows:list<array<string,mixed>>,total:int,page:int,pages:int}
     */
    public static function search(string $q = '', string $plan = '', string $role = '', int $page = 1, int $perPage = 24): array
    {
        $where = ['1=1'];
        $params = [];
        if ($q !== '') {
            $where[] = '(first_name LIKE ? OR email LIKE ?)';
            $like = '%' . $q . '%';
            $params[] = $like;
            $params[] = $like;
        }
        if ($plan !== '' && Plan::exists($plan)) {
            $where[] = 'plan = ?';
            $params[] = $plan;
        }
        if (in_array($role, ['admin', 'user'], true)) {
            $where[] = 'role = ?';
            $params[] = $role;
        }
        $sql = implode(' AND ', $where);
        $total = (int) (Database::fetch('SELECT COUNT(*) AS n FROM users WHERE ' . $sql, $params)['n'] ?? 0);
        $pages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $pages));
        $offset = ($page - 1) * $perPage;
        $rows = Database::fetchAll(
            'SELECT u.*,
                    (SELECT COUNT(*) FROM projects p WHERE p.user_id = u.id) AS circuits_count
             FROM users u
             WHERE ' . $sql . '
             ORDER BY u.created_at DESC
             LIMIT ' . (int) $perPage . ' OFFSET ' . (int) $offset,
            $params
        );

        return ['rows' => $rows, 'total' => $total, 'page' => $page, 'pages' => $pages];
    }

    /**
     * @param array{first_name:string,email:string,plan:string,role:string} $data
     */
    public static function adminUpdate(int $id, array $data): void
    {
        $firstName = mb_substr($data['first_name'], 0, 120);
        $email = mb_strtolower($data['email']);
        $current = self::find($id);
        $changed = $current && mb_strtolower((string) $current['email']) !== $email;
        $role = $data['role'] === 'admin' ? 'admin' : 'user';
        $plan = Plan::exists($data['plan']) ? $data['plan'] : 'libre';
        Database::query(
            'UPDATE users SET first_name = ?, email = ?, plan = ?, role = ?'
            . ($changed ? ', email_verified_at = NULL' : '')
            . ', updated_at = NOW() WHERE id = ?',
            [$firstName, $email, $plan, $role, $id]
        );
    }

    public static function unverify(int $id): void
    {
        Database::query('UPDATE users SET email_verified_at = NULL, updated_at = NOW() WHERE id = ?', [$id]);
    }
}
