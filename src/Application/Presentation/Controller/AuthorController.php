<?php

namespace BookStore\Application\Presentation\Controller;

use BookStore\Application\BussinesLogic\ServiceInterfaces\AuthorServiceInterface;
use BookStore\Application\BussinesLogic\Services\AuthorService;
use BookStore\Infrastructure\Response\HtmlResponse;
use BookStore\Infrastructure\Response\RedirectResponse;
use BookStore\Infrastructure\Response\Response;

/**
 * Controller class for handling author-related user requests.
 *
 * This controller provides methods for displaying author lists, creating, editing, and deleting authors.
 * It uses an AuthorServiceInterface implementation for business logic.
 */
class AuthorController
{
    private AuthorServiceInterface $authorService;

    /**
     * Constructor.
     * Initializes the AuthorController with an AuthorService instance.
     *
     * @param AuthorServiceInterface $authorService The AuthorService dependency.
     */
    public function __construct(AuthorServiceInterface $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Displays the list of authors.
     *
     * Fetches authors from the service and returns an HtmlResponse.
     *
     * @return HtmlResponse The HTTP response containing the author list HTML.
     */
    public function index(): Response
    {
        $authors = $this->authorService->getAllAuthors();

        ob_start();

        include __DIR__ . '/../../../../public/pages/authors.phtml';

        $html = ob_get_clean();

        return new HtmlResponse($html);
    }

    /**
     * Displays the form for creating a new author.
     *
     * Returns an HtmlResponse containing the form HTML.
     *
     * @return HtmlResponse The HTTP response containing the author creation form HTML.
     */
    public function create(): HtmlResponse
    {
        ob_start();

        include __DIR__ . '/../../../../public/pages/authorCreate.phtml';

        $html = ob_get_clean();

        return new HtmlResponse($html);
    }

    /**
     * Displays the form for editing an existing author, identified by ID.
     *
     * @param int $id The ID of the author to edit.
     * @return HtmlResponse The HTTP response containing the author edit form HTML or an error message.
     */
    public function edit(int $id): HtmlResponse
    {
        $authorToEdit = $this->authorService->getAuthorById($id);

        if (!$authorToEdit) {
            $errorMessage = "Error: Author with ID " . htmlspecialchars((string)$id) . " not found.";
            $errorHtml = "<h1>Not Found</h1><p>" . $errorMessage . "</p><p><a href=\"/public/\">Go back to the author list</a></p>";

            return new HtmlResponse($errorHtml, 404);
        }
        $nameParts = explode(' ', $authorToEdit['name'] ?? '', 2);
        $authorToEdit['firstName'] = $nameParts[0] ?? '';
        $authorToEdit['lastName'] = $nameParts[1] ?? '';

        $pageTitle = "Author Edit";

        ob_start();

        include __DIR__ . '/../../../../public/pages/authorEdit.phtml';

        $html = ob_get_clean();

        return new HtmlResponse($html);
    }

    /**
     * Handles the submission of the author creation form.
     *
     * Validates input, creates the author via the service, and returns a RedirectResponse.
     *
     * @return RedirectResponse An HTTP redirect response.
     */
    public function processCreate(): RedirectResponse
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return new RedirectResponse('/index.php?action=create', 303);
        }

        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';

        $newAuthor = $this->authorService->createAuthor($firstName, $lastName);

        if ($newAuthor) {
            $message = urlencode("Author " . htmlspecialchars($newAuthor['name']) . " has been successfully created.");
            $redirectUrl = '/index.php?action=index';

            return new RedirectResponse($redirectUrl, 303);
        } else {
            $errorMessage = urlencode("Error: Invalid input for author creation. First/Last name might be empty or too long.");
            $redirectUrl = '/index.php?action=create&status=error&message=' . $errorMessage;

            return new RedirectResponse($redirectUrl, 303);
        }
    }

    /**
     * Handles the submission of the author editing form.
     *
     * Updates the author via the service and redirects to the author list.
     *
     * @param int $id The ID of the author being processed for edit.
     * @return RedirectResponse The HTTP redirect response.
     */
    public function processEdit(int $id): RedirectResponse
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return new RedirectResponse('/index.php?action=index', 303);
        }

        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';

        $this->authorService->updateAuthor($id, $firstName, $lastName);

        $message = urlencode("Author with ID " . htmlspecialchars((string)$id) . " has been successfully updated.");
        $redirectUrl = '/index.php?action=index';

        return new RedirectResponse($redirectUrl, 303);
    }

    /**
     * Handles the deletion of an author.
     *
     * Deletes the author via the service and returns a RedirectResponse.
     *
     * @param int $id The ID of the author to delete.
     * @return RedirectResponse An HTTP redirect response.
     */
    public function delete(int $id): RedirectResponse
    {
        $this->authorService->deleteAuthor($id);
        $message = urlencode("Author with ID " . htmlspecialchars((string)$id) . " has been successfully deleted.");
        $redirectUrl = '/index.php?action=index';

        return new RedirectResponse($redirectUrl, 303);
    }
}