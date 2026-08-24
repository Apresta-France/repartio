<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    public static function user(): ?array
    {
        $id = Session::get('user_id');
        if (!$id) {
            return self::fromRemember();
        }
        static $cached = false;
        static $user = null;
        if ($cached === true) {
            return $user;
        }
        $cached = true;
        $user = User::find((int) $id);
        return $user;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        $user = self::user();
        return $user ? (int) $user['id'] : null;
    }

    public static function login(array $user, bool $remember = false): void
    {
        Session::regenerate();
        Session::set('user_id', (int) $user['id']);
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $hash = hash('sha256', $token);
            $expires = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30);
            Database::query(
                'INSERT INTO auth_tokens (user_id, token_hash, purpose, expires_at, created_at) VALUES (?, ?, ?, ?, NOW())',
                [(int) $user['id'], $hash, 'remember', $expires]
            );
            setcookie('repartio_remember', $token, [
                'expires' => time() + 60 * 60 * 24 * 30,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax',
                'secure' => is_https(),
            ]);
        }
        User::touchLogin((int) $user['id']);
    }

    public static function logout(): void
    {
        if (!empty($_COOKIE['repartio_remember'])) {
            Database::query(
                'DELETE FROM auth_tokens WHERE token_hash = ? AND purpose = ?',
                [hash('sha256', (string) $_COOKIE['repartio_remember']), 'remember']
            );
            setcookie('repartio_remember', '', ['expires' => time() - 3600, 'path' => '/']);
        }
        Session::forget('user_id');
        Session::regenerate();
    }

    public static function requireUser(): array
    {
        $user = self::user();
        if (!$user) {
            Session::flashSet('error', 'Connectez-vous pour continuer.');
            redirect('/connexion');
        }
        return $user;
    }

    private static function fromRemember(): ?array
    {
        $raw = $_COOKIE['repartio_remember'] ?? null;
        if (!$raw) {
            return null;
        }
        $row = Database::fetch(
            'SELECT user_id FROM auth_tokens WHERE token_hash = ? AND purpose = ? AND expires_at > NOW() LIMIT 1',
            [hash('sha256', $raw), 'remember']
        );
        if (!$row) {
            return null;
        }
        $user = User::find((int) $row['user_id']);
        if ($user) {
            Session::set('user_id', (int) $user['id']);
        }
        return $user;
    }
}
