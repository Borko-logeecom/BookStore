<?php

namespace BookStore\Infrastructure\Container;

/**
 * Simple registry acting as a basic Service Locator.
 * Stores and provides access to service instances.
 */
class ServiceRegistry
{
    private static array $services = [];

    /**
     * Registers a service instance with a given name.
     *
     * @param string $name The name of the service.
     * @param object $service The service instance.
     * @return void
     */
    public static function set(string $name, object $service): void
    {
        self::$services[$name] = $service;
    }

    /**
     * Retrieves a service instance by its name.
     * If the service is not found, it initializes and registers core services
     * using the ServiceFactory before attempting to retrieve the requested service.
     *
     * @param string $name The name of the service to retrieve.
     * @return object|null The service instance if found, null otherwise.
     */
    public static function get(string $name): ?object
    {
        if (!isset(self::$services[$name])) {
            throw new \Exception('Service: '  . $name . ' not found');

        }

        return self::$services[$name];
    }


    private static function initialize(): void
    {
        $factory = new ServiceFactory();

        $authorRepository = $factory->createAuthorRepository();
        self::set('authorRepository', $authorRepository);

        $authorService = $factory->createAuthorService();
        self::set('authorService', $authorService);

        $authorController = $factory->createAuthorController();
        self::set('AuthorController', $authorController);

        $bookRepository = $factory->createBookRepository();
        self::set('bookRepository', $bookRepository);

        $bookService = $factory->createBookService();
        self::set('bookService', $bookService);

        $bookController = $factory->createBookController();
        self::set('BookController', $bookController);

    }

}