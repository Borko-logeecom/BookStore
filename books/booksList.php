<?php
$books = [
    ['id' => 1, 'title' => 'Book Name (2001)', 'year' => 2001],
    ['id' => 2, 'title' => 'Book Name 2 (2002)', 'year' => 2002],
    ['id' => 3, 'title' => 'Book Name 3 (1997)', 'year' => 1997],
    ['id' => 4, 'title' => 'Book Name 4 (2005)', 'year' => 2005],
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book List</title>
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

        th {
            background-color: #f2f2f2;
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

<h2 style="text-align: center;">Book List</h2>

<table>
    <thead>
    <tr>
        <th>Book</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($books as $book): ?>
        <tr>
            <td><?= htmlspecialchars($book['title']) ?></td>
            <td class="actions">
                <a href="book_edit.php?id=<?= $book['id'] ?>">✏️</a>
                <a href="book_delete.php?id=<?= $book['id'] ?>">🗑️</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a class="create-btn" href="book_create.php">➕</a>

</body>
</html>