<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

class Migrator
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function run(): array
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS migrations (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(190) NOT NULL UNIQUE,
                ran_at DATETIME NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );

        $done = $this->pdo->query('SELECT name FROM migrations')->fetchAll(PDO::FETCH_COLUMN) ?: [];
        $dir = BASE_PATH . '/src/migrations';
        $files = glob($dir . '/*.php') ?: [];
        sort($files);

        $applied = [];
        foreach ($files as $file) {
            $name = basename($file);
            if (in_array($name, $done, true)) {
                continue;
            }
            $migration = require $file;
            if (!is_callable($migration)) {
                throw new \RuntimeException('Migration invalide : ' . $name);
            }
            $migration($this->pdo);
            $stmt = $this->pdo->prepare('INSERT INTO migrations (name, ran_at) VALUES (?, NOW())');
            $stmt->execute([$name]);
            $applied[] = $name;
        }

        return $applied;
    }
}
