<?php

declare(strict_types=1);

namespace BookStore\Infrastructure\Database;

use Exception;
use PDO;
use PDOException;

/**
 * Singleton class responsible for managing the database connection.
 *
 * This class provides a single instance of a PDO connection to the MySQL database.
 * It uses the Singleton design pattern to ensure that only one database connection exists.
 */
class DatabaseConnection
{
    private PDO $connection;
    private static ?DatabaseConnection $instance = null;

    /**
     * Private constructor to prevent direct instantiation.
     *
     * Establishes a PDO connection to the MySQL database using the specified DSN, username, and password.
     *
     * @throws Exception If the database connection fails.
     */
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
     * Returns the singleton instance of the DatabaseConnection.
     *
     * If an instance does not exist, it is created.
     *
     * @return DatabaseConnection The singleton instance of this class.
     */
    public static function getInstance(): DatabaseConnection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Returns the active PDO connection instance.
     *
     * This method provides access to the PDO connection for executing database queries.
     *
     * @return PDO The active PDO connection.
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}