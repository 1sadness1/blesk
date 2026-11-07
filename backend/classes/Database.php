<?php

class Database
{
    public static function getConnection(): PDO
    {
        $dbPath = __DIR__ . '/../database/blesk.sqlite';

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
