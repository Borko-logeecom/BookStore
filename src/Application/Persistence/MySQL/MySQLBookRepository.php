<?php

declare(strict_types=1);

namespace BookStore\Application\Persistence\MySQL;

use BookStore\Application\BussinesLogic\Model\Author\Author;
use BookStore\Application\BussinesLogic\Model\Book\Book;
use BookStore\Application\BussinesLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Infrastructure\Database\DatabaseConnection;
use PDO;
use PDOException;
use RuntimeException;

/**
 * MySQL implementation of the BookRepositoryInterface.
 *
 * This class provides methods to interact with the 'books' table in the database,
 * including creating, reading, and deleting book records.
 */
class MySQLBookRepository implements BookRepositoryInterface
{
    private string $tableName = 'books';

    /**
     * Finds all books belonging to a specific author.
     *
     * @param Author $author The author whose books are being retrieved.
     * @return array An array of book data arrays, or an empty array if no books are found.
     * Each book array includes keys 'id', 'author_id', 'title', 'publication_year'.
     */
    public function findByAuthorId(Author $author): array
    {
        $stmt = DatabaseConnection::getInstance()->getConnection()
            ->prepare("SELECT * FROM {$this->tableName} WHERE author_id = :author_id");
        $id = $author->getId();
        $stmt->bindParam(':author_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Finds a single book by its ID.
     *
     * @param int $bookId The ID of the book.
     * @return array|null An associative array representing the book data, or null if not found.
     * @throws RuntimeException If a database error occurs.
     */
    public function getById(int $bookId): ?array
    {
        $stmt = DatabaseConnection::getInstance()->getConnection()
            ->prepare("SELECT * FROM {$this->tableName} WHERE id = :id");
        $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
        $stmt->execute();
        $book = $stmt->fetch(PDO::FETCH_ASSOC);

        return $book ?: null;
    }


    /**
     * Creates a new book record in the database.
     *
     * @param Book $book The book object containing the data to be saved.
     * @return int The ID of the newly created book.
     * @throws RuntimeException If a database error occurs.
     */
    public function create(Book $book): int
    {
        $stmt = DatabaseConnection::getInstance()->getConnection()
            ->prepare("INSERT INTO {$this->tableName} (title, publish_year, author_id) VALUES (:title, :year, :author_id)");

        $authorId = $book->getAuthorId();
        $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
        $tittle = $book->getTittle();
        $stmt->bindParam(':title', $tittle, PDO::PARAM_STR);
        $publishYear = $book->getPublishYear();
        $stmt->bindParam(':year', $publishYear, PDO::PARAM_INT);

        $stmt->execute();

        return (int)DatabaseConnection::getInstance()->getConnection()->lastInsertId();
    }

    /**
     * Deletes a book record from the database by its ID.
     *
     * @param int $bookId The ID of the book to delete.
     * @return bool True on success, false on failure.
     * @throws RuntimeException If a database error occurs.
     */
    public function delete(int $bookId): bool
    {
        $stmt = DatabaseConnection::getInstance()->getConnection()
            ->prepare("DELETE FROM {$this->tableName} WHERE id = :id");
        $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Deletes all book records belonging to a specific author.
     *
     * @param int $authorId The ID of the author whose books should be deleted.
     * @return bool True on success, false on failure.
     * @throws RuntimeException If a database error occurs.
     */
    public function deleteAllByAuthorId(int $authorId): bool
    {
        $stmt = DatabaseConnection::getInstance()->getConnection()
            ->prepare("DELETE FROM {$this->tableName} WHERE author_id = :author_id");
        $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}