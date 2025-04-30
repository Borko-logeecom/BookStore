<?php

namespace BookStore\Container;

use BookStore\Controller\AuthorController;
use BookStore\Application\AuthorService;
use BookStore\Infrastructure\Persistence\Session\SessionAuthorRepository;

class ServiceFactory
{
    /**
     * Create an SessionAuthorRepository instance
     *
     * @return SessionAuthorRepository
     */
    public function createSessionAuthorRepository(): SessionAuthorRepository
    {
        return new SessionAuthorRepository();
    }

    /**
     * Create an AuthorService instance
     *
     * @return AuthorService
     */
    public function createAuthorService(): AuthorService
    {
        $SessionAuthorRepository = self::createSessionAuthorRepository();
        return new AuthorService($SessionAuthorRepository);
    }

    /**
     * Create an AuthorController instance
     *
     * @return AuthorController
     */
    public function createAuthorController(): AuthorController
    {
        $authorService = self::createAuthorService();
        return new AuthorController($authorService);
    }
}