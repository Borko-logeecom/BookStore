<?php

declare(strict_types=1);

namespace BookStore\Application\Presentation\Controller;

use BookStore\Application\BussinesLogic\Model\Author\Author;
use BookStore\Application\BussinesLogic\Model\Book\Book;
use BookStore\Application\BussinesLogic\ServiceInterfaces\BookServiceInterface;
use BookStore\Infrastructure\Response\HtmlResponse;
use BookStore\Infrastructure\Response\JsonResponse;
use BookStore\Infrastructure\Response\RedirectResponse;
use BookStore\Infrastructure\Response\Response;

/**
 * Controller class for handling API requests related to Books.
 *
 * This controller provides methods for retrieving, creating, and deleting books via JSON API responses.
 * It receives dependencies via constructor injection from the Service Factory.
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
     * Retrieves all books for a specific author by their ID.
     *
     * This method is intended for AJAX requests, returning a JSON response.
     *
     * @param int $authorID The ID of the author whose books are being requested.
     * @return JsonResponse The JSON response containing an array of books.
     */
    public function getBooksByAuthor(int $authorID): JsonResponse
    {
        $books = $this->bookService->getBooksByAuthorId($authorID);

        return new JsonResponse($books);
    }

    /**
     * Creates a new book using JSON data from the request body.
     *
     * This method expects a JSON payload containing the book data, including:
     * - 'title': The title of the book.
     * - 'publish_year': The publication year of the book.
     * - 'author_id': The ID of the author associated with the book.
     *
     * @return JsonResponse The JSON response containing the newly created book data.
     */
    public function createBook() :JsonResponse
    {
        $book = json_decode(file_get_contents('php://input'), true);
        $newBook = $this->bookService->createBook($book);

        return new JsonResponse($newBook);
    }

    /**
     * Deletes a book by its ID.
     *
     * This method is intended for AJAX requests, returning a JSON response indicating success or failure.
     *
     * @param int $bookID The ID of the book to delete.
     * @return JsonResponse The JSON response indicating the success of the deletion.
     */
    public function deleteBook(int $bookID): JsonResponse
    {
        return new JsonResponse($this->bookService->deleteBook($bookID));
    }


}