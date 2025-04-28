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
    // If not POST, redirect back to the author list
    header("Location: ../public/pages/authors.phtml");
    exit();
}

// Check if ID, first name, and last name are present
if (!isset($_POST['id']) || !is_numeric($_POST['id']) || !isset($_POST['firstName']) || !isset($_POST['lastName'])) {
    $_SESSION['edit_error'] = "Error: Missing author data for editing.";
    header("Location: ../public/pages/authors.phtml");
    exit();
}

$authorIdToEdit = (int)$_POST['id'];
$firstName = trim($_POST['firstName']);
$lastName = trim($_POST['lastName']);

// Basic validation (ideally this should be in the AuthorService)
if (empty($firstName)) {
    $_SESSION['edit_error'] = "Error: First name is required.";
    header("Location: ../public/pages/authorEdit.phtml?id=" . $authorIdToEdit);
    exit();
}
if (strlen($firstName) > 100) {
    $_SESSION['edit_error'] = "Error: First name cannot be longer than 100 characters.";
    header("Location: ../public/pages/authorEdit.phtml?id=" . $authorIdToEdit);
    exit();
}
if (empty($lastName)) {
    $_SESSION['edit_error'] = "Error: Last name is required.";
    header("Location: ../public/pages/authorEdit.phtml?id=" . $authorIdToEdit);
    exit();
}
if (strlen($lastName) > 100) {
    $_SESSION['edit_error'] = "Error: Last name cannot be longer than 100 characters.";
    header("Location: ../public/pages/authorEdit.phtml?id=" . $authorIdToEdit);
    exit();
}

// Update the author using the service
$authorService->updateAuthor($authorIdToEdit, $firstName, $lastName);

$_SESSION['edit_success'] = "Author with ID " . $authorIdToEdit . " has been successfully updated.";
header("Location: ../public/pages/authors.phtml");
exit();

