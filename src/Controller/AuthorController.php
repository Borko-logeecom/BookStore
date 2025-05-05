<?php

namespace BookStore\Controller;

use BookStore\Application\AuthorService;

/**
 * Controller class for handling author-related user requests.
 */
class AuthorController
{
    private AuthorService $authorService;

    /**
     * Constructor.
     * Initializes the AuthorController with an AuthorService instance.
     *
     * @param AuthorService $authorService The AuthorService dependency.
     */
    public function __construct(AuthorService $authorService)
    {
        session_start();
        $this->authorService = $authorService;
    }

    /**
     * Displays the list of authors.
     * Fetches authors from the service and redirects to the authors list page.
     *
     * @return void
     */
    public function index(): void
    {
        $authors = $this->authorService->getAllAuthors();

        $_SESSION['authors'] = $authors;

        header("Location: ../../public/pages/authors.phtml");
        exit();
    }

    /**
     * Redirects to the form for creating a new author.
     *
     * @return void
     */
    public function create(): void
    {
        header("Location: ../../public/pages/authorCreate.phtml");
        exit();
    }

    /**
     * Redirects to the form for editing an existing author, identified by ID.
     *
     * @param int $id The ID of the author to edit.
     * @return void
     */
    public function edit(int $id): void
    {
        header("Location: ../../public/pages/authorEdit.phtml?id=" . $id);
        exit();
    }

    /**
     * Handles the submission of the author creation form.
     * Validates input, creates the author via the service, and redirects.
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
            header("Location: ../../public/index.php?action=index");
            exit();
        } else {
            $_SESSION['create_error'] = "Error: Invalid input for author creation.";
            header("Location: ../../public/pages/authorCreate.phtml");
            exit();
        }
    }

    /**
     * Handles the submission of the author editing form.
     * Updates the author via the service and redirects.
     *
     * @param int $id The ID of the author being processed for edit.
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
        header("Location: ../../public/index.php?action=index");
        exit();
    }

    /**
     * Handles the deletion of an author.
     * Deletes the author via the service and redirects.
     *
     * @param int $id The ID of the author to delete.
     * @return void
     */
    public function delete(int $id): void
    {
        $this->authorService->deleteAuthor($id);
        $_SESSION['delete_message'] = "Author with ID " . $id . " has been successfully deleted.";
        header("Location: ../../public/index.php?action=index");
        exit();
    }
}