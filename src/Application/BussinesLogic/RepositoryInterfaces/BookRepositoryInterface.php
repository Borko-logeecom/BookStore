<?php

declare(strict_types=1);

namespace BookStore\Application\BussinesLogic\RepositoryInterfaces;

use BookStore\Application\BussinesLogic\Model\Author\Author;
use BookStore\Application\BussinesLogic\Model\Book\Book;

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
     * @param Author $author
     * @return array An array of associative arrays representing book data, or an empty array if no books are found.
     * Each book array should typically include keys like 'id', 'author_id', 'title', 'publication_year'.
     */
    public function findByAuthorId(Author $author): array;

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
     * @param Book $book
     * @return int The data of the newly created book (including its generated 'id'), or null on failure (e.g., database issue).
     */
    public function create(Book $book): int;

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