<?php

namespace BookStore\Container;

use BookStore\Controller\AuthorController;
use BookStore\Application\AuthorService;
use BookStore\Infrastructure\Persistence\MySQL\MySQLAuthorRepository; // Update the namespace
use BookStore\Infrastructure\Database\DatabaseConnection;

/**
 * Factory class responsible for creating instances of repositories, services, and controllers.
 */
class ServiceFactory
{
    /**
     * Creates a MySQLAuthorRepository instance.
     * Injects the database connection dependency into the repository.
     *
     * @return MySQLAuthorRepository The created AuthorRepository instance.
     */
    public function createAuthorRepository(): MySQLAuthorRepository // Rename the method
    {
        $pdo = DatabaseConnection::getConnection();

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