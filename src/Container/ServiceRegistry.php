<?php

namespace BookStore\Container;

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
     *
     * @param string $name The name of the service to retrieve.
     * @return ?object The service instance if found, null otherwise.
     */
    public static function get(string $name): ?object
    {
        if (isset(self::$services[$name])) {
            return self::$services[$name];
        }

        $factory = new ServiceFactory();

        $authorRepository = $factory->createAuthorRepository();
        self::set('authorRepository', $authorRepository);

        $authorService = $factory->createAuthorService();
        self::set('authorService', $authorService);

        $authorController = $factory->createAuthorController();
        self::set('AuthorController', $authorController);

        return self::$services[$name] ?? null;
    }
}