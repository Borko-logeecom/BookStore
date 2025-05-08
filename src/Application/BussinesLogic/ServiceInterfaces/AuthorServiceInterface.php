<?php

namespace BookStore\Application\BussinesLogic\ServiceInterfaces;

use RuntimeException;

/**
 * Interface for the Author Service business logic.
 * Defines the contract for author-related operations.
 */
interface AuthorServiceInterface
{
    /**
     * Retrieves all authors.
     *
     * @return array A list of all authors.
     */
    public function getAllAuthors(): array;

    /**
     * Retrieves an author by their ID.
     *
     * @param int $id The ID of the author to retrieve.
     * @return array|null The author data as an associative array, or null if not found.
     */
    public function getAuthorById(int $id): ?array;

    /**
     * Creates a new author.
     *
     * @param string $firstName The first name of the author.
     * @param string $lastName The last name of the author.
     * @return array|null The newly created author data, or null if validation failed.
     */
    public function createAuthor(string $firstName, string $lastName): ?array;

    /**
     * Updates an existing author.
     *
     * @param int $id The ID of the author to update.
     * @param string $firstName The new first name of the author.
     * @param string $lastName The new last name of the author.
     * @return void
     */
    public function updateAuthor(int $id, string $firstName, string $lastName): void;

    /**
     * Deletes an author by their ID.
     *
     * @param int $id The ID of the author to delete.
     * @return void
     * @throws RuntimeException If an error occurs during deletion.
     */
    public function deleteAuthor(int $id): void;
}