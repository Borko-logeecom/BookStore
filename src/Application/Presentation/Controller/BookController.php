<?php

declare(strict_types=1);

namespace BookStore\Application\Presentation\Controller;

use BookStore\Application\BussinesLogic\ServiceInterfaces\BookServiceInterface;
use BookStore\Application\BussinesLogic\Services\BookService;
use BookStore\Infrastructure\Response\JsonResponse;
use InvalidArgumentException;
use RuntimeException;
use Throwable;


/**
 * Controller class for handling API requests related to Books.
 * Receives dependencies via constructor injection from the Service Factory.
 * Returns JSON responses for AJAX calls.
 */
class BookController
{
    private BookServiceInterface $bookService;

    /**
     * Constructor.
     * Injects the BookService dependency required by this controller.
     *
     * @param BookServiceInterface $bookService The BookService instance provided by the Factory.
     */
    public function __construct(BookServiceInterface $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Handles requests to get all books for a specific author.
     * Expected to be called via AJAX from the author's book page.
     * Expects author_id as a parameter passed by the router (index.php).
     *
     * @param int $authorId The ID of the author whose books to retrieve.
     * This parameter will be extracted from the request URL/parameters by the router
     * and passed as an argument to this method.
     * @return JsonResponse A JSON response containing the list of books or an error message.
     */
    public function getBooksByAuthorId(int $authorId): JsonResponse
    {
        if ($authorId <= 0) {
            return new JsonResponse(['error' => 'Invalid author ID provided.'], 400);
        }

        try {
            $books = $this->bookService->getBooksByAuthorId($authorId);

            return new JsonResponse($books, 200);

        } catch (InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (RuntimeException $e) {
            error_log("BookController Runtime Exception in getBooksByAuthorId: " . $e->getMessage()); // Example logging

            return new JsonResponse(['error' => 'An internal server error occurred while fetching books.'], 500);
        } catch (Throwable $e) {
            error_log("BookController Unexpected Exception in getBooksByAuthorId: " . $e->getMessage()); // Example logging

            return new JsonResponse(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}