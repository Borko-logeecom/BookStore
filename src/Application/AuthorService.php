<?php

namespace BookStore\Application;

use BookStore\Infrastructure\Persistence\MySQL\MySQLAuthorRepository;

/**
 * Service class for handling author-related business logic.
 */
class AuthorService
{
    private MySQLAuthorRepository $authorRepository;

    /**
     * Constructor.
     * Initializes the AuthorService with a MySQLAuthorRepository instance.
     *
     * @param MySQLAuthorRepository $authorRepository The AuthorRepository dependency.
     */
    public function __construct(MySQLAuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * Retrieves all authors.
     *
     * @return array A list of all authors.
     */
    public function getAllAuthors(): array
    {
        return $this->authorRepository->getAll();
    }

    /**
     * Retrieves an author by their ID.
     *
     * @param int $id The ID of the author to retrieve.
     * @return array|null The author data as an associative array, or null if not found.
     */
    public function getAuthorById(int $id): ?array
    {
        return $this->authorRepository->getById($id);
    }

    /**
     * Creates a new author.
     * Performs validation on the first and last names before saving.
     *
     * @param string $firstName The first name of the author.
     * @param string $lastName The last name of the author.
     * @return array|null The newly created author data, or null if validation failed.
     */
    public function createAuthor(string $firstName, string $lastName): ?array
    {
        $firstName = trim($firstName);
        $lastName = trim($lastName);

        if (empty($firstName) || strlen($firstName) > 100 || empty($lastName) || strlen($lastName) > 100) {
            return null; // Validation failed
        }

        $name = $firstName . ' ' . $lastName;
        $authorData = ['name' => $name, 'books' => 0];
        $newAuthorId = $this->authorRepository->create($authorData);

        return $this->getAuthorById($newAuthorId);
    }

    /**
     * Retrieves an author by their full name.
     *
     * @param string $name The full name of the author.
     * @return array|null The author data as an associative array, or null if not found.
     */
    private function getAuthorByName(string $name): ?array
    {
        $authors = $this->authorRepository->getAll();
        foreach ($authors as $author) {
            if ($author['name'] === $name) {
                return $author;
            }
        }

        return null;
    }

    /**
     * Updates an existing author.
     * Performs validation on the first and last names before saving.
     *
     * @param int $id The ID of the author to update.
     * @param string $firstName The new first name of the author.
     * @param string $lastName The new last name of the author.
     * @return void
     */
    public function updateAuthor(int $id, string $firstName, string $lastName): void
    {
        $firstName = trim($firstName);
        $lastName = trim($lastName);

        if (empty($firstName) || strlen($firstName) > 100 || empty($lastName) || strlen($lastName) > 100) {
            return;
        }

        $name = $firstName . ' ' . $lastName;
        $authorData = ['id' => $id, 'name' => $name, 'books' => $this->getAuthorById($id)['books'] ?? 0];
        $this->authorRepository->update($authorData);
    }

    /**
     * Deletes an author by their ID.
     *
     * @param int $id The ID of the author to delete.
     * @return void
     */
    public function deleteAuthor(int $id): void
    {
        $this->authorRepository->delete($id);
    }
}