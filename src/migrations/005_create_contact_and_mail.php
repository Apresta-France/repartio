<?php

declare(strict_types=1);

return function (PDO $pdo): void {
    $pdo->exec(
        'CREATE TABLE contact_messages (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            topic VARCHAR(64) NOT NULL,
            first_name VARCHAR(120) NOT NULL,
            email VARCHAR(190) NOT NULL,
            body TEXT NOT NULL,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE email_logs (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            recipient VARCHAR(190) NOT NULL,
            subject VARCHAR(255) NOT NULL,
            template VARCHAR(80) NOT NULL,
            created_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE settings (
            setting_key VARCHAR(80) PRIMARY KEY,
            setting_value TEXT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );
};
