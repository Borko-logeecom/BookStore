<?php

declare(strict_types=1);

namespace BookStore\Infrastructure\Database;

use Exception;
use PDO;
use PDOException;

class DatabaseConnection
{
    private PDO $connection;
    private static ?DatabaseConnection $instance = null;

    private function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=bookstore_app';

        try {
            $this->connection = new PDO(
                $dsn,
                'root',
                'password',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Creates and returns DatabaseConnection instance
     *
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
     * Returns PDO instance
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}