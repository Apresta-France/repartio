<?php

declare(strict_types=1);

return function (PDO $pdo): void {
    $pdo->exec(
        'CREATE TABLE account_members (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            owner_id INT UNSIGNED NOT NULL,
            member_id INT UNSIGNED NULL,
            email VARCHAR(190) NOT NULL,
            token_hash VARCHAR(64) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT "pending",
            expires_at DATETIME NULL,
            created_at DATETIME NOT NULL,
            accepted_at DATETIME NULL,
            UNIQUE KEY uq_account_members_owner_email (owner_id, email),
            UNIQUE KEY uq_account_members_token (token_hash),
            INDEX idx_account_members_member (member_id, status),
            CONSTRAINT fk_account_members_owner FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE,
            CONSTRAINT fk_account_members_user FOREIGN KEY (member_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE account_member_circuits (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            member_row_id INT UNSIGNED NOT NULL,
            project_id INT UNSIGNED NOT NULL,
            permission VARCHAR(20) NOT NULL DEFAULT "lecture",
            UNIQUE KEY uq_member_circuit (member_row_id, project_id),
            INDEX idx_member_circuits_project (project_id),
            CONSTRAINT fk_amc_member FOREIGN KEY (member_row_id) REFERENCES account_members(id) ON DELETE CASCADE,
            CONSTRAINT fk_amc_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );
};
