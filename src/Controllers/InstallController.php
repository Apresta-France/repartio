<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Config;
use App\Core\Database;
use App\Core\Migrator;
use App\Core\Session;
use App\Core\View;
use App\Models\User;
use PDO;
use PDOException;
use Throwable;

class InstallController
{
    public function show(): void
    {
        if (is_installed()) {
            redirect('/');
        }

        View::render('install/index', [
            'title' => 'Installation',
            'defaults' => [
                'app_url' => rtrim((string) ($_ENV['APP_URL'] ?? self::detectedAppUrl()), '/'),
                'db_host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
                'db_port' => $_ENV['DB_PORT'] ?? '3306',
                'db_name' => $_ENV['DB_NAME'] ?? 'repartio',
                'db_user' => $_ENV['DB_USER'] ?? 'root',
                'db_pass' => '',
                'mail_driver' => $_ENV['MAIL_DRIVER'] ?? 'file',
                'mail_host' => $_ENV['MAIL_HOST'] ?? '127.0.0.1',
                'mail_port' => $_ENV['MAIL_PORT'] ?? '587',
                'mail_from' => $_ENV['MAIL_FROM'] ?? 'bonjour@repartio.fr',
            ],
            'checks' => $this->checks(),
        ], 'layouts/install');
    }

    public function store(): void
    {
        if (is_installed()) {
            redirect('/');
        }

        foreach ($this->checks() as $check) {
            if (!$check[1]) {
                Session::flashSet('error', 'Corrigez les prérequis manquants avant d’installer.');
                redirect('/install');
            }
        }

        try {
            if (User::count() > 0) {
                Session::flashSet('error', 'Des comptes existent déjà. Restaurez storage/installed.lock plutôt que de réinstaller.');
                redirect('/install');
            }
        } catch (Throwable) {
        }

        $appUrl = rtrim(trim((string) ($_POST['app_url'] ?? '')), '/');
        $dbHost = trim((string) ($_POST['db_host'] ?? '127.0.0.1'));
        $dbPort = (int) ($_POST['db_port'] ?? 3306);
        $dbName = trim((string) ($_POST['db_name'] ?? 'repartio'));
        $dbUser = trim((string) ($_POST['db_user'] ?? 'root'));
        $dbPass = (string) ($_POST['db_pass'] ?? '');
        $firstName = trim((string) ($_POST['first_name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $mailDriver = trim((string) ($_POST['mail_driver'] ?? 'file'));

        if ($firstName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !password_is_strong($password)) {
            Session::flashSet('error', 'Complétez le compte administrateur (mot de passe 12 caractères, majuscule, minuscule et chiffre ou symbole).');
            redirect('/install');
        }

        try {
            $dsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $dbHost, $dbPort);
            $pdo = new PDO($dsn, $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $safeName = preg_replace('/[^a-zA-Z0-9_]/', '', $dbName) ?: 'repartio';
            $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . $safeName . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        } catch (PDOException $e) {
            Session::flashSet('error', 'Connexion MySQL impossible : ' . $e->getMessage());
            redirect('/install');
        }

        $key = 'base64:' . bin2hex(random_bytes(16));
        $this->writeEnv([
            'APP_NAME' => 'repartio',
            'APP_ENV' => 'production',
            'APP_DEBUG' => '0',
            'APP_URL' => $appUrl !== '' ? $appUrl : 'https://repartio.fr',
            'APP_KEY' => $key,
            'DB_DRIVER' => 'mysql',
            'DB_HOST' => $dbHost,
            'DB_PORT' => (string) $dbPort,
            'DB_NAME' => $safeName,
            'DB_USER' => $dbUser,
            'DB_PASS' => $dbPass,
            'MAIL_DRIVER' => $mailDriver,
            'MAIL_HOST' => trim((string) ($_POST['mail_host'] ?? '127.0.0.1')),
            'MAIL_PORT' => trim((string) ($_POST['mail_port'] ?? '587')),
            'MAIL_USER' => trim((string) ($_POST['mail_user'] ?? '')),
            'MAIL_PASS' => (string) ($_POST['mail_pass'] ?? ''),
            'MAIL_ENCRYPTION' => trim((string) ($_POST['mail_encryption'] ?? 'starttls')),
            'MAIL_FROM' => trim((string) ($_POST['mail_from'] ?? 'bonjour@repartio.fr')),
            'MAIL_FROM_NAME' => 'repartio',
            'MAIL_ADMIN' => $email,
            'REINVENT_API_URL' => 'https://secure.reinvent.fr',
            'REINVENT_API_KEY' => '',
            'REINVENT_PLATFORM' => 'repartio',
            'REINVENT_WEBHOOK_SECRET' => '',
        ]);

        Config::load(BASE_PATH . '/.env');
        Database::reset();

        try {
            $pdo = Database::connect();
            $applied = (new Migrator($pdo))->run();
            if (User::count() === 0) {
                $user = User::create([
                    'first_name' => $firstName,
                    'email' => $email,
                    'password' => $password,
                    'plan' => 'complet',
                    'role' => 'admin',
                ]);
                User::markVerified((int) $user['id']);
            }
            if (User::count() === 0) {
                throw new \RuntimeException('Le compte administrateur n’a pas pu être créé.');
            }
        } catch (Throwable $e) {
            Session::flashSet('error', 'Installation interrompue : ' . $e->getMessage());
            redirect('/install');
        }

        $lock = file_put_contents(
            BASE_PATH . '/storage/installed.lock',
            json_encode(['installed_at' => date('c'), 'migrations' => $applied ?? []], JSON_PRETTY_PRINT)
        );
        if ($lock === false) {
            Session::flashSet('error', 'Le compte est créé, mais storage/installed.lock n’a pas pu être écrit. Recréez-le avant de recharger.');
            redirect('/connexion');
        }

        Session::flashSet('success', 'repartio est installé. Connectez-vous avec le compte créé.');
        redirect('/connexion');
    }

    private static function detectedAppUrl(): string
    {
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        $host = $_SERVER['HTTP_HOST'] ?? 'repartio.fr';
        return ($https ? 'https://' : 'http://') . $host;
    }

    private function checks(): array
    {
        return [
            ['PHP 8.1+', version_compare(PHP_VERSION, '8.1.0', '>=')],
            ['PDO MySQL', extension_loaded('pdo_mysql')],
            ['OpenSSL', extension_loaded('openssl')],
            ['mbstring', extension_loaded('mbstring')],
            ['Dossier storage/ inscriptible', is_writable(BASE_PATH . '/storage')],
        ];
    }

    private function writeEnv(array $values): void
    {
        $lines = [];
        foreach ($values as $key => $value) {
            $value = (string) $value;
            if ($value !== '' && preg_match('/[\s#"\']/', $value)) {
                $value = '"' . str_replace('"', '\\"', $value) . '"';
            }
            $lines[] = $key . '=' . $value;
        }
        file_put_contents(BASE_PATH . '/.env', implode(PHP_EOL, $lines) . PHP_EOL);
    }
}
