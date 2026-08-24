<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function connect(bool $withoutDatabase = false): PDO
    {
        if (self::$pdo instanceof PDO && !$withoutDatabase) {
            return self::$pdo;
        }

        $host = (string) Config::get('db.host', '127.0.0.1');
        $port = (int) Config::get('db.port', 3306);
        $name = (string) Config::get('db.name', 'repartio');
        $user = (string) Config::get('db.user', 'root');
        $pass = (string) Config::get('db.pass', '');
        $dsn = $withoutDatabase
            ? sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $host, $port)
            : sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $name);

        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        if (!$withoutDatabase) {
            self::$pdo = $pdo;
        }

        return $pdo;
    }

    public static function pdo(): PDO
    {
        return self::connect();
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function lastId(): string
    {
        return self::pdo()->lastInsertId();
    }

    public static function ping(): bool
    {
        try {
            self::connect(true);
            return true;
        } catch (PDOException) {
            return false;
        }
    }
}
