<?php

declare(strict_types=1);

namespace BookStore\Application\BussinesLogic\Services;

use BookStore\Application\BussinesLogic\Model\Author\Author;
use BookStore\Application\BussinesLogic\Model\Book\Book;
use BookStore\Application\BussinesLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Application\BussinesLogic\ServiceInterfaces\BookServiceInterface;
use InvalidArgumentException;
use RuntimeException;

// Using for validation errors in input data
// Using for general application/repository errors

/**
 * Service class for handling book-related business logic.
 * Depends on a BookRepositoryInterface implementation.
 */
class BookService implements BookServiceInterface
{
    private BookRepositoryInterface $bookRepository;

    /**
     * Constructor.
     * Injects the book repository dependency.
     *
     * @param BookRepositoryInterface $bookRepository The book repository implementation.
     */
    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Gets all books belonging to a specific author.
     *
     * @param int $authorId The ID of the author.
     * @return array An array of book data, or empty array if none found.
     * @throws RuntimeException If a repository error occurs.
     */
    public function getBooksByAuthorId(int $authorId): array
    {
        $author = new Author('');
        $author->setId($authorId);

        return $this->bookRepository->findByAuthorId($author);
    }

    /**
     * Gets a single book by its ID.
     *
     * @param int $bookId The ID of the book.
     * @return array|null Book data, or null if not found.
     * @throws RuntimeException If a repository error occurs.
     */
    public function getBookById(int $bookId): ?array
    {
        return $this->bookRepository->getById($bookId);
    }

    /**
     * Creates a new book.
     * Includes basic validation for book data.
     *
     * @param array $bookData Associative array with book data (must include 'author_id', 'title', 'publication_year').
     * @return array|null Data of the newly created book (including ID), or null on failure (e.g., invalid data).
     * @throws InvalidArgumentException If input data is invalid.
     * @throws RuntimeException If a repository error occurs.
     */
    public function createBook(array $bookData): ?array
    {

        $title = $bookData['title'] ?? '';
        $year = $bookData['year'] ?? '';
        $authorId = isset($bookData['author_id']) ? (int)$bookData['author_id'] : null;

        $book = new Book(0,$title, $year, $authorId);

        $bookID = $this->bookRepository->create($book);

        return $this->getBookById($bookID);
    }

    /**
     * Deletes a book.
     *
     * @param int $bookId The ID of the book to delete.
     * @return bool True on success, false on failure.
     * @throws RuntimeException If a repository error occurs.
     */
    public function deleteBook(int $bookId): bool
    {
        return $this->bookRepository->delete($bookId);

    }

    /**
     * Deletes all books belonging to a specific author.
     * Called when an author is deleted.
     *
     * @param int $authorId The ID of the author.
     * @return bool True on success, false on failure.
     * @throws RuntimeException If a repository error occurs.
     */
    public function deleteBooksByAuthorId(int $authorId): bool
    {
        return $this->bookRepository->deleteAllByAuthorId($authorId);
    }

}