<?php

declare(strict_types=1);

namespace BookStore\Application\Persistence\MySQL;

use BookStore\Application\BussinesLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Infrastructure\Database\DatabaseConnection;
use PDO;
use PDOException;
use RuntimeException;

// Using RuntimeException for database errors

/**
 * MySQL implementation of the BookRepositoryInterface.
 * Interacts with the 'books' table in the database.
 */
class MySQLBookRepository implements BookRepositoryInterface
{
    private DatabaseConnection $db;

    /**
     * Constructor.
     * Injects the database connection dependency.
     *
     * @param DatabaseConnection $db The database connection instance.
     */
    public function __construct(DatabaseConnection $db)
    {
        $this->db = $db;
    }

    /**
     * Finds all books belonging to a specific author.
     *
     * @param int $authorId The ID of the author.
     * @return array An array of book data arrays, or an empty array if no books are found.
     * Each book array includes keys 'id', 'author_id', 'title', 'publication_year'.
     * @throws RuntimeException If a database error occurs.
     */
    public function findByAuthorId(int $authorId): array
    {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("SELECT id, author_id, title, publication_year FROM books WHERE author_id = :author_id");
            $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // In a real application, you would log the error here.
            throw new RuntimeException("Database error fetching books for author ID " . $authorId . ": " . $e->getMessage());
        }
    }

    /**
     * Finds a single book by its ID.
     *
     * @param int $bookId The ID of the book.
     * @return array|null An associative array representing the book data, or null if the book is not found.
     * The book array includes keys 'id', 'author_id', 'title', 'publication_year'.
     * @throws RuntimeException If a database error occurs.
     */
    public function getById(int $bookId): ?array
    {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("SELECT id, author_id, title, publication_year FROM books WHERE id = :id");
            $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
            $stmt->execute();
            $book = $stmt->fetch(PDO::FETCH_ASSOC);
            return $book ?: null; // Return array or null
        } catch (PDOException $e) {
            // In a real application, you would log the error here.
            throw new RuntimeException("Database error fetching book with ID " . $bookId . ": " . $e->getMessage());
        }
    }


    /**
     * Creates a new book record in the data store.
     *
     * @param array $bookData An associative array containing book data (['author_id', 'title', 'publication_year']).
     * @return array|null The data of the newly created book (including its generated 'id'), or null on failure.
     * @throws RuntimeException If a database error occurs or required data is missing.
     */
    public function create(array $bookData): ?array
    {
        // Basic check for required data keys
        if (!isset($bookData['author_id'], $bookData['title'], $bookData['publication_year'])) {
            throw new RuntimeException("Missing required book data for creation.");
        }

        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("INSERT INTO books (author_id, title, publication_year) VALUES (:author_id, :title, :year)");

            $stmt->bindParam(':author_id', $bookData['author_id'], PDO::PARAM_INT);
            $stmt->bindParam(':title', $bookData['title'], PDO::PARAM_STR);
            $stmt->bindParam(':year', $bookData['publication_year'], PDO::PARAM_INT);

            $success = $stmt->execute();

            if ($success) {
                // Get the last inserted ID and fetch the created book data
                $lastInsertId = (int)$conn->lastInsertId();
                // Fetch the created book data to return the ID and all fields
                return $this->getById($lastInsertId); // Use getById to fetch the complete record
            } else {
                return null; // Indicate failure if execute did not return true
            }

        } catch (PDOException $e) {
            // In a real application, you would log the error here.
            throw new RuntimeException("Database error creating book: " . $e->getMessage());
        }
    }

    /**
     * Updates an existing book record in the data store by its ID.
     *
     * @param int $bookId The ID of the book to update.
     * @param array $bookData An associative array containing the updated book data (['title', 'publication_year']).
     * @return bool True on success, false on failure.
     * @throws RuntimeException If a database error occurs or required data is missing.
     */
    public function update(int $bookId, array $bookData): bool
    {
        // Basic check for required data keys
        if (!isset($bookData['title'], $bookData['publication_year'])) {
            // Note: author_id is usually not changed during book edit, but if it were, check for it too.
            throw new RuntimeException("Missing required book data for update.");
        }

        try {
            $conn = $this->db->getConnection();
            // Only update the fields that are expected to be updated (title, year)
            $stmt = $conn->prepare("UPDATE books SET title = :title, publication_year = :year WHERE id = :id");

            $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
            $stmt->bindParam(':title', $bookData['title'], PDO::PARAM_STR);
            $stmt->bindParam(':year', $bookData['publication_year'], PDO::PARAM_INT);

            $stmt->execute();

            // Check the number of affected rows to see if the update was successful
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            // In a real application, you would log the error here.
            throw new RuntimeException("Database error updating book with ID " . $bookId . ": " . $e->getMessage());
        }
    }

    /**
     * Deletes a book record from the data store by its ID.
     *
     * @param int $bookId The ID of the book to delete.
     * @return bool True on success, false on failure.
     * @throws RuntimeException If a database error occurs.
     */
    public function delete(int $bookId): bool
    {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("DELETE FROM books WHERE id = :id");
            $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);
            $stmt->execute();

            // Check the number of affected rows to see if a row was actually deleted
            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            // In a real application, you would log the error here.
            throw new RuntimeException("Database error deleting book with ID " . $bookId . ": " . $e->getMessage());
        }
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
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("DELETE FROM books WHERE author_id = :author_id");
            $stmt->bindParam(':author_id', $authorId, PDO::PARAM_INT);
            $stmt->execute();

            // Return true even if no rows were affected (no books to delete),
            // as the operation itself was successful.
            return true; // $stmt->rowCount() > 0; // Use this if you only want true when something was actually deleted

        } catch (PDOException $e) {
            // In a real application, you would log the error here.
            throw new RuntimeException("Database error deleting all books for author ID " . $authorId . ": " . $e->getMessage());
        }
    }
}