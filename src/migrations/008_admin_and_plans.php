<?php

declare(strict_types=1);

return function (PDO $pdo): void {
    $pdo->exec(
        'ALTER TABLE users
         ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT "user" AFTER plan'
    );
    $pdo->exec('ALTER TABLE users ADD INDEX idx_users_role (role)');

    $adminEmail = mb_strtolower(trim((string) (getenv('MAIL_ADMIN') ?: ($_ENV['MAIL_ADMIN'] ?? ''))));
    if ($adminEmail !== '') {
        $stmt = $pdo->prepare('UPDATE users SET role = "admin" WHERE email = ?');
        $stmt->execute([$adminEmail]);
    }
    $admins = $pdo->query('SELECT COUNT(*) FROM users WHERE role = "admin"')->fetchColumn();
    if ((int) $admins === 0) {
        $pdo->exec('UPDATE users SET role = "admin" ORDER BY id ASC LIMIT 1');
    }

    $pdo->exec(
        'CREATE TABLE plans (
            slug VARCHAR(32) PRIMARY KEY,
            label VARCHAR(80) NOT NULL,
            blurb VARCHAR(255) NOT NULL DEFAULT "",
            circuits INT UNSIGNED NOT NULL,
            horizon INT UNSIGNED NOT NULL,
            members INT UNSIGNED NOT NULL DEFAULT 0,
            price_monthly_ht DECIMAL(8,2) NOT NULL DEFAULT 0,
            price_yearly_ht DECIMAL(8,2) NOT NULL DEFAULT 0,
            sort_order INT NOT NULL DEFAULT 0,
            featured TINYINT(1) NOT NULL DEFAULT 0,
            cta_label VARCHAR(80) NOT NULL DEFAULT "",
            cta_url VARCHAR(190) NOT NULL DEFAULT "",
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $seed = [
        ['libre', 'Libre', 'Un circuit, deux ans de projection, et un lien de partage public.', 1, 24, 0, 0, 0, 1, 0, 'Créer un circuit', '/creer-un-compte'],
        ['complet', 'Complet', 'Trois circuits, cinq ans de projection, une personne invitée.', 3, 60, 1, 3.9, 39, 2, 1, 'Passer en Complet', '/creer-un-compte'],
        ['foyer', 'Foyer', 'Jusqu’à 50 circuits, 50 ans de projection, et jusqu’à 10 personnes pour gérer.', 50, 600, 10, 8.9, 89, 3, 0, 'Choisir Foyer', '/contact'],
    ];
    $stmt = $pdo->prepare(
        'INSERT INTO plans (slug, label, blurb, circuits, horizon, members, price_monthly_ht, price_yearly_ht, sort_order, featured, cta_label, cta_url, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())'
    );
    foreach ($seed as $row) {
        $stmt->execute($row);
    }
};
