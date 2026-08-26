<?php

declare(strict_types=1);

return function (PDO $pdo): void {
    $pdo->exec(
        'CREATE TABLE projects (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            uid CHAR(32) NOT NULL,
            user_id INT UNSIGNED NOT NULL,
            name VARCHAR(180) NOT NULL,
            slug VARCHAR(190) NOT NULL,
            status VARCHAR(32) NOT NULL DEFAULT "actif",
            horizon INT UNSIGNED NOT NULL DEFAULT 60,
            payload LONGTEXT NOT NULL,
            monthly_in DECIMAL(12,2) NOT NULL DEFAULT 0,
            monthly_out DECIMAL(12,2) NOT NULL DEFAULT 0,
            monthly_saved DECIMAL(12,2) NOT NULL DEFAULT 0,
            unassigned DECIMAL(12,2) NOT NULL DEFAULT 0,
            projection DECIMAL(12,2) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            UNIQUE KEY uq_projects_uid (uid),
            INDEX idx_user_status (user_id, status),
            CONSTRAINT fk_projects_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );
};
