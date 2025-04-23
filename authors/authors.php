<?php
$authors = [
    ['id' => 1, 'name' => 'Pera Peric', 'books' => 6],
    ['id' => 2, 'name' => 'Mika Mikic', 'books' => 2],
    ['id' => 3, 'name' => 'Zika Zikic', 'books' => 3],
    ['id' => 4, 'name' => 'Nikola Nikolic', 'books' => 0],
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Author List</title>
    <style>
        table {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        .actions a {
            margin: 0 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .create-btn {
            font-size: 30px;
            display: block;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<h2 style="text-align: center;">Author List</h2>

<table>
    <thead>
        <tr>
            <th>Author</th>
            <th>Books</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($authors as $author): ?>
            <tr>
                <td>
                    <a href="author_books.php?id=<?= $author['id'] ?>">
                        <?= htmlspecialchars($author['name']) ?>
                    </a>
                </td>
                <td><?= $author['books'] ?></td>
                <td class="actions">
                    <a href="author_edit.php?id=<?= $author['id'] ?>">✏️</a>
                    <a href="author_delete.php?id=<?= $author['id'] ?>">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a class="create-btn" href="author_create.php">➕</a>

</body>
</html>

