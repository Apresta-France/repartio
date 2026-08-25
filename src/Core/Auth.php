<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    private static bool $resolved = false;
    private static ?array $cachedUser = null;

    public static function user(): ?array
    {
        if (self::$resolved) {
            return self::$cachedUser;
        }
        $id = Session::get('user_id');
        if (!$id) {
            self::$cachedUser = self::fromRemember();
            self::$resolved = true;
            return self::$cachedUser;
        }
        $user = User::find((int) $id);
        if (!$user || !self::passwordMatches($user)) {
            Session::forget('user_id');
            Session::forget('pwd_sig');
            self::$cachedUser = $user ? null : self::fromRemember();
            self::$resolved = true;
            return self::$cachedUser;
        }
        self::$resolved = true;
        self::$cachedUser = $user;
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
        self::bindSession($user);
        if ($remember) {
            self::issueRemember((int) $user['id']);
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
        }
        self::clearRememberCookie();
        Session::forget('user_id');
        Session::forget('pwd_sig');
        Session::regenerate();
        self::$resolved = true;
        self::$cachedUser = null;
    }

    public static function revokeAllTokens(int $userId): void
    {
        Database::query('DELETE FROM auth_tokens WHERE user_id = ?', [$userId]);
        self::clearRememberCookie();
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

    public static function isAdmin(?array $user = null): bool
    {
        $user ??= self::user();
        return $user !== null && ($user['role'] ?? '') === 'admin';
    }

    public static function requireAdmin(): array
    {
        $user = self::requireUser();
        if (!self::isAdmin($user)) {
            Session::flashSet('error', 'Cette page est réservée à l’administration.');
            redirect('/app');
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
            self::clearRememberCookie();
            return null;
        }
        $user = User::find((int) $row['user_id']);
        if ($user) {
            Session::regenerate();
            self::bindSession($user);
            self::issueRemember((int) $user['id'], $raw);
            return $user;
        }
        Database::query(
            'DELETE FROM auth_tokens WHERE token_hash = ? AND purpose = ?',
            [hash('sha256', $raw), 'remember']
        );
        self::clearRememberCookie();
        return null;
    }

    /**
     * @param array<string, mixed> $user
     */
    private static function bindSession(array $user): void
    {
        Session::set('user_id', (int) $user['id']);
        Session::set('pwd_sig', hash('sha256', (string) ($user['password_hash'] ?? '')));
        self::$resolved = true;
        self::$cachedUser = $user;
    }

    /**
     * @param array<string, mixed> $user
     */
    private static function passwordMatches(array $user): bool
    {
        $expected = hash('sha256', (string) ($user['password_hash'] ?? ''));
        $sig = Session::get('pwd_sig');
        if (!is_string($sig) || $sig === '') {
            Session::set('pwd_sig', $expected);
            return true;
        }
        return hash_equals($sig, $expected);
    }

    private static function issueRemember(int $userId, ?string $previous = null): void
    {
        if ($previous) {
            Database::query(
                'DELETE FROM auth_tokens WHERE token_hash = ? AND purpose = ?',
                [hash('sha256', $previous), 'remember']
            );
        }
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30);
        Database::query(
            'INSERT INTO auth_tokens (user_id, token_hash, purpose, expires_at, created_at) VALUES (?, ?, ?, ?, NOW())',
            [$userId, hash('sha256', $token), 'remember', $expires]
        );
        self::setRememberCookie($token);
    }

    private static function setRememberCookie(string $token): void
    {
        setcookie('repartio_remember', $token, [
            'expires' => time() + 60 * 60 * 24 * 30,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => is_https(),
        ]);
    }

    private static function clearRememberCookie(): void
    {
        setcookie('repartio_remember', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => is_https(),
        ]);
    }
}
