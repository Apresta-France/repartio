<?php

declare(strict_types=1);

return function (PDO $pdo): void {
    $exists = $pdo->query("SHOW COLUMNS FROM projects LIKE 'uid'")->fetch();
    if (!$exists) {
        $pdo->exec('ALTER TABLE projects ADD COLUMN uid CHAR(32) NULL AFTER id');
    }

    $rows = $pdo->query('SELECT id FROM projects WHERE uid IS NULL OR uid = ""')->fetchAll(PDO::FETCH_ASSOC);
    $taken = $pdo->query('SELECT uid FROM projects WHERE uid IS NOT NULL AND uid != ""')->fetchAll(PDO::FETCH_COLUMN) ?: [];
    $taken = array_flip($taken);

    $update = $pdo->prepare('UPDATE projects SET uid = ? WHERE id = ?');
    foreach ($rows as $row) {
        do {
            $uid = bin2hex(random_bytes(16));
        } while (isset($taken[$uid]));
        $taken[$uid] = true;
        $update->execute([$uid, $row['id']]);
    }

    $index = $pdo->query("SHOW INDEX FROM projects WHERE Key_name = 'uq_projects_uid'")->fetch();
    if (!$index) {
        $pdo->exec('ALTER TABLE projects ADD UNIQUE KEY uq_projects_uid (uid)');
    }

    $col = $pdo->query("SHOW COLUMNS FROM projects LIKE 'uid'")->fetch(PDO::FETCH_ASSOC);
    if ($col && strtoupper((string) ($col['Null'] ?? '')) === 'YES') {
        $pdo->exec('ALTER TABLE projects MODIFY uid CHAR(32) NOT NULL');
    }
};
