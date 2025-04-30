<?php

namespace BookStore\Infrastructure\Persistence\MySQL;

use PDO;

class MySQLAuthorRepository
{
    private PDO $pdo;
    private string $tableName = 'authors';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->tableName} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM {$this->tableName}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(array $authorData): void
    {
        if (isset($authorData['id'])) {
            $stmt = $this->pdo->prepare("UPDATE {$this->tableName} SET name = :name, books = :books WHERE id = :id");
            $stmt->bindParam(':id', $authorData['id'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $authorData['name'], PDO::PARAM_STR);
            $stmt->bindParam(':books', $authorData['books'], PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO {$this->tableName} (name, books) VALUES (:name, :books)");
            $stmt->bindParam(':name', $authorData['name'], PDO::PARAM_STR);
            $stmt->bindParam(':books', $authorData['books'], PDO::PARAM_INT);
            $stmt->execute();
            // Optionally, you might want to fetch the last inserted ID
            // $authorData['id'] = $this->pdo->lastInsertId();
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->tableName} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}