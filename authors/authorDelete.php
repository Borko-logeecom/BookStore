<?php
session_start();

// Proveri da li je ID autora prosleđen putem GET metode
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $authorIdToDelete = $_GET['id'];

    // Proveri da li postoji niz autora u sesiji
    if (isset($_SESSION['authors']) && is_array($_SESSION['authors'])) {
        $authors = &$_SESSION['authors']; // Kreiramo referencu na niz autora u sesiji

        $authorIndexToDelete = -1; // Inicijalizujemo indeks na -1 (ne pronađen)

        // Prolazimo kroz niz autora da pronađemo onog sa odgovarajućim ID-jem
        foreach ($authors as $index => $author) {
            if (isset($author['id']) && $author['id'] == $authorIdToDelete) {
                $authorIndexToDelete = $index;
                break; // Prekidamo petlju kada pronađemo autora
            }
        }

        // Ako je autor pronađen, ukloni ga iz niza
        if ($authorIndexToDelete !== -1) {
            unset($authors[$authorIndexToDelete]);

            // Reindeksiraj niz kako bi ključevi bili sekvencijalni (opciono, ali može biti korisno)
            $authors = array_values($authors);

            // Postavi poruku o uspešnom brisanju (može se prikazati na authors.php kasnije)
            $_SESSION['delete_message'] = "Autor sa ID-jem " . $authorIdToDelete . " je uspešno obrisan.";
        } else {
            // Ako autor sa datim ID-jem nije pronađen
            $_SESSION['delete_error'] = "Autor sa ID-jem " . $authorIdToDelete . " ne postoji.";
        }
    } else {
        // Ako niz autora u sesiji ne postoji ili nije niz
        $_SESSION['delete_error'] = "Došlo je do greške pri brisanju autora.";
    }
} else {
    // Ako ID nije prosleđen ili nije validan
    $_SESSION['delete_error'] = "Nevažeći ID autora za brisanje.";
}

// Preusmeri nazad na listu autora
header("Location: authors.php");
exit();
?>