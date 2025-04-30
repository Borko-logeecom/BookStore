<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BookStore\Container\ServiceRegistry;

// Get the AuthorController from the ServiceRegistry
$controller = ServiceRegistry::get('AuthorController');


// Determine the requested action from GET or POST parameters
$action = $_GET['action'] ?? $_POST['action'] ?? 'index'; // Default action is index (author list)

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
            echo "Error: Invalid or missing author ID for editing.";
            exit();
        }
        break;
    case 'processEdit':
        $id = $_POST['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $controller->processEdit((int)$id);
        } else {
            echo "Error: Invalid or missing author ID for processing edit.";
            exit();
        }
        break;
    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $controller->delete((int)$id);
        } else {
            echo "Error: Invalid or missing author ID for deletion.";
            exit();
        }
        break;
    case 'index':
    default:
        $controller->index();
        break;
}