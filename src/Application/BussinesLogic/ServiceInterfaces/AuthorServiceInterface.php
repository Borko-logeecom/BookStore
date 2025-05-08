<?php

namespace BookStore\Application\BussinesLogic\ServiceInterfaces;

use RuntimeException;

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
     * Performs validation on the first and last names before saving.
     *
     * @param string $firstName The first name of the author.
     * @param string $lastName The last name of the author.
     * @return array|null The newly created author data, or null if validation failed.
     */
    public function createAuthor(string $firstName, string $lastName): ?array;

    /**
     * Updates an existing author.
     * Performs validation on the first and last names before saving.
     *
     * @param int $id The ID of the author to update.
     * @param string $firstName The new first name of the author.
     * @param string $lastName The new last name of the author.
     * @return void
     */
    public function updateAuthor(int $id, string $firstName, string $lastName): void;

    /**
     * Deletes an author by their ID.
     * Also deletes all books associated with the author.
     *
     * @param int $id The ID of the author to delete.
     * @return void // Changed back to void
     * @throws RuntimeException If a repository error occurs.
     */
    public function deleteAuthor(int $id): void;
}