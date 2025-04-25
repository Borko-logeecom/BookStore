<?php

namespace BookStore\Application;

use BookStore\Infrastructure\Persistence\Session\SessionAuthorRepository;

class AuthorService
{
    private SessionAuthorRepository $authorRepository;

    public function __construct(SessionAuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function getAllAuthors(): array
    {
        return $this->authorRepository->getAll();
    }

    public function getAuthorById(int $id): ?array
    {
        return $this->authorRepository->getById($id);
    }

    public function createAuthor(string $firstName, string $lastName): ?array
    {
        $name = trim($firstName) . ' ' . trim($lastName);
        $authorData = ['name' => $name, 'books' => 0];
        $this->authorRepository->save($authorData);
        // After saving a new author without an ID, the repository generates one.
        // For simplicity, we'll fetch and return the newly created author.
        // This might not be the most efficient way in a real application.
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
        $name = trim($firstName) . ' ' . trim($lastName);
        $authorData = ['id' => $id, 'name' => $name, 'books' => $this->getAuthorById($id)['books'] ?? 0];
        $this->authorRepository->save($authorData);
    }

    public function deleteAuthor(int $id): void
    {
        $this->authorRepository->delete($id);
    }
}