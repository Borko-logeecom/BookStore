<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

    if (!empty($firstName) && !empty($lastName)) {
        $name = trim($firstName) . ' ' . trim($lastName);

        // Generisanje jedinstvenog ID-ja (za potrebe sesije, možemo koristiti timestamp)
        $id = time();

        $newAuthor = [
            'id' => $id,
            'name' => $name,
            'books' => 0 // Početni broj knjiga je 0
        ];

        // Provera da li postoji niz autora u sesiji
        if (!isset($_SESSION['authors'])) {
            $_SESSION['authors'] = [];
        }

        // Dodavanje novog autora u niz
        $_SESSION['authors'][] = $newAuthor;

        // Preusmeravanje nazad na listu autora
        header("Location: authors.php");
        exit();
    } else {
        // Ako ime ili prezime nisu popunjeni, možeš preusmeriti nazad na formu sa porukom o grešci
        // Za sada ćemo jednostavno preusmeriti nazad
        header("Location: authorCreate.php");
        exit();
    }
} else {
    // Ako se pristupa fajlu direktno (nije POST zahtev), preusmeri na listu autora
    header("Location: authors.php");
    exit();
}
?>