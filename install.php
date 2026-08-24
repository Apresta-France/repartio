<?php

declare(strict_types=1);

/**
 * Installation en ligne de commande.
 * Usage : php install.php
 *         php install.php --migrate
 */

define('BASE_PATH', __DIR__);
require BASE_PATH . '/src/bootstrap.php';

use App\Core\Database;
use App\Core\Migrator;
use App\Models\User;

$migrateOnly = in_array('--migrate', $argv ?? [], true);

try {
    $host = (string) env('DB_HOST', '127.0.0.1');
    $port = (int) env('DB_PORT', 3306);
    $name = (string) env('DB_NAME', 'repartio');
    $user = (string) env('DB_USER', 'root');
    $pass = (string) env('DB_PASS', '');
    $pdo = new PDO(sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $host, $port), $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $safe = preg_replace('/[^a-zA-Z0-9_]/', '', $name) ?: 'repartio';
    $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . $safe . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    $applied = (new Migrator(Database::connect()))->run();
    echo 'Migrations : ' . (count($applied) ? implode(', ', $applied) : 'à jour') . PHP_EOL;

    if (!$migrateOnly && User::count() === 0) {
        echo 'Aucun utilisateur. Créez le premier compte via /install ou /creer-un-compte.' . PHP_EOL;
    }

    if (!is_installed()) {
        file_put_contents(BASE_PATH . '/storage/installed.lock', json_encode([
            'installed_at' => date('c'),
            'via' => 'cli',
            'migrations' => $applied,
        ], JSON_PRETTY_PRINT));
        echo 'Fichier installed.lock créé.' . PHP_EOL;
    }

    echo 'OK' . PHP_EOL;
} catch (PDOException $e) {
    fwrite(STDERR, 'Erreur MySQL : ' . $e->getMessage() . PHP_EOL);
    exit(1);
}
