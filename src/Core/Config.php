<?php

declare(strict_types=1);

namespace App\Core;

class Config
{
    /** @var array<string, mixed> */
    private static array $items = [];

    public static function load(string $envPath): void
    {
        if (!is_file($envPath)) {
            return;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES) ?: [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }
            if (!str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }
            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }

        self::$items = [
            'app.name' => $_ENV['APP_NAME'] ?? 'repartio',
            'app.env' => $_ENV['APP_ENV'] ?? 'local',
            'app.debug' => ($_ENV['APP_DEBUG'] ?? '0') === '1',
            'app.url' => rtrim($_ENV['APP_URL'] ?? '', '/'),
            'app.key' => $_ENV['APP_KEY'] ?? '',
            'db.driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
            'db.host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'db.port' => (int) ($_ENV['DB_PORT'] ?? 3306),
            'db.name' => $_ENV['DB_NAME'] ?? 'repartio',
            'db.user' => $_ENV['DB_USER'] ?? 'root',
            'db.pass' => $_ENV['DB_PASS'] ?? '',
            'mail.driver' => $_ENV['MAIL_DRIVER'] ?? 'file',
            'mail.host' => $_ENV['MAIL_HOST'] ?? '127.0.0.1',
            'mail.port' => (int) ($_ENV['MAIL_PORT'] ?? 587),
            'mail.user' => $_ENV['MAIL_USER'] ?? '',
            'mail.pass' => $_ENV['MAIL_PASS'] ?? '',
            'mail.encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
            'mail.from' => $_ENV['MAIL_FROM'] ?? 'bonjour@repartio.fr',
            'mail.from_name' => $_ENV['MAIL_FROM_NAME'] ?? 'repartio',
            'mail.admin' => $_ENV['MAIL_ADMIN'] ?? 'bonjour@repartio.fr',
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::$items[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        self::$items[$key] = $value;
    }
}
