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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // If not POST, redirect back to the author creation form
    header("Location: authorCreate.php");
    exit();
}

// Check if first name and last name are set in the POST data
if (!isset($_POST['firstName']) || !isset($_POST['lastName'])) {
    $_SESSION['create_error'] = "Error: First name and last name are required.";
    header("Location: authorCreate.php");
    exit();
}

$firstName = trim($_POST['firstName']);
$lastName = trim($_POST['lastName']);

// Basic validation (ideally this should be in the AuthorService)
if (empty($firstName)) {
    $_SESSION['create_error'] = "Error: First name is required.";
    header("Location: authorCreate.php");
    exit();
}
if (strlen($firstName) > 100) {
    $_SESSION['create_error'] = "Error: First name cannot be longer than 100 characters.";
    header("Location: authorCreate.php");
    exit();
}
if (empty($lastName)) {
    $_SESSION['create_error'] = "Error: Last name is required.";
    header("Location: authorCreate.php");
    exit();
}
if (strlen($lastName) > 100) {
    $_SESSION['create_error'] = "Error: Last name cannot be longer than 100 characters.";
    header("Location: authorCreate.php");
    exit();
}

// Create the author using the service
$newAuthor = $authorService->createAuthor($firstName, $lastName);

if ($newAuthor) {
    $_SESSION['create_success'] = "Author " . $newAuthor['name'] . " has been successfully created.";
    header("Location: authors.php");
    exit();
} else {
    $_SESSION['create_error'] = "Error: Failed to create author.";
    header("Location: authorCreate.php");
    exit();
}
