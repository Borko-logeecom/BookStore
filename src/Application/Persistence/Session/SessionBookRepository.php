<?php

declare(strict_types=1);

namespace BookStore\Application\Persistence\Session;

use BookStore\Application\BussinesLogic\Model\Book\Book;
use BookStore\Application\BussinesLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Infrastructure\Session\SessionHandler;

/**
 * Repository class for interacting with book data stored in PHP sessions.
 */
class SessionBookRepository implements BookRepositoryInterface
{
    private SessionHandler $sessionHandler;

    public function __construct()
    {
        $this->sessionHandler = SessionHandler::getInstance();
    }

    /**
     * Finds all books belonging to a specific author.
     */
    public function findByAuthorId(int $authorId): array
    {
        $books = $this->sessionHandler->get('books') ?? [];

        return array_filter($books, fn($book) => $book['author_id'] === $authorId);
    }

    /**
     * Finds a single book by its ID.
     */
    public function getById(int $bookId): ?array
    {
        $books = $this->sessionHandler->get('books') ?? [];

        foreach ($books as $book) {
            if ($book['id'] === $bookId) {
                return $book;
            }
        }

        return null;
    }

    /**
     * Creates a new book record.
     */
    public function create(Book $book): int
    {
        $books = $this->sessionHandler->get('books') ?? [];

        $newId = count($books) + 1;
        $bookData = [
            'id' => $newId,
            'author_id' => $book->getAuthorId(),
            'title' => $book->getTittle(),
            'publication_year' => $book->getPublishYear()
        ];

        $books[] = $bookData;
        $this->sessionHandler->set('books', $books);

        return $newId;
    }

    /**
     * Deletes a book by its ID.
     */
    public function delete(int $bookId): bool
    {
        $books = $this->sessionHandler->get('books') ?? [];

        foreach ($books as $index => $book) {
            if ($book['id'] === $bookId) {
                unset($books[$index]);
                $this->sessionHandler->set('books', array_values($books));
                return true;
            }
        }

        return false;
    }

    /**
     * Deletes all books belonging to a specific author.
     */
    public function deleteAllByAuthorId(int $authorId): bool
    {
        $books = $this->sessionHandler->get('books') ?? [];

        $books = array_filter($books, fn($book) => $book['author_id'] !== $authorId);

        $this->sessionHandler->set('books', array_values($books));

        return true;
    }
}