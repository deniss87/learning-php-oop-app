<?php

namespace App\Services;

use PDO;
use PDOException;

class Database
{
    protected static ?PDO $conn = null;

    public function __construct(string $server, string $dbName, string $dbUser, string $dbPassword)
    {
        if (self::$conn === null) {
            $this->connect($server, $dbName, $dbUser, $dbPassword);
        }
    }

    protected function connect(string $server, string $dbName, string $dbUser, string $dbPassword): void
    {
        $dsn = "mysql:host={$server};dbname={$dbName};charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $dbUser, $dbPassword, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

            self::$conn = $pdo;

        } catch (PDOException $e) {
            throw new \RuntimeException("Database connection failed: " . $e->getMessage());
        }
    }

    public static function connection(): PDO
    {
        return self::$conn;
    }
}
