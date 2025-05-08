<?php

declare(strict_types=1);

namespace BookStore\Application\BussinesLogic\Services;

use BookStore\Application\BussinesLogic\RepositoryInterfaces\BookRepositoryInterface;
use InvalidArgumentException;
use RuntimeException;

// Using for validation errors in input data
// Using for general application/repository errors

/**
 * Service class for handling book-related business logic.
 * Depends on a BookRepositoryInterface implementation.
 */
class BookService
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
        try {
            return $this->bookRepository->findByAuthorId($authorId);
        } catch (RuntimeException $e) {
            throw $e;
        }
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
        try {
            return $this->bookRepository->getById($bookId);
        } catch (RuntimeException $e) {
            throw $e;
        }
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
        if (!isset($bookData['author_id'], $bookData['title'], $bookData['publication_year'])) {
            throw new InvalidArgumentException("Required book data (author_id, title, publication_year) is missing.");
        }
        if (!is_int($bookData['author_id']) || $bookData['author_id'] <= 0) {
            throw new InvalidArgumentException("Invalid author_id provided.");
        }
        if (empty($bookData['title']) || !is_string($bookData['title']) || strlen($bookData['title']) > 255) {
            throw new InvalidArgumentException("Invalid or missing book title.");
        }
        if (!is_numeric($bookData['publication_year']) || $bookData['publication_year'] <= 0 || $bookData['publication_year'] > (int)date("Y")) {
            throw new InvalidArgumentException("Invalid publication year. Must be a positive number not in the future.");
        }
        try {
            return $this->bookRepository->create($bookData);
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    /**
     * Updates an existing book.
     * Includes basic validation for updated book data.
     *
     * @param int $bookId The ID of the book to update.
     * @param array $bookData Associative array with updated book data (must include 'title', 'publication_year').
     * @return bool True on success, false on failure (e.g., book not found, invalid data).
     * @throws InvalidArgumentException If input data is invalid.
     * @throws RuntimeException If a repository error occurs.
     */
    public function updateBook(int $bookId, array $bookData): bool
    {
        // Basic check for required data keys
        if (!isset($bookData['title'], $bookData['publication_year'])) {
            throw new InvalidArgumentException("Required book data (title, publication_year) for update is missing.");
        }

        // Validate title
        if (empty($bookData['title']) || !is_string($bookData['title']) || strlen($bookData['title']) > 255) {
            throw new InvalidArgumentException("Invalid or missing book title for update.");
        }

        // Validate publication_year
        if (!is_numeric($bookData['publication_year']) || $bookData['publication_year'] <= 0 || $bookData['publication_year'] > (int)date("Y")) {
            throw new InvalidArgumentException("Invalid publication year for update. Must be a positive number not in the future.");
        }
        // --- End Validation ---

        try {
            $book = $this->bookRepository->getById($bookId);
            if (!$book) {
                return false;
            }

            return $this->bookRepository->update($bookId, $bookData);
        } catch (RuntimeException $e) {
            throw $e;
        }
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
        try {
            return $this->bookRepository->delete($bookId);
        } catch (RuntimeException $e) {
            throw $e;
        }
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
        try {
            return $this->bookRepository->deleteAllByAuthorId($authorId);
        } catch (RuntimeException $e) {
            throw $e;
        }
    }
}