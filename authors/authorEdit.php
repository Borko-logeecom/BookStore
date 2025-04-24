<?php
// Povezivanje sa nizom autora (u stvarnoj aplikaciji ovo bi bila baza podataka)
$authors = [
    ['id' => 1, 'name' => 'Pera Peric'],
    ['id' => 2, 'name' => 'Mika Mikic'],
    ['id' => 3, 'name' => 'Zika Zikic'],
    ['id' => 4, 'name' => 'Nikola Nikolic'],
];

// Funkcija za pronalaženje autora po ID-u
function getAuthorById($authors, $id)
{
    foreach ($authors as $author) {
        if ($author['id'] == $id) {
            // Razdvajamo ime i prezime (pretpostavljamo da su spojeni u 'name')
            $nameParts = explode(' ', $author['name'], 2);
            return [
                'id' => $author['id'],
                'firstName' => $nameParts[0] ?? '',
                'lastName' => $nameParts[1] ?? ''
            ];
        }
    }
    return null;
}

// Provera da li je ID autora prosleđen
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Greška: Nedostaje validan ID autora.");
}

$authorId = $_GET['id'];
$author = getAuthorById($authors, $authorId);

// Ako autor ne postoji
if (!$author) {
    die("Greška: Autor sa ID-jem " . htmlspecialchars($authorId) . " ne postoji.");
}

$pageTitle = "Uredi Autora (" . htmlspecialchars($author['id']) . ")";
?>

<!DOCTYPE html>
<html lang = "en">
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
            text-align: center;
            margin-bottom: 20px;
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
    <h2><?= $pageTitle ?></h2>
    <form action="processEditAuthor.php" method="post" onsubmit="return validateForm()">
        <input type="hidden" name="id" value="<?= htmlspecialchars($author['id']) ?>">
        <div class="form-group">
            <label for="firstName">Ime:</label>
            <input type="text" id="firstName" name="firstName" maxlength="100" required
                   value="<?= htmlspecialchars($author['firstName']) ?>"
                   oninvalid="this.nextElementSibling.style.display='block'"
                   oninput="this.nextElementSibling.style.display='none'">
            <p class="error-message">* Ovo polje je obavezno</p>
        </div>
        <div class="form-group">
            <label for="lastName">Prezime:</label>
            <input type="text" id="lastName" name="lastName" maxlength="100" required
                   value="<?= htmlspecialchars($author['lastName']) ?>"
                   oninvalid="this.nextElementSibling.style.display='block'"
                   oninput="this.nextElementSibling.style.display='none'">
            <p class="error-message">* Ovo polje je obavezno</p>
        </div>
        <button type="submit">Sačuvaj</button>
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