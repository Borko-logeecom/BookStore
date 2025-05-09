<?php

declare(strict_types=1);

namespace BookStore\Infrastructure\Database;

use PDO;

/**
 * Class responsible for establishing and providing a database connection.
 * Handles PDO connection logic.
 */
class DatabaseConnection
{

    private static ?DatabaseConnection $instance = null;

    /**
     * @return DatabaseConnection
     */
    public static function getInstance(): DatabaseConnection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Establishes and returns a PDO database connection instance.
     *
     * @return PDO The PDO database connection.
     * @throws \PDOException If the connection fails.
     */
    public static function getConnection(): PDO
    {
        $host = 'localhost';
        $dbname = 'bookstore_app';
        $username = 'root';
        $password = 'password';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}