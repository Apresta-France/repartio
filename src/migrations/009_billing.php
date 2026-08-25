<?php

declare(strict_types=1);

return function (PDO $pdo): void {
    $pdo->exec(
        'CREATE TABLE billing_profiles (
            user_id INT UNSIGNED PRIMARY KEY,
            type VARCHAR(20) NOT NULL DEFAULT "individual",
            name VARCHAR(190) NOT NULL DEFAULT "",
            company_name VARCHAR(190) NOT NULL DEFAULT "",
            email VARCHAR(190) NOT NULL DEFAULT "",
            line1 VARCHAR(190) NOT NULL DEFAULT "",
            line2 VARCHAR(190) NOT NULL DEFAULT "",
            postal_code VARCHAR(20) NOT NULL DEFAULT "",
            city VARCHAR(120) NOT NULL DEFAULT "",
            country CHAR(2) NOT NULL DEFAULT "FR",
            vat_number VARCHAR(40) NOT NULL DEFAULT "",
            siret VARCHAR(20) NOT NULL DEFAULT "",
            updated_at DATETIME NOT NULL,
            CONSTRAINT fk_billing_profiles_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE billing_subscriptions (
            user_id INT UNSIGNED PRIMARY KEY,
            reinvent_id INT UNSIGNED NULL,
            stripe_subscription_id VARCHAR(80) NULL,
            product_code VARCHAR(64) NULL,
            price_code VARCHAR(64) NULL,
            status VARCHAR(40) NULL,
            current_period_end DATETIME NULL,
            cancel_at_period_end TINYINT(1) NOT NULL DEFAULT 0,
            synced_at DATETIME NULL,
            updated_at DATETIME NOT NULL,
            CONSTRAINT fk_billing_subscriptions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'UPDATE plans SET cta_url = "/creer-un-compte", cta_label = "Choisir Foyer"
         WHERE slug = "foyer" AND cta_url = "/contact"'
    );
};
