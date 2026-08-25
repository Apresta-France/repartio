<?php

declare(strict_types=1);

namespace App\Core;

class Config
{
    /** @var array<string, mixed> */
    private static array $items = [];

    public static function load(string $envPath): void
    {
        if (is_file($envPath)) {
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
                    $value = str_replace(['\\"', "\\'", '\\\\'], ['"', "'", '\\'], $value);
                }
                $_ENV[$key] = $value;
                putenv($key . '=' . $value);
            }
        }

        self::$items = [
            'app.name' => env('APP_NAME', 'repartio'),
            'app.env' => env('APP_ENV', 'local'),
            'app.debug' => (string) env('APP_DEBUG', '0') === '1',
            'app.url' => rtrim((string) env('APP_URL', ''), '/'),
            'app.key' => env('APP_KEY', ''),
            'db.driver' => env('DB_DRIVER', 'mysql'),
            'db.host' => env('DB_HOST', '127.0.0.1'),
            'db.port' => (int) env('DB_PORT', 3306),
            'db.name' => env('DB_NAME', 'repartio'),
            'db.user' => env('DB_USER', 'root'),
            'db.pass' => env('DB_PASS', ''),
            'mail.driver' => env('MAIL_DRIVER', 'file'),
            'mail.host' => env('MAIL_HOST', '127.0.0.1'),
            'mail.port' => (int) env('MAIL_PORT', 587),
            'mail.user' => env('MAIL_USER', ''),
            'mail.pass' => env('MAIL_PASS', ''),
            'mail.encryption' => env('MAIL_ENCRYPTION', 'starttls'),
            'mail.from' => env('MAIL_FROM', 'bonjour@repartio.fr'),
            'mail.from_name' => env('MAIL_FROM_NAME', 'repartio'),
            'mail.admin' => env('MAIL_ADMIN', 'bonjour@repartio.fr'),
            'reinvent.api_url' => rtrim((string) env('REINVENT_API_URL', 'https://secure.reinvent.fr'), '/'),
            'reinvent.api_key' => env('REINVENT_API_KEY', ''),
            'reinvent.platform' => env('REINVENT_PLATFORM', 'repartio'),
            'reinvent.webhook_secret' => env('REINVENT_WEBHOOK_SECRET', ''),
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
