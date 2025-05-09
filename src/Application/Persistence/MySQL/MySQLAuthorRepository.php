<?php

namespace BookStore\Application\Persistence\MySQL;

use BookStore\Application\BussinesLogic\RepositoryInterfaces\AuthorRepositoryInterface;
use BookStore\Infrastructure\Database\DatabaseConnection;
use PDO;

/**
 * Repository class for interacting with author data in a MySQL database.
 */
class MySQLAuthorRepository implements AuthorRepositoryInterface
{
    private string $tableName = 'authors';

    /**
     * Retrieves a single author from the database by their ID.
     *
     * @param int $id The ID of the author to retrieve.
     * @return array|null The author data as an associative array, or null if not found.
     */
    public function getById(int $id): ?array
    {
        $stmt = DatabaseConnection::getInstance()->getConnection()->prepare("SELECT * FROM {$this->tableName} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Retrieves all authors from the database.
     *
     * @return array A list of all authors as associative arrays.
     */
    public function getAll(): array
    {
        return DatabaseConnection::getInstance()->getConnection()->query("SELECT * FROM {$this->tableName}")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Creates a new author in the database.
     *
     * @param array $authorData Associative array containing author data (['name' => ..., 'books' => ...]).
     * @return int The ID of the newly created author.
     */
    public function create(array $authorData): int
    {
        $stmt = DatabaseConnection::getInstance()->getConnection()->prepare("INSERT INTO {$this->tableName} (name, books) VALUES (:name, :books)");
        $stmt->bindParam(':name', $authorData['name'], PDO::PARAM_STR);
        $stmt->bindParam(':books', $authorData['books'], PDO::PARAM_INT);
        $stmt->execute();

        return DatabaseConnection::getInstance()->getConnection()->lastInsertId();
    }

    /**
     * Updates an existing author in the database.
     *
     * @param array $authorData Associative array containing author data (['id' => ..., 'name' => ..., 'books' => ...]).
     * @return void
     */
    public function update(array $authorData): void
    {
        $stmt = DatabaseConnection::getInstance()->getConnection()->prepare("UPDATE {$this->tableName} SET name = :name, books = :books WHERE id = :id");
        $stmt->bindParam(':id', $authorData['id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $authorData['name'], PDO::PARAM_STR);
        $stmt->bindParam(':books', $authorData['books'], PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Deletes an author from the database by their ID.
     *
     * @param int $id The ID of the author to delete.
     * @return void
     */
    public function delete(int $id): void
    {
        $stmt = DatabaseConnection::getInstance()->getConnection()->prepare("DELETE FROM {$this->tableName} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}