<?php

declare(strict_types=1);

return function (PDO $pdo): void {
    $pdo->exec(
        'CREATE TABLE circuit_shares (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            project_id INT UNSIGNED NOT NULL,
            user_id INT UNSIGNED NOT NULL,
            slug VARCHAR(80) NOT NULL,
            title VARCHAR(180) NOT NULL,
            enabled TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            UNIQUE KEY uq_circuit_shares_project (project_id),
            UNIQUE KEY uq_circuit_shares_slug (slug),
            INDEX idx_circuit_shares_enabled (enabled),
            CONSTRAINT fk_circuit_shares_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
            CONSTRAINT fk_circuit_shares_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE circuit_share_sends (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            share_id INT UNSIGNED NOT NULL,
            email VARCHAR(190) NOT NULL,
            sent_at DATETIME NOT NULL,
            INDEX idx_share_sends_share (share_id, sent_at),
            CONSTRAINT fk_circuit_share_sends_share FOREIGN KEY (share_id) REFERENCES circuit_shares(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );
};
