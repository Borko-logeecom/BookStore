<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if ID, first name, and last name are present
    if (isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['firstName']) && isset($_POST['lastName'])) {
        $authorIdToEdit = $_POST['id'];
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $fullName = $firstName . ' ' . $lastName;

        // Basic validation (same as during creation)
        if (empty($firstName)) {
            $_SESSION['edit_error'] = "Error: First name is required.";
            header("Location: authorEdit.php?id=" . $authorIdToEdit);
            exit();
        }
        if (strlen($firstName) > 100) {
            $_SESSION['edit_error'] = "Error: First name cannot be longer than 100 characters.";
            header("Location: authorEdit.php?id=" . $authorIdToEdit);
            exit();
        }
        if (empty($lastName)) {
            $_SESSION['edit_error'] = "Error: Last name is required.";
            header("Location: authorEdit.php?id=" . $authorIdToEdit);
            exit();
        }
        if (strlen($lastName) > 100) {
            $_SESSION['edit_error'] = "Error: Last name cannot be longer than 100 characters.";
            header("Location: authorEdit.php?id=" . $authorIdToEdit);
            exit();
        }

        // Check if the authors array exists in the session
        if (isset($_SESSION['authors']) && is_array($_SESSION['authors'])) {
            $authors = &$_SESSION['authors'];
            $authorUpdated = false;

            // Loop through the authors array to find the one with the matching ID and update it
            foreach ($authors as $index => $author) {
                if (isset($author['id']) && $author['id'] == $authorIdToEdit) {
                    $authors[$index]['name'] = $fullName;
                    // If you want to store first and last names separately in the session, do it here:
                    // $authors[$index]['firstName'] = $firstName;
                    // $authors[$index]['lastName'] = $lastName;
                    $authorUpdated = true;
                    break; // Stop the loop when the author is found and updated
                }
            }

            if ($authorUpdated) {
                $_SESSION['edit_success'] = "Author with ID " . $authorIdToEdit . " has been successfully updated.";
            } else {
                $_SESSION['edit_error'] = "Error: Author with ID " . $authorIdToEdit . " was not found for editing.";
            }
        } else {
            $_SESSION['edit_error'] = "Error: There was a problem with the author list.";
        }

        // Redirect back to the author list
        header("Location: authors.php");
        exit();

    } else {
        $_SESSION['edit_error'] = "Error: Missing author data for editing.";
        header("Location: authors.php");
        exit();
    }
} else {
    // If the file is accessed directly (not a POST request)
    header("Location: authors.php");
    exit();
}

?>