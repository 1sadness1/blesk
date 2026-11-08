<?php
declare(strict_types=1);

class Database
{
    public static function getConnection(): PDO
    {
        $config = require __DIR__ . '/../config.php';

        $dbPath = $config['database']['path'];

        if (!file_exists($dbPath)) {
            throw new RuntimeException('Файл базы данных не найден');
        }

        $pdo = new PDO('sqlite:' . $dbPath);

        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $pdo->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );

        $pdo->exec('PRAGMA foreign_keys = ON');

        return $pdo;
    }
}
