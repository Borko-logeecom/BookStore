<?php

require_once __DIR__ . '/../src/Infrastructure/Container/ServiceFactory.php';

use BookStore\Infrastructure\Container\ServiceFactory;
use BookStore\Infrastructure\Container\ServiceRegistry;
use BookStore\Infrastructure\Response\HtmlResponse;
use BookStore\Infrastructure\Response\Response;

$authorRepoType = ServiceFactory::getAuthorRepositoryType();
$bookRepoType = ServiceFactory::getBookRepositoryType();

if ($authorRepoType === 'session' || $bookRepoType === 'session') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Main application entry point (Front Controller).
 * Handles incoming requests and routes them to the appropriate controller action.
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Get the AuthorController from the ServiceRegistry
$controller = ServiceRegistry::get('AuthorController');

// Determine the requested action from GET or POST parameters
$action = $_GET['action'] ?? $_POST['action'] ?? 'index'; // Default action is index (author list)

$response = null;

// Route the request to the appropriate controller method based on the action
switch ($action) {
    case 'create':
        $response = $controller->create();
        break;
    case 'processCreate':
        $response = $controller->processCreate();
        break;
    case 'edit':
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $response = $controller->edit((int)$id);
        } else {
            $errorHtml = "<h1>Error</h1><p>Invalid or missing author ID for editing.</p><p><a href=\"/public/\">Go back to the author list</a></p>";
            $response = new HtmlResponse($errorHtml, 400);
        }
        break;
    case 'processEdit':
        $id = $_POST['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $response = $controller->processEdit((int)$id);
        } else {
            $errorHtml = "<h1>Error</h1><p>Invalid or missing author ID for processing edit.</p><p><a href=\"/public/\">Go back to the author list</a></p>";
            $response = new HtmlResponse($errorHtml, 400);
        }
        break;
    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $response = $controller->delete((int)$id);
        } else {
            $errorHtml = "<h1>Error</h1><p>Invalid or missing author ID for deletion.</p><p><a href=\"/public/\">Go back to the author list</a></p>";
            $response = new HtmlResponse($errorHtml, 400);
        }
        break;
    case 'index':
    default:
        $response = $controller->index();
        break;
}

if ($response instanceof Response) {
    $response->send();
} else {
    http_response_code(500);
    echo "Application Error: Controller did not return a valid Response object.";
}
