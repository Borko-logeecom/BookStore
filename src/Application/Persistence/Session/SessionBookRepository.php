<?php

declare(strict_types=1);

namespace BookStore\Application\Persistence\Session;

use BookStore\Application\BussinesLogic\Model\Author\Author;
use BookStore\Application\BussinesLogic\Model\Book\Book;
use BookStore\Application\BussinesLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Infrastructure\Session\SessionHandler;

/**
 * Session-based implementation of the BookRepositoryInterface.
 *
 * This repository provides methods to create, read, and delete book data stored in PHP sessions.
 * It uses a session-stored counter to assign unique IDs to books.
 */
class SessionBookRepository implements BookRepositoryInterface
{
    private const SESSION_KEY_BOOKS = 'books';
    private const SESSION_KEY_BOOK_ID = 'book_id_counter';

    private SessionHandler $sessionHandler;

    /**
     * Constructor.
     * Initializes the session handler and sets the book ID counter if not already set.
     */
    public function __construct()
    {
        $this->sessionHandler = SessionHandler::getInstance();

        // Initialize book ID counter if not set
        if (!$this->sessionHandler->has(self::SESSION_KEY_BOOK_ID)) {
            $this->sessionHandler->set(self::SESSION_KEY_BOOK_ID, 1);
        }
    }

    /**
     * Finds all books belonging to a specific author.
     *
     * @param Author $author The author whose books are being retrieved.
     * @return array An array of book data for the specified author.
     */
    public function findByAuthorId(Author $author): array
    {
        $booksByAuthor = $this->sessionHandler->get(self::SESSION_KEY_BOOKS)[$author->getId()] ?? [];
        return $booksByAuthor;
    }

    /**
     * Retrieves a book by its ID.
     *
     * @param int $bookId The ID of the book to retrieve.
     * @return array|null The book data as an associative array, or null if not found.
     */
    public function getById(int $bookId): ?array
    {
        $allBooks = $this->sessionHandler->get(self::SESSION_KEY_BOOKS) ?? [];

        foreach ($allBooks as $authorBooks) {
            foreach ($authorBooks as $book) {
                if ($book['id'] === $bookId) {
                    return $book;
                }
            }
        }

        return null;
    }

    /**
     * Creates a new book in the session.
     *
     * The book is stored under the associated author's ID, and a unique global book ID is assigned.
     *
     * @param Book $book The book object to be saved.
     * @return int The ID of the newly created book.
     */
    public function create(Book $book): int
    {
        $allBooks = $this->sessionHandler->get(self::SESSION_KEY_BOOKS) ?? [];
        $authorId = $book->getAuthorId();

        if (!isset($allBooks[$authorId])) {
            $allBooks[$authorId] = [];
        }

        // Get the next global book ID
        $bookId = $this->sessionHandler->get(self::SESSION_KEY_BOOK_ID);

        $bookData = [
            'id' => $bookId,
            'author_id' => $authorId,
            'title' => $book->getTittle(),
            'publish_year' => $book->getPublishYear(),
        ];

        $allBooks[$authorId][] = $bookData;
        $this->sessionHandler->set(self::SESSION_KEY_BOOKS, $allBooks);

        // Increment the global book ID counter
        $this->sessionHandler->set(self::SESSION_KEY_BOOK_ID, $bookId + 1);

        return $bookData['id'];
    }

    /**
     * Deletes a book by its ID.
     *
     * @param int $bookId The ID of the book to delete.
     * @return bool True on success, false on failure.
     */
    public function delete(int $bookId): bool
    {
        $allBooks = $this->sessionHandler->get(self::SESSION_KEY_BOOKS) ?? [];

        foreach ($allBooks as $authorId => &$authorBooks) {
            $authorBooks = array_filter($authorBooks, fn($book) => $book['id'] !== $bookId);
        }

        $this->sessionHandler->set(self::SESSION_KEY_BOOKS, $allBooks);

        return true;
    }

    /**
     * Deletes all books belonging to a specific author.
     *
     * @param int $authorId The ID of the author whose books should be deleted.
     * @return bool True on success, false on failure.
     */
    public function deleteAllByAuthorId(int $authorId): bool
    {
        $allBooks = $this->sessionHandler->get(self::SESSION_KEY_BOOKS) ?? [];

        unset($allBooks[$authorId]);

        $this->sessionHandler->set(self::SESSION_KEY_BOOKS, $allBooks);

        return true;
    }
}
