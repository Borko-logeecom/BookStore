<?php

use BookStore\Application\Presentation\Controller\AuthorController;
use BookStore\Infrastructure\Container\ServiceFactory;
use BookStore\Infrastructure\Container\ServiceRegistry;
use BookStore\Infrastructure\Response\HtmlResponse;
use BookStore\Infrastructure\Response\Response;

/**
 * Main application entry point (Front Controller).
 * Handles incoming requests and routes them to the appropriate controller action.
 */

require_once __DIR__ . '/../vendor/autoload.php';

try {



ServiceFactory::init();

// Get the AuthorController from the ServiceRegistry
/** @var AuthorController $authorController */
$authorController = ServiceRegistry::get(AuthorController::class);


// Determine the requested action from GET or POST parameters
$action = $_GET['action'] ?? $_POST['action'] ?? 'index'; // Default action is index (author list)

$response = null;

// Route the request to the appropriate controller method based on the action
switch ($action) {
    case 'create':
        $response = $authorController->create();
        break;
    case 'processCreate':
        $response = $authorController->processCreate();
        break;
    case 'edit':
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $response = $authorController->edit((int)$id);
        } else {
            $errorHtml = "<h1>Error</h1><p>Invalid or missing author ID for editing.</p><p><a href=\"/public/\">Go back to the author list</a></p>";
            $response = new HtmlResponse($errorHtml, 400);
        }
        break;
    case 'processEdit':
        $id = $_POST['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $response = $authorController->processEdit((int)$id);
        } else {
            $errorHtml = "<h1>Error</h1><p>Invalid or missing author ID for processing edit.</p><p><a href=\"/public/\">Go back to the author list</a></p>";
            $response = new HtmlResponse($errorHtml, 400);
        }
        break;
    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $response = $authorController->delete((int)$id);
        } else {
            $errorHtml = "<h1>Error</h1><p>Invalid or missing author ID for deletion.</p><p><a href=\"/public/\">Go back to the author list</a></p>";
            $response = new HtmlResponse($errorHtml, 400);
        }
        break;
    case 'index':
    default:
        $response = $authorController->index();
        break;
}

if ($response instanceof Response) {
    $response->send();
} else {
    http_response_code(500);
    echo "Application Error: Controller did not return a valid Response object.";
}

}catch (\Throwable $exception){
    $u = 1;
}
