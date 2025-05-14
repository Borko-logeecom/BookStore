<?php

namespace BookStore\Application\Persistence\Session;

use BookStore\Application\BussinesLogic\Model\Author\Author;
use BookStore\Application\BussinesLogic\RepositoryInterfaces\AuthorRepositoryInterface;
use BookStore\Infrastructure\Session\SessionHandler;

/**
 * Repository class for interacting with author data stored in PHP sessions.
 *
 * This repository provides methods to create, read, update, and delete author data
 * stored in the session, making it a lightweight alternative to a database.
 * NOTE: This repository is intended for legacy data handling and is being replaced by MySQL.
 */
class SessionAuthorRepository implements AuthorRepositoryInterface
{
    private SessionHandler $sessionHandler;

    /**
     * Constructor.
     * Initializes the SessionHandler instance.
     */
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
     * The author ID is automatically incremented using a session-stored counter.
     *
     * @param Author $author The author object to be saved.
     * @return int The ID of the newly created author.
     */
    public function create(Author $author): int
    {
        $authorData = ['name' => $author->getName(), 'books' => $author->getBookCount()];

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
     * @param Author $author The author object containing updated data.
     * @return void
     */
    public function update(Author $author): void
    {
        $authorData = ['id' => $author->getId(), 'name' => $author->getName(), 'books' => $author->getBookCount()];
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