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
                    <a href="authorDelete.php?id=<?= $author['id'] ?>">🗑️</a>
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

</body>
</html>

