<?php
session_start();

// Provera da li je ID autora prosleđen
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Greška: Nedostaje validan ID autora.");
}

$authorId = $_GET['id'];
$authorToEdit = null;

// Provera da li postoji niz autora u sesiji
if (isset($_SESSION['authors']) && is_array($_SESSION['authors'])) {
    // Prolazimo kroz niz autora da pronađemo onog sa odgovarajućim ID-jem
    foreach ($_SESSION['authors'] as $author) {
        if (isset($author['id']) && $author['id'] == $authorId) {
            // Razdvajamo ime i prezime (pretpostavljamo da su spojeni u 'name')
            $nameParts = explode(' ', $author['name'], 2);
            $authorToEdit = [
                'id' => $author['id'],
                'firstName' => $nameParts[0] ?? '',
                'lastName' => $nameParts[1] ?? ''
            ];
            break; // Prekidamo petlju kada pronađemo autora
        }
    }
}

// Ako autor ne postoji u sesiji
if (!$authorToEdit) {
    die("Greška: Autor sa ID-jem " . htmlspecialchars($authorId) . " ne postoji.");
}

$pageTitle = "Author Edit (" . htmlspecialchars($authorToEdit['id']) . ")";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $pageTitle ?></title>
    <style>
        body {
            font-family: sans-serif;
        }

        .container {
            width: 80%;
            max-width: 500px;
            margin: 20px auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }

        h2 {
            text-align: left;
            border-bottom: 2px solid #ADD8E6;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
            display: none;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 style="text-align: left;"><?= $pageTitle ?></h2>
    <form action="processEditAuthor.php" method="post" onsubmit="return validateForm()">
        <input type="hidden" name="id" value="<?= htmlspecialchars($authorToEdit['id']) ?>">
        <div class="form-group">
            <label for="firstName">First name:</label>
            <input type="text" id="firstName" name="firstName" maxlength="100" required
                   value="<?= htmlspecialchars($authorToEdit['firstName']) ?>"
                   oninvalid="this.nextElementSibling.style.display='block'"
                   oninput="this.nextElementSibling.style.display='none'">
            <p class="error-message">* This field is required</p>
        </div>
        <div class="form-group">
            <label for="lastName">Last name:</label>
            <input type="text" id="lastName" name="lastName" maxlength="100" required
                   value="<?= htmlspecialchars($authorToEdit['lastName']) ?>"
                   oninvalid="this.nextElementSibling.style.display='block'"
                   oninput="this.nextElementSibling.style.display='none'">
            <p class="error-message">* This field is required</p>
        </div>
        <button type="submit">Save</button>
    </form>
</div>

<script>
    function validateForm() {
        let valid = true;
        const firstNameInput = document.getElementById('firstName');
        const lastNameInput = document.getElementById('lastName');
        const firstNameError = firstNameInput.nextElementSibling;
        const lastNameError = lastNameInput.nextElementSibling;

        if (!firstNameInput.value.trim()) {
            firstNameError.style.display = 'block';
            valid = false;
        } else {
            firstNameError.style.display = 'none';
        }

        if (!lastNameInput.value.trim()) {
            lastNameError.style.display = 'block';
            valid = false;
        } else {
            lastNameError.style.display = 'none';
        }

        return valid;
    }
</script>
</body>
</html>