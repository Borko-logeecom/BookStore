<?php

namespace BookStore\Container;

use BookStore\Controller\AuthorController;
use BookStore\Application\AuthorService;
use BookStore\Infrastructure\Persistence\MySQL\MySQLAuthorRepository; // Update the namespace
use PDO;

/**
 * Factory class responsible for creating instances of repositories, services, and controllers.
 */
class ServiceFactory
{
    /**
     * Creates a MySQLAuthorRepository instance.
     * Establishes a database connection within this method.
     *
     * @return MySQLAuthorRepository The created AuthorRepository instance.
     */
    public function createAuthorRepository(): MySQLAuthorRepository // Rename the method
    {
        $host = 'localhost'; // Adjust if your MySQL server is not on localhost
        $dbname = 'bookstore_app';
        $username = 'root'; // Adjust if you use a different user
        $password = 'password'; // Enter the password for your MySQL root user (or another user)

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        return new MySQLAuthorRepository($pdo);
    }

    /**
     * Creates an AuthorService instance.
     * Injects the AuthorRepository dependency.
     *
     * @return AuthorService The created AuthorService instance.
     */
    public function createAuthorService(): AuthorService
    {
        $authorRepository = $this->createAuthorRepository(); // Use the new method
        return new AuthorService($authorRepository);
    }

    /**
     * Creates an AuthorController instance.
     * Injects the AuthorService dependency.
     *
     * @return AuthorController The created AuthorController instance.
     */
    public function createAuthorController(): AuthorController
    {
        $authorService = $this->createAuthorService();
        return new AuthorController($authorService);
    }
}