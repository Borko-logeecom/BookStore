<?php

declare(strict_types=1);

namespace BookStore\Infrastructure\RepositoryInterfaces;

/**
 * Interface for accessing and managing Book data in a data store.
 * Defines the contract for Book Repository implementations.
 */
interface BookRepositoryInterface
{
    /**
     * Finds all books belonging to a specific author.
     * Used to display the list of books for an author.
     *
     * @param int $authorId The ID of the author.
     * @return array An array of associative arrays representing book data, or an empty array if no books are found.
     * Each book array should typically include keys like 'id', 'author_id', 'title', 'publication_year'.
     */
    public function findByAuthorId(int $authorId): array;

    /**
     * Finds a single book by its ID.
     * Used to retrieve data for the book edit form.
     *
     * @param int $bookId The ID of the book.
     * @return array|null An associative array representing the book data, or null if the book is not found.
     * The book array should typically include keys like 'id', 'author_id', 'title', 'publication_year'.
     */
    public function getById(int $bookId): ?array;

    /**
     * Creates a new book record in the data store.
     *
     * @param array $bookData An associative array containing book data (e.g., ['author_id' => 1, 'title' => 'Book Title', 'publication_year' => 2023]).
     * Must include 'author_id', 'title', and 'publication_year'.
     * @return array|null The data of the newly created book (including its generated 'id'), or null on failure (e.g., database issue).
     */
    public function create(array $bookData): ?array;

    /**
     * Updates an existing book record in the data store by its ID.
     * Used to save changes from the book edit form.
     *
     * @param int $bookId The ID of the book to update.
     * @param array $bookData An associative array containing the updated book data (e.g., ['title' => 'New Title', 'publication_year' => 2024]).
     * Should include 'title' and 'publication_year'.
     * @return bool True on success, false on failure.
     */
    public function update(int $bookId, array $bookData): bool;

    /**
     * Deletes a book record from the data store by its ID.
     * Used for deleting a single book from the list.
     *
     * @param int $bookId The ID of the book to delete.
     * @return bool True on success, false on failure.
     */
    public function delete(int $bookId): bool;

    /**
     * Deletes all book records belonging to a specific author.
     * This method is called when an author is deleted to maintain data integrity.
     *
     * @param int $authorId The ID of the author whose books should be deleted.
     * @return bool True on success, false on failure.
     */
    public function deleteAllByAuthorId(int $authorId): bool;
}