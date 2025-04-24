<?php

session_start();

// Provera da li postoji niz autora u sesiji
$authors = isset($_SESSION['authors']) ? $_SESSION['authors'] : [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Author List</title>
    <style>
        table {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        thead th {
            border-bottom: 1px solid #ccc;
        }

        .actions a {
            margin: 0 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .create-btn {
            font-size: 40px;
            display: inline-block;
            text-align: center;
            margin-top: -10px;
            margin-left: 20%;
            vertical-align: middle;
            width: 33px; /* Postavljamo širinu kruga */
            height: 33px; /* Postavljamo visinu kruga */
            line-height: 33px; /* Vertikalno centriramo plus */
            border-radius: 50%; /* Činimo ga krugom */
            background-color: white;
            color: #007bff;
            text-decoration: none;
            border: 2px solid #007bff;
        }

        .create-btn:hover {
            background-color: #0056b3; /* Tamnija plava na hover */
            cursor: pointer; /* Menjamo kursor u pointer */
        }


        .book-count {
            display: inline-block;
            width: 20px; /* Podesi veličinu kruga po potrebi */
            height: 20px; /* Podesi veličinu kruga po potrebi */
            line-height: 20px; /* Centrira tekst vertikalno */
            border-radius: 50%; /* Čini element krugom */
            background-color: #f0f0f0; /* Boja pozadine kruga */
            color: #333; /* Boja teksta (broja) */
            font-size: 0.8em; /* Veličina fonta broja */
            text-align: center;
            margin-left: 5px; /* Mali razmak od teksta autora */
        }

        tbody td a {
            color: black; /* Postavlja boju linka na plavu */
            text-decoration: none; /* Uklanja podvlačenje po defaultu */
            font-weight: bold;
        }

        tbody td a:hover {
            text-decoration: underline; /* Podvlači link kada se pređe mišem */
        }

    </style>
</head>

<body>

<h2 style="text-align: left ; margin-left: 20%;">Author List</h2>

<table>
    <thead>
    <tr>
        <th>Author</th>
        <th style="text-align: right;">Books</th>
        <th style="text-align: right;">Actions</th>
    </tr>
    </thead>

    <tbody>
    <?php if (!empty($authors)): ?>
        <?php foreach ($authors as $author): ?>
            <tr>
                <td>
                    <a href="author_books.php?id=<?= $author['id'] ?>">
                        👤 <?= htmlspecialchars($author['name']) ?>
                    </a>
                </td>
                <td style="text-align: right;">
                    <span class="book-count"><?= $author['books'] ?></span>
                </td>
                <td class="actions" style="text-align: right;">
                    <a href="authorEdit.php?id=<?= $author['id'] ?>">✏️</a>
                    <a href="#"
                       onclick="confirmDelete(<?= $author['id'] ?>, '<?= htmlspecialchars($author['name']) ?>')">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">Nema dodatih autora.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<a class="create-btn" href="authorCreate.php">+</a>

<div id="deleteConfirmationModal"
     style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); width: 400px;">
        <div style="display: flex; align-items: center; margin-bottom: 15px;">
            <span style="color: #dc3545; font-size: 1.5em; margin-right: 10px;">⚠️</span>
            <h3 style="margin: 0;">Delete Author</h3>
        </div>
        <p id="deleteConfirmationText">You are about to delete the author '<span id="authorToDeleteName"></span>'?
            If you proceed with this action, application will permanently delete all books related to this author.</p>
        <div style="display: flex; justify-content: flex-end;">
            <button style="background-color: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; margin-left: 10px;"
                    onclick="deleteAuthor()">Delete
            </button>
            <button style="background-color: #6c757d; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; margin-left: 10px;"
                    onclick="closeDeleteModal()">Cancel
            </button>

            <input type="hidden" id="authorIdToDelete">
        </div>
    </div>
</div>

<script>
    function confirmDelete(authorId, authorName) {
        document.getElementById('authorToDeleteName').textContent = authorName;
        document.getElementById('authorIdToDelete').value = authorId;
        document.getElementById('deleteConfirmationModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteConfirmationModal').style.display = 'none';
    }

    function deleteAuthor() {
        const authorId = document.getElementById('authorIdToDelete').value;
        window.location.href = 'authorDelete.php?id=' + authorId;
    }
</script>

</body>
</html>

