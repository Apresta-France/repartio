<?php

declare(strict_types=1);

return function (PDO $pdo): void {
    $pdo->exec(
        'CREATE TABLE circuit_versions (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            project_id INT UNSIGNED NOT NULL,
            user_id INT UNSIGNED NOT NULL,
            name VARCHAR(180) NOT NULL,
            payload LONGTEXT NOT NULL,
            created_at DATETIME NOT NULL,
            INDEX idx_versions_project (project_id, created_at, id),
            CONSTRAINT fk_versions_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
            CONSTRAINT fk_versions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE circuit_live (
            project_id INT UNSIGNED PRIMARY KEY,
            user_id INT UNSIGNED NOT NULL,
            revision INT UNSIGNED NOT NULL DEFAULT 1,
            name VARCHAR(180) NOT NULL,
            payload LONGTEXT NOT NULL,
            updated_at DATETIME NOT NULL,
            CONSTRAINT fk_live_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
            CONSTRAINT fk_live_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE circuit_presence (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            project_id INT UNSIGNED NOT NULL,
            user_id INT UNSIGNED NOT NULL,
            client_id VARCHAR(40) NOT NULL,
            cursor_x DOUBLE NOT NULL DEFAULT 0,
            cursor_y DOUBLE NOT NULL DEFAULT 0,
            last_seen DATETIME NOT NULL,
            UNIQUE KEY uq_presence_client (project_id, user_id, client_id),
            INDEX idx_presence_seen (project_id, last_seen),
            CONSTRAINT fk_presence_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
            CONSTRAINT fk_presence_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );
};
