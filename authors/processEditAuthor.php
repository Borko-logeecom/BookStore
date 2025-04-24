<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Provera da li su ID, ime i prezime prisutni
    if (isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['firstName']) && isset($_POST['lastName'])) {
        $authorId = $_POST['id'];
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);

        // Osnovna validacija (isto kao kod kreiranja)
        if (empty($firstName)) {
            die("Greška: Ime je obavezno.");
        }
        if (strlen($firstName) > 100) {
            die("Greška: Ime ne sme biti duže od 100 karaktera.");
        }
        if (empty($lastName)) {
            die("Greška: Prezime je obavezno.");
        }
        if (strlen($lastName) > 100) {
            die("Greška: Prezime ne sme biti duže od 100 karaktera.");
        }

        // Ovde bi u stvarnoj aplikaciji išao kod za ažuriranje autora u bazi podataka
        // Koristeći $authorId, $firstName i $lastName

        // Za sada, samo simuliramo uspeh i preusmeravamo nazad na listu autora
        header("Location: authors.php?edit_success=true");
        exit();

    } else {
        die("Greška: Nedostaju podaci za uređivanje autora.");
    }
} else {
    // Ako se pristupi fajlu direktno (nije POST zahtev)
    header("Location: authors.php");
    exit();
}

?>