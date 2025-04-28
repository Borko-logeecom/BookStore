<?php

namespace BookStore\Controller;

include __DIR__ . "/../Application/AuthorService.php";
use BookStore\Application\AuthorService;

class AuthorController
{
    private AuthorService $authorService;

    /**
     * Constructor.
     * Initializes the AuthorController with an AuthorService instance and starts the session.
     */
    public function __construct()
    {
        session_start();
        $this->authorService = new AuthorService();
    }

    /**
     * Displays the list of authors.
     * Redirects to the authors.phtml page.
     *
     * @return void
     */
    public function index(): void
    {
        header("Location: ../../public/pages/authors.phtml");
        exit();
    }

    /**
     * Displays the form for creating a new author.
     *  Redirects to the authorCreate.phtml page.
     *
     * @return void
     */
    public function create(): void
    {
        header("Location: ../../public/pages/authorCreate.phtml");
        exit();
    }

    /**
     * Displays the form for editing an existing author.
     * Redirects to the authorEdit.phtml page, passing the author ID as a query parameter.
     *
     * @param int $id
     * @return void
     */
    public function edit(int $id): void
    {
        header("Location: ../../public/pages/authorEdit.phtml?id=" . $id);
        exit();
    }

    /**
     * Handles the submission of the author creation form.
     * Validates input and uses the AuthorService to create a new author.
     * Sets session messages for success or failure and redirects accordingly.
     *
     * @return void
     */
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

    /**
     * Handles the submission of the author editing form.
     * Validates input and uses the AuthorService to update an existing author.
     * Sets session messages for success or failure and redirects accordingly.
     *
     * @param int $id
     * @return void
     */
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

    /**
     * Handles the deletion of an author.
     * Uses the AuthorService to delete the author and sets a session message.
     * Redirects back to the list of authors.
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $this->authorService->deleteAuthor($id);
        $_SESSION['delete_message'] = "Author with ID " . $id . " has been successfully deleted.";
        header("Location: ../../public/pages/authors.phtml");
        exit();
    }
}