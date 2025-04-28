<?php


// Učitavanje kontrolera
require_once __DIR__ . '/../src/Controller/AuthorController.php';

use BookStore\Controller\AuthorController;

$action = $_GET['action'] ?? $_POST['action'] ?? 'index'; // Default akcija je index (lista autora)

$controller = new AuthorController();

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
            // Обработка грешке за недостајући или невалидан ИД
            echo "Error: Invalid or missing author ID for editing.";
            exit();
        }
        break;
    case 'processEdit':
        $id = $_POST['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $controller->processEdit((int)$id);
        } else {
            // Обработка грешке за недостајући или невалидан ИД
            echo "Error: Invalid or missing author ID for processing edit.";
            exit();
        }
        break;
    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id !== null && is_numeric($id)) {
            $controller->delete((int)$id);
        } else {
            // Обработка грешке за недостајући или невалидан ИД
            echo "Error: Invalid or missing author ID for deletion.";
            exit();
        }
        break;
    case 'index':
    default:
        $controller->index();
        break;
}