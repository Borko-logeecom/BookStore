<?php

declare(strict_types=1);

namespace BookStore\Application\Persistence\Session;

use BookStore\Application\BussinesLogic\Model\Author\Author;
use BookStore\Application\BussinesLogic\Model\Book\Book;
use BookStore\Application\BussinesLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Infrastructure\Session\SessionHandler;

/**
 * Session-based implementation of the BookRepositoryInterface.
 * Interacts with the 'books' data stored in the PHP session.
 */
class SessionBookRepository implements BookRepositoryInterface
{
    private const SESSION_KEY_BOOKS = 'books';
    private const SESSION_KEY_BOOK_ID = 'book_id_counter';

    private SessionHandler $sessionHandler;

    public function __construct()
    {
        $this->sessionHandler = SessionHandler::getInstance();

        // Initialize book ID counter if not set
        if (!$this->sessionHandler->has(self::SESSION_KEY_BOOK_ID)) {
            $this->sessionHandler->set(self::SESSION_KEY_BOOK_ID, 1);
        }
    }

    public function findByAuthorId(Author $author): array
    {
        $booksByAuthor = $this->sessionHandler->get(self::SESSION_KEY_BOOKS)[$author->getId()] ?? [];
        return $booksByAuthor;
    }

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

    public function delete(int $bookId): bool
    {
        $allBooks = $this->sessionHandler->get(self::SESSION_KEY_BOOKS) ?? [];

        foreach ($allBooks as $authorId => &$authorBooks) {
            $authorBooks = array_filter($authorBooks, fn($book) => $book['id'] !== $bookId);
        }

        $this->sessionHandler->set(self::SESSION_KEY_BOOKS, $allBooks);

        return true;
    }

    public function deleteAllByAuthorId(int $authorId): bool
    {
        $allBooks = $this->sessionHandler->get(self::SESSION_KEY_BOOKS) ?? [];

        unset($allBooks[$authorId]);

        $this->sessionHandler->set(self::SESSION_KEY_BOOKS, $allBooks);

        return true;
    }
}
