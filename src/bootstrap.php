<?php

declare(strict_types=1);

use App\Core\Config;

require BASE_PATH . '/src/helpers.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $path = BASE_PATH . '/src/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($path)) {
        require $path;
    }
});

foreach (['storage', 'storage/logs', 'storage/mail', 'storage/cache'] as $dir) {
    $path = BASE_PATH . '/' . $dir;
    if (!is_dir($path)) {
        @mkdir($path, 0775, true);
    }
}

Config::load(BASE_PATH . '/.env');

date_default_timezone_set('Europe/Paris');

if (PHP_SAPI !== 'cli' && session_status() !== PHP_SESSION_ACTIVE) {
    $sessionDir = BASE_PATH . '/storage/cache';
    if (is_dir($sessionDir) && is_writable($sessionDir)) {
        session_save_path($sessionDir);
    }
    session_name('repartio_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    @session_start();
}

if ((string) env('APP_DEBUG', '0') === '1') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL & ~E_NOTICE);
    ini_set('display_errors', '0');
}
