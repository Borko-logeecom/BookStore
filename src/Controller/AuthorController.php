<?php

namespace BookStore\Controller;

include __DIR__ . "/../Application/AuthorService.php";
use BookStore\Application\AuthorService;

class AuthorController
{
    private AuthorService $authorService;

    public function __construct()
    {
        session_start();
        $this->authorService = new AuthorService();
    }

    public function index(): void
    {
        header("Location: ../../public/pages/authors.phtml");
        exit();
    }

    public function create(): void
    {
        header("Location: ../../public/pages/authorCreate.phtml");
        exit();
    }

    public function edit(int $id): void
    {
        header("Location: ../../public/pages/authorEdit.phtml?id=" . $id);
        exit();
    }

    public function processCreate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../../public/pages/authorCreate.phtml");
            exit();
        }

        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';

        $newAuthor = $this->authorService->createAuthor($firstName, $lastName);

        if ($newAuthor) {
            $_SESSION['create_success'] = "Author " . htmlspecialchars($newAuthor['name']) . " has been successfully created.";
            header("Location: ../../public/pages/authors.phtml");
            exit();
        } else {
            $_SESSION['create_error'] = "Error: Invalid input for author creation.";
            header("Location: ../../public/pages/authorCreate.phtml");
            exit();
        }
    }

    public function processEdit(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../../public/pages/authors.phtml");
            exit();
        }

        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';

        // We are assuming that if the service method is called, the basic validation passed in the form.
        // A more robust solution might involve the service returning a status or throwing an exception.
        $this->authorService->updateAuthor($id, $firstName, $lastName);
        $_SESSION['edit_success'] = "Author with ID " . $id . " has been successfully updated.";
        header("Location: ../../public/pages/authors.phtml");
        exit();
    }

    public function delete(int $id): void
    {
        $this->authorService->deleteAuthor($id);
        $_SESSION['delete_message'] = "Author with ID " . $id . " has been successfully deleted.";
        header("Location: ../../public/pages/authors.phtml");
        exit();
    }
}