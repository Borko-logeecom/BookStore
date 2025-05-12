<?php

declare(strict_types=1);

namespace BookStore\Application\BussinesLogic\RepositoryInterfaces;

use BookStore\Application\BussinesLogic\Model\Author\Author;

/**
 * Interface for Author Repositories.
 * Defines the contract for any class acting as an Author Repository.
 */
interface AuthorRepositoryInterface
{
    /**
     * Retrieves all authors from the repository.
     *
     * @return array A list of all authors as associative arrays.
     */
    public function getAll(): array;

    /**
     * Retrieves a specific author from the repository by their ID.
     *
     * @param int $id The ID of the author to retrieve.
     * @return array|null The author data as an associative array, or null if not found.
     */
    public function getById(int $id): ?array;

    /**
     * @param Author $author
     * @return int
     */
    public function create(Author $author): int;

    /**
     * @param Author $author
     * @return void
     */
    public function update(Author $author): void;

    /**
     * Deletes an author from the repository by their ID.
     *
     * @param int $id The ID of the author to delete.
     * @return void
     */
    public function delete(int $id): void;

}