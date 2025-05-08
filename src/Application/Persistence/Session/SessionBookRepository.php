<?php

declare(strict_types=1);

namespace BookStore\Application\Persistence\Session;

use BookStore\Application\BussinesLogic\RepositoryInterfaces\BookRepositoryInterface;
use RuntimeException;

/**
 * Session-based implementation of the BookRepositoryInterface.
 * Stores book data in the $_SESSION superglobal.
 */
class SessionBookRepository implements BookRepositoryInterface
{
    private const SESSION_KEY_BOOKS = 'books';
    private const SESSION_KEY_NEXT_ID = 'next_book_id';

    /**
     * Constructor.
     * Ensures the necessary session keys are initialized.
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            throw new RuntimeException("Session is not started. SessionBookRepository requires an active session.");
        }

        // Initialize the session storage for books if it doesn't exist
        if (!isset($_SESSION[self::SESSION_KEY_BOOKS]) || !is_array($_SESSION[self::SESSION_KEY_BOOKS])) {
            $_SESSION[self::SESSION_KEY_BOOKS] = [];
        }

        // Initialize the ID counter if it doesn't exist
        if (!isset($_SESSION[self::SESSION_KEY_NEXT_ID]) || !is_int($_SESSION[self::SESSION_KEY_NEXT_ID])) {
            $_SESSION[self::SESSION_KEY_NEXT_ID] = 1;
        }
    }

    /**
     * Finds all books belonging to a specific author from the session.
     *
     * @param int $authorId The ID of the author.
     * @return array An array of book data arrays, or an empty array if no books are found.
     * Each book array includes keys like 'id', 'author_id', 'title', 'publication_year'.
     */
    public function findByAuthorId(int $authorId): array
    {
        $allBooks = $_SESSION[self::SESSION_KEY_BOOKS];
        $authorBooks = [];

        foreach ($allBooks as $book) {
            if (isset($book['author_id']) && $book['author_id'] === $authorId) {
                $authorBooks[] = $book;
            }
        }

        return $authorBooks;
    }

    /**
     * Finds a single book by its ID from the session.
     *
     * @param int $bookId The ID of the book.
     * @return array|null An associative array representing the book data, or null if the book is not found.
     * The book array includes keys like 'id', 'author_id', 'title', 'publication_year'.
     */
    public function getById(int $bookId): ?array
    {
        $allBooks = $_SESSION[self::SESSION_KEY_BOOKS];

        foreach ($allBooks as $book) {
            if (isset($book['id']) && $book['id'] === $bookId) {
                return $book;
            }
        }

        return null; // Book not found
    }

    /**
     * Creates a new book record in the session.
     *
     * @param array $bookData An associative array containing book data (['author_id', 'title', 'publication_year']).
     * @return array|null The data of the newly created book (including its generated 'id'), or null on failure (e.g., data missing).
     * @throws RuntimeException If required data is missing in $bookData.
     */
    public function create(array $bookData): ?array
    {
        // Basic check for required data keys
        if (!isset($bookData['author_id'], $bookData['title'], $bookData['publication_year'])) {
            // In a real session repo, data validation might be minimal, but check structure.
            throw new RuntimeException("Missing required book data for creation in session repository.");
        }

        // Generate a unique ID for the session book
        $newId = $_SESSION[self::SESSION_KEY_NEXT_ID];
        $bookData['id'] = $newId; // Add ID to the book data

        // Add the new book to the session array
        $_SESSION[self::SESSION_KEY_BOOKS][] = $bookData;

        // Increment the ID counter for the next creation
        $_SESSION[self::SESSION_KEY_NEXT_ID]++;

        // Return the data of the newly created book
        return $bookData;
    }

    /**
     * Updates an existing book record in the session by its ID.
     *
     * @param int $bookId The ID of the book to update.
     * @param array $bookData An associative array containing the updated book data (['title', 'publication_year']).
     * @return bool True on success, false on failure (e.g., book not found, data missing).
     * @throws RuntimeException If required data is missing in $bookData.
     */
    public function update(int $bookId, array $bookData): bool
    {
        // Basic check for required data keys
        if (!isset($bookData['title'], $bookData['publication_year'])) {
            throw new RuntimeException("Missing required book data for update in session repository.");
        }

        $allBooks = $_SESSION[self::SESSION_KEY_BOOKS];

        // Find the book by ID and update its data
        foreach ($allBooks as $index => $book) {
            if (isset($book['id']) && $book['id'] === $bookId) {
                // Update only the fields that are expected to be updated
                $_SESSION[self::SESSION_KEY_BOOKS][$index]['title'] = $bookData['title'];
                $_SESSION[self::SESSION_KEY_BOOKS][$index]['publication_year'] = $bookData['publication_year'];
                // Note: author_id is typically not updated via book edit form
                return true; // Update successful
            }
        }

        return false; // Book not found with the given ID
    }

    /**
     * Deletes a book record from the session by its ID.
     *
     * @param int $bookId The ID of the book to delete.
     * @return bool True on success, false on failure (e.g., book not found).
     */
    public function delete(int $bookId): bool
    {
        $allBooks = $_SESSION[self::SESSION_KEY_BOOKS];
        $updatedBooks = [];
        $found = false;

        // Iterate and copy books that are NOT the one we want to delete
        foreach ($allBooks as $book) {
            if (isset($book['id']) && $book['id'] === $bookId) {
                $found = true;
                // Skip this book - it will not be added to $updatedBooks
            } else {
                $updatedBooks[] = $book;
            }
        }

        if ($found) {
            $_SESSION[self::SESSION_KEY_BOOKS] = $updatedBooks;
            // array_values re-indexes the array numerically if needed, keeping it clean
            $_SESSION[self::SESSION_KEY_BOOKS] = array_values($_SESSION[self::SESSION_KEY_BOOKS]);
            return true; // Deletion successful
        }

        return false; // Book not found
    }

    /**
     * Deletes all book records belonging to a specific author from the session.
     *
     * @param int $authorId The ID of the author whose books should be deleted.
     * @return bool True on success, false on failure (e.g., session issue, but less likely here).
     */
    public function deleteAllByAuthorId(int $authorId): bool
    {
        $allBooks = $_SESSION[self::SESSION_KEY_BOOKS];
        $updatedBooks = [];

        // Iterate and copy books that do NOT belong to the author we want to delete
        foreach ($allBooks as $book) {
            if (isset($book['author_id']) && $book['author_id'] !== $authorId) {
                $updatedBooks[] = $book;
            }
        }

        $_SESSION[self::SESSION_KEY_BOOKS] = $updatedBooks;
        // array_values re-indexes the array numerically
        $_SESSION[self::SESSION_KEY_BOOKS] = array_values($_SESSION[self::SESSION_KEY_BOOKS]);

        // In session, this operation itself is always "successful" if the session is active,
        // regardless of whether any books were actually deleted.
        return true;
    }
}