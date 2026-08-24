<?php

declare(strict_types=1);

if (version_compare(PHP_VERSION, '8.1.0', '<')) {
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    echo 'repartio nécessite PHP 8.1 ou plus. Version actuelle : ' . htmlspecialchars(PHP_VERSION, ENT_QUOTES, 'UTF-8');
    exit;
}

define('BASE_PATH', __DIR__);

try {
    require BASE_PATH . '/src/bootstrap.php';
    App\Core\App::run();
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    $installed = is_file(BASE_PATH . '/storage/installed.lock');
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Erreur</title></head><body>';
    echo '<p>repartio n’a pas pu démarrer.</p>';
    if (!$installed) {
        echo '<pre>' . htmlspecialchars($e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine(), ENT_QUOTES, 'UTF-8') . '</pre>';
    }
    echo '</body></html>';
}
