<?php

namespace BookStore\Infrastructure\Container;

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



/**
 * Factory class responsible for creating instances of repositories, services, and controllers.
 *
 * This factory class is responsible for managing dependencies and switching between different repository types
 * (MySQL or Session-based) for Authors and Books. It uses the ServiceRegistry to register and retrieve instances.
 */
class ServiceFactory
{
    /**
     * Initializes the service container by registering all required services, repositories, and controllers.
     *
     * This method sets up the following components:
     * - Author and Book repositories (MySQL-based)
     * - Author and Book services
     * - Author and Book controllers
     *
     * All components are registered in the ServiceRegistry for global access.
     *
     * @throws Exception If any dependency fails to initialize.
     */
    public static function init(): void
    {
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