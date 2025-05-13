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

    public function getBooksByAuthor(int $authorID): JsonResponse
    {
        $books = $this->bookService->getBooksByAuthorId($authorID);

        return new JsonResponse($books);
    }

    public function createBook() :JsonResponse
    {
        $book = json_decode(file_get_contents('php://input'), true);
        $newBook = $this->bookService->createBook($book);

        return new JsonResponse($newBook);
    }

    public function deleteBook(int $bookID): JsonResponse
    {
        return new JsonResponse($this->bookService->deleteBook($bookID));
    }


}