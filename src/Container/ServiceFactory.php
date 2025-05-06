<?php

namespace BookStore\Container;

use BookStore\Controller\AuthorController;
use BookStore\Application\AuthorService;
use BookStore\Infrastructure\Persistence\MySQL\MySQLAuthorRepository; // Update the namespace
use BookStore\Infrastructure\Database\DatabaseConnection;
use BookStore\Infrastructure\Persistence\Session\SessionAuthorRepository;
use BookStore\Infrastructure\RepositoryInterfaces\AuthorRepositoryInterface;

/**
 * Factory class responsible for creating instances of repositories, services, and controllers.
 */
class ServiceFactory
{
    private const AUTHOR_REPOSITORY_TYPE = 'mysql';

    /**
     * Creates a MySQLAuthorRepository instance.
     * Injects the database connection dependency into the repository.
     *
     * @return AuthorRepositoryInterface  The created AuthorRepository instance.
     */
    public function createAuthorRepository(): AuthorRepositoryInterface
    {
        if (self::AUTHOR_REPOSITORY_TYPE === 'mysql') {
            $pdo = DatabaseConnection::getConnection();
            return new MySQLAuthorRepository($pdo);
        } elseif (self::AUTHOR_REPOSITORY_TYPE === 'session') {
            return new SessionAuthorRepository();
        } else {
            throw new \InvalidArgumentException("Unknown author repository type: " . self::AUTHOR_REPOSITORY_TYPE);
        }
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

    /**
     * Returns the configured author repository type.
     *
     * @return string The repository type ('mysql' or 'session').
     */
    public static function getRepositoryType(): string
    {
        return self::AUTHOR_REPOSITORY_TYPE;
    }

}