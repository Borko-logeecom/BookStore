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
        //$sessionAuthorRepository = new SessionAuthorRepository();
        $this->authorService = new AuthorService();
    }

    public function index(): void
    {
        // Prikazuje listu autora
        header("Location: ../../public/pages/authors.phtml"); // Za sada preusmeravamo direktno
        exit();
    }

    public function create(): void
    {
        // Prikazuje formu za kreiranje autora
        header("Location: ../../public/pages/authorCreate.phtml");
        exit();
    }

    public function edit(int $id): void
    {
        // Prikazuje formu za uređivanje autora
        header("Location: ../../public/pages/authorEdit.phtml?id=" . $id);
        exit();
    }

    public function processCreate(): void
    {
        // Obrada logike za kreiranje autora
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../../public/pages/authorCreate.phtml");
            exit();
        }

        if (!isset($_POST['firstName']) || !isset($_POST['lastName'])) {
            $_SESSION['create_error'] = "Error: First name and last name are required.";
            header("Location: ../../public/pages/authorCreate.phtml");
            exit();
        }

        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);

        // Basic validation (ideally this should be in the AuthorService)
        if (empty($firstName)) {
            $_SESSION['create_error'] = "Error: First name is required.";
            header("Location: ../../public/pages/authorCreate.phtml");
            exit();
        }
        if (strlen($firstName) > 100) {
            $_SESSION['create_error'] = "Error: First name cannot be longer than 100 characters.";
            header("Location: ../../public/pages/authorCreate.phtml");
            exit();
        }
        if (empty($lastName)) {
            $_SESSION['create_error'] = "Error: Last name is required.";
            header("Location: ../../public/pages/authorCreate.phtml");
            exit();
        }
        if (strlen($lastName) > 100) {
            $_SESSION['create_error'] = "Error: Last name cannot be longer than 100 characters.";
            header("Location: ../../public/pages/authorCreate.phtml");
            exit();
        }

        $newAuthor = $this->authorService->createAuthor($firstName, $lastName);

        if ($newAuthor) {
            $_SESSION['create_success'] = "Author " . htmlspecialchars($newAuthor['name']) . " has been successfully created.";
            header("Location: ../../public/pages/authors.phtml");
            exit();
        } else {
            $_SESSION['create_error'] = "Error: Failed to create author.";
            header("Location: ../../public/pages/authorCreate.phtml");
            exit();
        }
    }

    public function processEdit(int $id): void
    {
        // Obrada logike za uređivanje autora
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../../public/pages/authors.phtml");
            exit();
        }

        if (!isset($_POST['id']) || !is_numeric($_POST['id']) || !isset($_POST['firstName']) || !isset($_POST['lastName'])) {
            $_SESSION['edit_error'] = "Error: Missing author data for editing.";
            header("Location: ../../public/pages/authors.phtml");
            exit();
        }

        $authorIdToEdit = (int)$_POST['id'];
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);

        // Basic validation (ideally this should be in the AuthorService)
        if (empty($firstName)) {
            $_SESSION['edit_error'] = "Error: First name is required.";
            header("Location: ../../public/pages/authorEdit.phtml?id=" . $authorIdToEdit);
            exit();
        }
        if (strlen($firstName) > 100) {
            $_SESSION['edit_error'] = "Error: First name cannot be longer than 100 characters.";
            header("Location: ../../public/pages/authorEdit.phtml?id=" . $authorIdToEdit);
            exit();
        }
        if (empty($lastName)) {
            $_SESSION['edit_error'] = "Error: Last name is required.";
            header("Location: ../../public/pages/authorEdit.phtml?id=" . $authorIdToEdit);
            exit();
        }
        if (strlen($lastName) > 100) {
            $_SESSION['edit_error'] = "Error: Last name cannot be longer than 100 characters.";
            header("Location: ../../public/pages/authorEdit.phtml?id=" . $authorIdToEdit);
            exit();
        }

        $this->authorService->updateAuthor($authorIdToEdit, $firstName, $lastName);

        $_SESSION['edit_success'] = "Author with ID " . $authorIdToEdit . " has been successfully updated.";
        header("Location: ../../public/pages/authors.phtml");
        exit();
    }

    public function delete(int $id): void
    {
        // Obrada logike za brisanje autora
        $this->authorService->deleteAuthor($id);
        $_SESSION['delete_message'] = "Author with ID " . $id . " has been successfully deleted.";
        header("Location: ../../public/pages/authors.phtml");
        exit();
    }
}

// Potrebno je podesiti rutiranje u public/index.php da se ova klasa instancira
// i da se odgovarajuće metode pozivaju na osnovu akcije u URL-u.