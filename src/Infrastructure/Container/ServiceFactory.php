<?php

namespace BookStore\Infrastructure\Container;

// Use statements for Author components
use BookStore\Application\BussinesLogic\RepositoryInterfaces\AuthorRepositoryInterface;
use BookStore\Application\BussinesLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Application\BussinesLogic\Services\AuthorService;
use BookStore\Application\BussinesLogic\Services\BookService;
use BookStore\Application\Persistence\MySQL\MySQLAuthorRepository;
use BookStore\Application\Persistence\MySQL\MySQLBookRepository;
use BookStore\Application\Persistence\Session\SessionAuthorRepository;
use BookStore\Application\Persistence\Session\SessionBookRepository;
use BookStore\Application\Presentation\Controller\AuthorController;
use BookStore\Application\Presentation\Controller\BookController;
use BookStore\Infrastructure\Database\DatabaseConnection;
use InvalidArgumentException;
use RuntimeException;

// Use statement for Database connection

// Use statements for NEW Book components

// Using for unknown repository type errors

/**
 * Factory class responsible for creating instances of repositories, services, and controllers.
 * Manages dependencies and repository type switching for Authors and Books.
 */
class ServiceFactory
{
    // Configuration constants for repository types
    private const AUTHOR_REPOSITORY_TYPE = 'mysql';
    private const BOOK_REPOSITORY_TYPE = 'session';

    /**
     * Returns the configured author repository type.
     *
     * @return string The repository type ('mysql' or 'session').
     */
    public static function getAuthorRepositoryType(): string
    {
        return self::AUTHOR_REPOSITORY_TYPE;
    }

    /**
     * Returns the configured book repository type.
     *
     * @return string The repository type ('mysql' or 'session').
     */
    public static function getBookRepositoryType(): string
    {
        return self::BOOK_REPOSITORY_TYPE;
    }


    /**
     * Creates an AuthorRepository instance based on configuration.
     * Injects dependencies if required (e.g., DatabaseConnection for MySQL).
     *
     * @return AuthorRepositoryInterface The created AuthorRepository instance.
     * @throws InvalidArgumentException If an unknown repository type is configured.
     * @throws RuntimeException If database connection fails for MySQL repository. // Added RuntimeException from DatabaseConnection
     */
    public function createAuthorRepository(): AuthorRepositoryInterface
    {
        $repositoryType = self::getAuthorRepositoryType();

        if ($repositoryType === 'mysql') {
            $dbConnection = DatabaseConnection::getConnection();
            return new MySQLAuthorRepository($dbConnection);
        } elseif ($repositoryType === 'session') {
            return new SessionAuthorRepository();
        } else {
            throw new InvalidArgumentException("Unknown author repository type configured: " . $repositoryType);
        }
    }

    /**
     * Creates a BookRepository instance based on configuration.
     * Injects dependencies if required (e.g., DatabaseConnection for MySQL).
     *
     * @return BookRepositoryInterface The created BookRepository instance.
     * @throws InvalidArgumentException If an unknown repository type is configured.
     * @throws RuntimeException If database connection fails for MySQL repository. // Added RuntimeException from DatabaseConnection
     */
    public function createBookRepository(): BookRepositoryInterface
    {
        // Use the new static getter method
        $repositoryType = self::getBookRepositoryType();

        if ($repositoryType === 'mysql') {
            $dbConnection = DatabaseConnection::getConnection();
            return new MySQLBookRepository($dbConnection);
        } elseif ($repositoryType === 'session') {
            return new SessionBookRepository();
        } else {
            throw new InvalidArgumentException("Unknown book repository type configured: " . $repositoryType);
        }
    }

    /**
     * Creates an AuthorService instance.
     * Injects AuthorRepository and BookService dependencies.
     *
     * @return AuthorService The created AuthorService instance.
     */
    public function createAuthorService(): AuthorService
    {
        $authorRepository = $this->createAuthorRepository();

        $bookService = $this->createBookService();

        return new AuthorService($authorRepository, $bookService);
    }

    /**
     * Creates a BookService instance.
     * Injects the BookRepository dependency.
     *
     * @return BookService The created BookService instance.
     */
    public function createBookService(): BookService
    {
        $bookRepository = $this->createBookRepository();

        return new BookService($bookRepository);
    }


    /**
     * Creates an AuthorController instance.
     * Injects the AuthorService dependency.
     * Note: AuthorController now indirectly depends on BookService via AuthorService.
     *
     * @return AuthorController The created AuthorController instance.
     */
    public function createAuthorController(): AuthorController
    {
        $authorService = $this->createAuthorService();

        return new AuthorController($authorService);
    }

    /**
     * Creates a BookController instance.
     * Injects the BookService dependency into the BookController constructor.
     *
     * @return BookController The created BookController instance.
     */
    public function createBookController(): BookController
    {
        $bookService = $this->createBookService();

        return new BookController($bookService);
    }

}