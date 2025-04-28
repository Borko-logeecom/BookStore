<?php

/**
 * Front controller for the BookStore application.
 * Handles all incoming requests and dispatches them to the appropriate controller action.
 */

// Load the author controller
require_once __DIR__ . '/../src/Controller/AuthorController.php';

use BookStore\Controller\AuthorController;

// Determine the requested action from GET or POST parameters
$action = $_GET['action'] ?? $_POST['action'] ?? 'index'; // Default action is index (author list)

// Instantiate the AuthorController
$controller = new AuthorController();

// Route the request to the appropriate controller method based on the action
switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'processCreate':
        $controller->processCreate();
        break;
    case 'edit':
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $controller->edit((int)$id);
        } else {
            // Handle error for missing or invalid ID
            echo "Error: Invalid or missing author ID for editing.";
            exit();
        }
        break;
    case 'processEdit':
        $id = $_POST['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $controller->processEdit((int)$id);
        } else {
            // Handle error for missing or invalid ID
            echo "Error: Invalid or missing author ID for processing edit.";
            exit();
        }
        break;
    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $controller->delete((int)$id);
        } else {
            // Handle error for missing or invalid ID
            echo "Error: Invalid or missing author ID for deletion.";
            exit();
        }
        break;
    case 'index':
    default:
        $controller->index();
        break;
}