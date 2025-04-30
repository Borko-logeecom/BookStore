<?php

namespace BookStore\Container;

use BookStore\Controller\AuthorController;
use BookStore\Application\AuthorService;
use BookStore\Infrastructure\Persistence\MySQL\MySQLAuthorRepository; // Update the namespace
use PDO;

class ServiceFactory
{
    /**
     * Create a MySQLAuthorRepository instance
     *
     * @return MySQLAuthorRepository
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
     * Create an AuthorService instance
     *
     * @return AuthorService
     */
    public function createAuthorService(): AuthorService
    {
        $authorRepository = $this->createAuthorRepository(); // Use the new method
        return new AuthorService($authorRepository);
    }

    /**
     * Create an AuthorController instance
     *
     * @return AuthorController
     */
    public function createAuthorController(): AuthorController
    {
        $authorService = $this->createAuthorService();
        return new AuthorController($authorService);
    }
}