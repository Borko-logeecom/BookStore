<?php

namespace BookStore\Infrastructure\Container;

// Use statements for Author components
use BookStore\Application\BussinesLogic\RepositoryInterfaces\AuthorRepositoryInterface;
use BookStore\Application\BussinesLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Application\BussinesLogic\ServiceInterfaces\AuthorServiceInterface;
use BookStore\Application\BussinesLogic\ServiceInterfaces\BookServiceInterface;
use BookStore\Application\BussinesLogic\Services\AuthorService;
use BookStore\Application\BussinesLogic\Services\BookService;
use BookStore\Application\Persistence\MySQL\MySQLAuthorRepository;
use BookStore\Application\Persistence\MySQL\MySQLBookRepository;
use BookStore\Application\Persistence\Session\SessionAuthorRepository;
use BookStore\Application\Persistence\Session\SessionBookRepository;
use BookStore\Application\Presentation\Controller\AuthorController;
use BookStore\Application\Presentation\Controller\BookController;
use BookStore\Infrastructure\Database\DatabaseConnection;
use Exception;
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
    /**
     * @throws Exception
     */
    public static function init(): void
    {

        //Author bootstrap
/*
        ServiceRegistry::set(
            AuthorRepositoryInterface::class,
            new SessionAuthorRepository());
*/
        ServiceRegistry::set(
            AuthorRepositoryInterface::class,
            new MySQLAuthorRepository());

        ServiceRegistry::set(
            BookRepositoryInterface::class,
            new MySQLBookRepository()
        );

        ServiceRegistry::set(
            BookServiceInterface::class,
            new BookService(ServiceRegistry::get(BookRepositoryInterface::class))
        );

        ServiceRegistry::set(
            AuthorServiceInterface::class,
            new AuthorService(
                ServiceRegistry::get(AuthorRepositoryInterface::class),
                ServiceRegistry::get(BookServiceInterface::class)
            )
        );

        ServiceRegistry::set(
            AuthorController::class,
            new AuthorController(ServiceRegistry::get(AuthorServiceInterface::class))
        );

        ServiceRegistry::set(
            BookController::class,
            new BookController(ServiceRegistry::get(BookServiceInterface::class))
        );


    }

}