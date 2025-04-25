<?php

use BookStore\Application\AuthorService;
use BookStore\Infrastructure\Persistence\Session\SessionAuthorRepository;

session_start();

// Include necessary files
require_once __DIR__ . '/../src/Application/AuthorService.php';
require_once __DIR__ . '/../src/Infrastructure/Persistence/Session/SessionAuthorRepository.php';

// Create instances of the repository and the service
$sessionAuthorRepository = new SessionAuthorRepository();
$authorService = new AuthorService($sessionAuthorRepository);

// Check if the author ID is passed via GET method
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $authorIdToDelete = (int)$_GET['id'];

    // Delete the author using the service
    $authorService->deleteAuthor($authorIdToDelete);

    $_SESSION['delete_message'] = "Author with ID " . $authorIdToDelete . " has been successfully deleted.";
} else {
    // If the ID was not passed or is invalid
    $_SESSION['delete_error'] = "Invalid author ID for deletion.";
}

// Redirect back to the author list
header("Location: authors.php");
exit();
