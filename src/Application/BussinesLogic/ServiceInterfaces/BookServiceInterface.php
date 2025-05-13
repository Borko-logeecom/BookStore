<?php

declare(strict_types=1);

namespace BookStore\Application\BussinesLogic\ServiceInterfaces;

use InvalidArgumentException;
use RuntimeException;

/**
 * Interface for the Book Service business logic.
 * Defines the contract for book-related operations.
 */
interface BookServiceInterface
{
    /**
     * Gets all books belonging to a specific author.
     *
     * @param int $authorId The ID of the author.
     * @return array An array of book data, or empty array if none found.
     * @throws RuntimeException If a repository error occurs.
     */
    public function getBooksByAuthorId(int $authorId): array;

    /**
     * Gets a single book by its ID.
     *
     * @param int $bookId The ID of the book.
     * @return array|null Book data, or null if not found.
     * @throws RuntimeException If a repository error occurs.
     */
    public function getBookById(int $bookId): ?array;

    /**
     * Creates a new book.
     * Includes basic validation for book data.
     *
     * @param array $bookData Associative array with book data (must include 'author_id', 'title', 'publication_year').
     * @return array|null Data of the newly created book (including ID), or null on failure (e.g., invalid data).
     * @throws InvalidArgumentException If input data is invalid.
     * @throws RuntimeException If a repository error occurs.
     */
    public function createBook(array $bookData): ?array;

    /**
     * Deletes a book.
     *
     * @param int $bookId The ID of the book to delete.
     * @return bool True on success, false on failure.
     * @throws RuntimeException If a repository error occurs.
     */
    public function deleteBook(int $bookId): bool;

    /**
     * Deletes all books belonging to a specific author.
     * Called when an author is deleted.
     *
     * @param int $authorId The ID of the author.
     * @return bool True on success, false on failure.
     * @throws RuntimeException If a repository error occurs.
     */
    public function deleteBooksByAuthorId(int $authorId): bool;
}
