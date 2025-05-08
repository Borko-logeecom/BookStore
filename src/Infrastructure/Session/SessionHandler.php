<?php

namespace BookStore\Infrastructure\Session;

class SessionHandler
{
    /**
     * @var ?SessionHandler
     */
    private static ?SessionHandler $instance = null;

    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @return SessionHandler
     */
    public static function getInstance(): SessionHandler
    {
        if (self::$instance === null) {
            self::$instance = new SessionHandler();
        }

        return self::$instance;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

}