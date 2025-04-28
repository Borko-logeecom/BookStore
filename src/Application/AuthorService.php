<?php

namespace BookStore\Application;

include __DIR__ . "/../Infrastructure/Persistence/Session/SessionAuthorRepository.php";
use BookStore\Infrastructure\Persistence\Session\SessionAuthorRepository;

class AuthorService
{
    private SessionAuthorRepository $authorRepository;

    public function __construct()
    {
        $this->authorRepository = new SessionAuthorRepository();
    }

    public function getAuthorById(int $id): ?array
    {
        return $this->authorRepository->getById($id);
    }

    public function createAuthor(string $firstName, string $lastName): ?array
    {
        $firstName = trim($firstName);
        $lastName = trim($lastName);

        if (empty($firstName) || strlen($firstName) > 100 || empty($lastName) || strlen($lastName) > 100) {
            return null; // Validation failed
        }

        $name = $firstName . ' ' . $lastName;
        $authorData = ['name' => $name, 'books' => 0];
        $this->authorRepository->save($authorData);
        return $this->getAuthorByName($name);
    }

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

    public function updateAuthor(int $id, string $firstName, string $lastName): void
    {
        $firstName = trim($firstName);
        $lastName = trim($lastName);

        if (empty($firstName) || strlen($firstName) > 100 || empty($lastName) || strlen($lastName) > 100) {
            // Ideally, we might want to signal a validation error differently here,
            // perhaps by returning false or throwing an exception, as the controller
            // for updating expects a void return type currently. For simplicity, we'll keep it void for now
            // but the validation is performed.
            return;
        }

        $name = $firstName . ' ' . $lastName;
        $authorData = ['id' => $id, 'name' => $name, 'books' => $this->getAuthorById($id)['books'] ?? 0];
        $this->authorRepository->save($authorData);
    }

    public function deleteAuthor(int $id): void
    {
        $this->authorRepository->delete($id);
    }
}