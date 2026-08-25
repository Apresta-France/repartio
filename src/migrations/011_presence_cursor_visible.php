<?php

declare(strict_types=1);

return function (PDO $pdo): void {
    $pdo->exec(
        'ALTER TABLE circuit_presence
         ADD cursor_visible TINYINT(1) NOT NULL DEFAULT 0 AFTER cursor_y'
    );
};
