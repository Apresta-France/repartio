<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class EnvFile
{
    public const SECRETS = ['APP_KEY', 'DB_PASS', 'MAIL_PASS'];

    public const ORDER = [
        'APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL', 'APP_KEY',
        'DB_DRIVER', 'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASS',
        'MAIL_DRIVER', 'MAIL_HOST', 'MAIL_PORT', 'MAIL_USER', 'MAIL_PASS',
        'MAIL_ENCRYPTION', 'MAIL_FROM', 'MAIL_FROM_NAME', 'MAIL_ADMIN',
    ];

    /** @return array<string, array<string, array{label:string,type:string,options?:array<string,string>,hint?:string}>> */
    public static function groups(): array
    {
        return [
            'Application' => [
                'APP_NAME' => ['label' => 'Nom de l’application', 'type' => 'text'],
                'APP_ENV' => [
                    'label' => 'Environnement',
                    'type' => 'select',
                    'options' => ['local' => 'local (développement)', 'production' => 'production'],
                ],
                'APP_DEBUG' => [
                    'label' => 'Mode debug',
                    'type' => 'checkbox',
                    'hint' => 'Affiche les erreurs PHP. À laisser éteint en production.',
                ],
                'APP_URL' => ['label' => 'URL publique', 'type' => 'url'],
            ],
            'Base de données' => [
                'DB_HOST' => ['label' => 'Hôte', 'type' => 'text'],
                'DB_PORT' => ['label' => 'Port', 'type' => 'text'],
                'DB_NAME' => ['label' => 'Nom de la base', 'type' => 'text'],
                'DB_USER' => ['label' => 'Utilisateur', 'type' => 'text'],
                'DB_PASS' => ['label' => 'Mot de passe', 'type' => 'password', 'hint' => 'Laissez vide pour conserver la valeur actuelle.'],
            ],
            'Courrier' => [
                'MAIL_DRIVER' => [
                    'label' => 'Pilote',
                    'type' => 'select',
                    'options' => ['file' => 'Fichier (storage/mail)', 'smtp' => 'SMTP', 'mail' => 'mail() PHP'],
                ],
                'MAIL_HOST' => ['label' => 'Hôte SMTP', 'type' => 'text'],
                'MAIL_PORT' => ['label' => 'Port SMTP', 'type' => 'text'],
                'MAIL_USER' => ['label' => 'Utilisateur SMTP', 'type' => 'text'],
                'MAIL_PASS' => ['label' => 'Mot de passe SMTP', 'type' => 'password', 'hint' => 'Laissez vide pour conserver la valeur actuelle.'],
                'MAIL_ENCRYPTION' => [
                    'label' => 'Chiffrement',
                    'type' => 'select',
                    'options' => [
                        'starttls' => 'STARTTLS',
                        'tls' => 'TLS',
                        'ssl' => 'SSL / SMTPS',
                        'none' => 'Aucun',
                    ],
                ],
                'MAIL_FROM' => ['label' => 'Expéditeur', 'type' => 'email'],
                'MAIL_FROM_NAME' => ['label' => 'Nom de l’expéditeur', 'type' => 'text'],
                'MAIL_ADMIN' => ['label' => 'E-mail d’administration', 'type' => 'email'],
            ],
        ];
    }

    public static function path(): string
    {
        return BASE_PATH . '/.env';
    }

    public static function writable(): bool
    {
        $path = self::path();
        if (is_file($path)) {
            return is_writable($path);
        }
        return is_writable(dirname($path));
    }

    /** @return array<string, string> */
    public static function read(): array
    {
        $path = self::path();
        if (!is_file($path)) {
            return [];
        }

        $values = [];
        foreach (file($path, FILE_IGNORE_NEW_LINES) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"'))
                || (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
                $value = str_replace(['\\"', "\\'", '\\\\'], ['"', "'", '\\'], $value);
            }
            $values[$key] = $value;
        }

        return $values;
    }

    /**
     * @param array<string, string> $updates
     * @param list<string> $keepIfEmpty
     */
    public static function write(array $updates, array $keepIfEmpty = []): void
    {
        $current = self::read();
        foreach ($keepIfEmpty as $key) {
            if (!array_key_exists($key, $updates) || $updates[$key] === '') {
                $updates[$key] = $current[$key] ?? '';
            }
        }

        $merged = $current;
        foreach ($updates as $key => $value) {
            if (!preg_match('/^[A-Z][A-Z0-9_]*$/', $key)) {
                continue;
            }
            $merged[$key] = (string) $value;
        }

        $lines = [];
        $written = [];
        foreach (self::ORDER as $key) {
            if (!array_key_exists($key, $merged)) {
                continue;
            }
            $lines[] = $key . '=' . self::quote((string) $merged[$key]);
            $written[$key] = true;
        }
        foreach ($merged as $key => $value) {
            if (isset($written[$key])) {
                continue;
            }
            $lines[] = $key . '=' . self::quote((string) $value);
        }

        $path = self::path();
        $tmp = $path . '.tmp';
        if (file_put_contents($tmp, implode(PHP_EOL, $lines) . PHP_EOL) === false) {
            throw new \RuntimeException('Impossible d’écrire le fichier .env.');
        }
        if (!@rename($tmp, $path)) {
            @unlink($path);
            if (!@rename($tmp, $path)) {
                @unlink($tmp);
                throw new \RuntimeException('Impossible de remplacer le fichier .env.');
            }
        }
    }

    /**
     * @param array<string, string> $values
     */
    public static function testDatabase(array $values): void
    {
        $host = $values['DB_HOST'] ?? '127.0.0.1';
        $port = (int) ($values['DB_PORT'] ?? 3306);
        $name = $values['DB_NAME'] ?? 'repartio';
        $user = $values['DB_USER'] ?? 'root';
        $pass = $values['DB_PASS'] ?? '';
        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $name);
        try {
            new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        } catch (PDOException $e) {
            throw new \RuntimeException('Connexion MySQL impossible : ' . $e->getMessage());
        }
    }

    public static function reload(): void
    {
        Config::load(self::path());
        Database::reset();
    }

    private static function quote(string $value): string
    {
        if ($value !== '' && preg_match('/[\s#"\']/', $value)) {
            return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
        }
        return $value;
    }
}
