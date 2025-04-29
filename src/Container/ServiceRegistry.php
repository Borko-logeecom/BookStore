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
        return self::$services[$name] ?? null;
    }
}