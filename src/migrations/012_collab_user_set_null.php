<?php

declare(strict_types=1);

return function (PDO $pdo): void {
    $pdo->exec('ALTER TABLE circuit_versions DROP FOREIGN KEY fk_versions_user');
    $pdo->exec('ALTER TABLE circuit_versions MODIFY user_id INT UNSIGNED NULL');
    $pdo->exec(
        'ALTER TABLE circuit_versions
         ADD CONSTRAINT fk_versions_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL'
    );

    $pdo->exec('ALTER TABLE circuit_live DROP FOREIGN KEY fk_live_user');
    $pdo->exec('ALTER TABLE circuit_live MODIFY user_id INT UNSIGNED NULL');
    $pdo->exec(
        'ALTER TABLE circuit_live
         ADD CONSTRAINT fk_live_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL'
    );
};
