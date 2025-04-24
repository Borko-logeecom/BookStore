<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Provera da li su ID, ime i prezime prisutni
    if (isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['firstName']) && isset($_POST['lastName'])) {
        $authorIdToEdit = $_POST['id'];
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $fullName = $firstName . ' ' . $lastName;

        // Osnovna validacija (isto kao kod kreiranja)
        if (empty($firstName)) {
            $_SESSION['edit_error'] = "Greška: Ime je obavezno.";
            header("Location: authorEdit.php?id=" . $authorIdToEdit);
            exit();
        }
        if (strlen($firstName) > 100) {
            $_SESSION['edit_error'] = "Greška: Ime ne sme biti duže od 100 karaktera.";
            header("Location: authorEdit.php?id=" . $authorIdToEdit);
            exit();
        }
        if (empty($lastName)) {
            $_SESSION['edit_error'] = "Greška: Prezime je obavezno.";
            header("Location: authorEdit.php?id=" . $authorIdToEdit);
            exit();
        }
        if (strlen($lastName) > 100) {
            $_SESSION['edit_error'] = "Greška: Prezime ne sme biti duže od 100 karaktera.";
            header("Location: authorEdit.php?id=" . $authorIdToEdit);
            exit();
        }

        // Provera da li postoji niz autora u sesiji
        if (isset($_SESSION['authors']) && is_array($_SESSION['authors'])) {
            $authors = &$_SESSION['authors'];
            $authorUpdated = false;

            // Prolazimo kroz niz autora da pronađemo onog sa odgovarajućim ID-jem i ažuriramo ga
            foreach ($authors as $index => $author) {
                if (isset($author['id']) && $author['id'] == $authorIdToEdit) {
                    $authors[$index]['name'] = $fullName;
                    // Ako želiš da čuvaš i odvojeno ime i prezime u sesiji, uradi to ovde:
                    // $authors[$index]['firstName'] = $firstName;
                    // $authors[$index]['lastName'] = $lastName;
                    $authorUpdated = true;
                    break; // Prekidamo petlju kada pronađemo i ažuriramo autora
                }
            }

            if ($authorUpdated) {
                $_SESSION['edit_success'] = "Autor sa ID-jem " . $authorIdToEdit . " je uspešno ažuriran.";
            } else {
                $_SESSION['edit_error'] = "Greška: Autor sa ID-jem " . $authorIdToEdit . " nije pronađen za uređivanje.";
            }
        } else {
            $_SESSION['edit_error'] = "Greška: Došlo je do problema sa listom autora.";
        }

        // Preusmeravanje nazad na listu autora
        header("Location: authors.php");
        exit();

    } else {
        $_SESSION['edit_error'] = "Greška: Nedostaju podaci za uređivanje autora.";
        header("Location: authors.php");
        exit();
    }
} else {
    // Ako se pristupi fajlu direktno (nije POST zahtev)
    header("Location: authors.php");
    exit();
}

?>