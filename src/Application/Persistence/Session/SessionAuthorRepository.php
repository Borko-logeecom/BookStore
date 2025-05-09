<?php

namespace BookStore\Application\Persistence\Session;

use BookStore\Application\BussinesLogic\RepositoryInterfaces\AuthorRepositoryInterface;
use BookStore\Infrastructure\Session\SessionHandler;

/**
 * Repository class for interacting with author data stored in PHP sessions.
 * NOTE: This repository is intended for legacy data handling and is being replaced by MySQL.
 */
class SessionAuthorRepository implements AuthorRepositoryInterface
{
    private SessionHandler $sessionHandler;

    public function __construct()
    {
        $this->sessionHandler = SessionHandler::getInstance();
    }

    /**
     * Retrieves all authors from the session.
     *
     * @return array A list of all authors stored in the session.
     */
    public function getAll(): array
    {
        return $this->sessionHandler->get('authors') ?? [];
    }

    /**
     * Retrieves a specific author from the session by their ID.
     *
     * @param int $id The ID of the author to retrieve.
     * @return array|null The author data as an associative array, or null if not found.
     */
    public function getById(int $id): ?array
    {
        $authors = $this->sessionHandler->get('authors') ?? [];
        foreach ($authors as $author) {
            if (isset($author['id']) && $author['id'] === $id) {
                return $author;
            }
        }
        return null;
    }

    /**
     * Creates a new author in the session.
     *
     * @param array $authorData Associative array containing author data (['name' => ..., 'books' => ...]).
     * @return int The ID of the newly created author.
     */
    public function create(array $authorData): int
    {
        $authorIdCounter = $this->sessionHandler->get('author_id_counter') ?? 0;
        $authors = $this->sessionHandler->get('authors') ?? [];

        $authorIdCounter++;
        $authorData['id'] = $authorIdCounter;

        $authors[] = $authorData;

        $this->sessionHandler->set('author_id_counter', $authorIdCounter);
        $this->sessionHandler->set('authors', $authors);

        return $authorData['id'];
    }

    /**
     * Updates an existing author in the session.
     *
     * @param array $authorData Associative array containing author data (['id' => ..., 'name' => ..., 'books' => ...]).
     * @return void
     */
    public function update(array $authorData): void
    {
        $authors = $this->sessionHandler->get('authors') ?? [];
        foreach ($authors as $index => $author) {
            if (isset($author['id']) && $author['id'] === $authorData['id']) {
                $authors[$index] = $authorData;
                break;
            }
        }
        $this->sessionHandler->set('authors', $authors);
    }

    /**
     * Deletes an author from the session by their ID.
     *
     * @param int $id The ID of the author to delete.
     * @return void
     */
    public function delete(int $id): void
    {
        $authors = $this->sessionHandler->get('authors') ?? [];

        foreach ($authors as $index => $author) {
            if (isset($author['id']) && $author['id'] === $id) {
                unset($authors[$index]);
                break;
            }
        }

        $this->sessionHandler->set('authors', array_values($authors));
    }
}