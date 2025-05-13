<?php

require_once __DIR__ . '/src/Infrastructure/Container/ServiceFactory.php';

require_once __DIR__ . '/vendor/autoload.php';

use BookStore\Application\Presentation\Controller\AuthorController;
use BookStore\Application\Presentation\Controller\BookController;
use BookStore\Infrastructure\Container\ServiceFactory;
use BookStore\Infrastructure\Container\ServiceRegistry;
use BookStore\Infrastructure\Response\HtmlResponse;

$basePath = '';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$routePath = str_replace($basePath, '', parse_url($requestUri, PHP_URL_PATH));

try {

    ServiceFactory::init();

    if (!str_starts_with($routePath, '/api/')) {
        /** @var AuthorController $authorController */
        $authorController = ServiceRegistry::get(AuthorController::class);

        $action = $_GET['action'] ?? $_POST['action'] ?? 'index';

        //$action = explode('?', $action)[0];

        $response = null;

        switch ($action) {
            case 'create':
                $response = $authorController->create();
                break;
            case 'processCreate':
                $response = $authorController->processCreate();
                break;
            case 'edit':
                $id = $_GET['id'] ?? $_POST['id'] ?? null;
                if (is_numeric($id)) {
                    $response = $authorController->edit((int)$id);
                } else {
                    $errorHtml = ">Go back to the author list</a></p>";
                    $response = new HtmlResponse($errorHtml, 400);
                }
                break;
            case 'processEdit':
                $id = $_POST['id'] ?? null;
                if (is_numeric($id)) {
                    $response = $authorController->processEdit((int)$id);
                } else {
                    $errorHtml = ">Go back to the author list</a></p>";
                    $response = new HtmlResponse($errorHtml, 400);
                }
                break;
            case 'delete':
                $id = $_GET['id'] ?? null;
                if (is_numeric($id)) {
                    $response = $authorController->delete((int)$id);
                } else {
                    $errorHtml = ">Go back to the author list</a></p>";
                    $response = new HtmlResponse($errorHtml, 400);
                }
                break;

            case 'index':
            default:
                $response = $authorController->index();
                break;
        }
        $response->send();
        exit;
    }

    /** @var BookController $bookController */
    $bookController = ServiceRegistry::get(BookController::class);
    $response = null;
    // GET /api/books
    if ($requestMethod === 'GET' && $routePath === '/api/books')
    {
        $authorID = $_GET['authorId'] ?? null;
        $response = $bookController->getBooksByAuthor($authorID);
    }
    // POST /api/books/create
    elseif ($requestMethod === 'POST' && $routePath === '/api/books/create')
    {
        $response = $bookController->createBook();
    }
    // POST /api/books/{id}/edit
    elseif ($requestMethod === 'POST' && preg_match('/^\/api\/books\/(\d+)\/edit$/', $routePath, $matches))
    {
        $bookID = (int)$matches[1];
        $response = $bookController->editBook($bookID);
    }
    // POST /api/books/{id}/delete
    elseif ($requestMethod === 'POST' && preg_match('/^\/api\/books\/(\d+)\/delete$/', $routePath, $matches))
    {
        $bookID = (int)$matches[1];
        $response = $bookController->deleteBook($bookID);
    }else {

    }
    $response->send();

} catch (Throwable $exception) {
    $u = 1;
}