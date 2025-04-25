<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

    if (!empty($firstName) && !empty($lastName)) {
        $name = trim($firstName) . ' ' . trim($lastName);

        // Generating a unique ID (for session purposes, we can use timestamp)
        $id = time();

        $newAuthor = [
            'id' => $id,
            'name' => $name,
            'books' => 0 // Initial number of books is 0
        ];

        // Check if the authors array exists in the session
        if (!isset($_SESSION['authors'])) {
            $_SESSION['authors'] = [];
        }

        // Adding the new author to the array
        $_SESSION['authors'][] = $newAuthor;

        // Redirecting back to the author list
        header("Location: authors.php");
        exit();
    } else {
        // If first name or last name are not filled, you can redirect back to the form with an error message
        // For now, we will simply redirect back
        header("Location: authorCreate.php");
        exit();
    }
} else {
    // If the file is accessed directly (not a POST request), redirect to the author list
    header("Location: authors.php");
    exit();
}
?>