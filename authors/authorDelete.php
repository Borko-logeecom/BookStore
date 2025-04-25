<?php
session_start();

// Check if the author ID is passed via GET method
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $authorIdToDelete = $_GET['id'];

    // Check if the authors array exists in the session
    if (isset($_SESSION['authors']) && is_array($_SESSION['authors'])) {
        $authors = &$_SESSION['authors']; // Create a reference to the authors array in the session

        $authorIndexToDelete = -1; // Initialize the index to -1 (not found)

        // Loop through the authors array to find the one with the matching ID
        foreach ($authors as $index => $author) {
            if (isset($author['id']) && $author['id'] == $authorIdToDelete) {
                $authorIndexToDelete = $index;
                break; // Stop the loop when the author is found
            }
        }

        // If the author is found, remove them from the array
        if ($authorIndexToDelete !== -1) {
            unset($authors[$authorIndexToDelete]);

            // Re-index the array so that the keys are sequential (optional, but can be useful)
            $authors = array_values($authors);

            // Set a message about the successful deletion (can be displayed on authors.php later)
            $_SESSION['delete_message'] = "Author with ID " . $authorIdToDelete . " has been successfully deleted.";
        } else {
            // If the author with the given ID was not found
            $_SESSION['delete_error'] = "Author with ID " . $authorIdToDelete . " does not exist.";
        }
    } else {
        // If the authors array in the session does not exist or is not an array
        $_SESSION['delete_error'] = "An error occurred while deleting the author.";
    }
} else {
    // If the ID was not passed or is invalid
    $_SESSION['delete_error'] = "Invalid author ID for deletion.";
}

// Redirect back to the author list
header("Location: authors.php");
exit();
?>