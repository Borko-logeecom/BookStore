<?php

namespace BookStore\Infrastructure\Persistence\Session;

class SessionAuthorRepository
{
    public function getAll(): array
    {
        return $_SESSION['authors'] ?? [];
    }

    public function getById(int $id): ?array
    {

        // Check if the authors array exists and is an array in the session
        if (!isset($_SESSION['authors']) || !is_array($_SESSION['authors'])) {
            return null;
        }

        // Iterate through all authors and look for the one with the matching ID
        foreach ($_SESSION['authors'] as $author) {
            if (isset($author['id']) && $author['id'] === $id) {
                return $author; // Return the author if found
            }
        }

        // If the loop finishes without finding an author, return null
        return null;

    }

    public function save(array $authorData): void
    {

        // Check if the authors array exists in the session, if not, initialize it
        if (!isset($_SESSION['authors']) || !is_array($_SESSION['authors'])) {
            $_SESSION['authors'] = [];
        }

        // If the author data has an ID, we are updating an existing author
        if (isset($authorData['id'])) {
            $authorId = $authorData['id'];
            $authorUpdated = false;
            foreach ($_SESSION['authors'] as $index => $author) {
                if (isset($author['id']) && $author['id'] === $authorId) {
                    $_SESSION['authors'][$index] = $authorData;
                    $authorUpdated = true;
                    break;
                }
            }
            // If the author ID was provided but not found, we might want to handle this (e.g., throw an error),
            // but for now, we'll just do nothing.
        } else {
            // If the author data does not have an ID, we are creating a new author
            // We'll generate a unique ID (for session purposes, using the current timestamp)
            $authorData['id'] = time();
            $_SESSION['authors'][] = $authorData;
        }

    }

    public function delete(int $id): void
    {

        // Check if the authors array exists and is an array in the session
        if (!isset($_SESSION['authors']) || !is_array($_SESSION['authors'])) {
            return; // Nothing to delete if the array doesn't exist
        }

        $authorIndexToDelete = -1;
        foreach ($_SESSION['authors'] as $index => $author) {
            if (isset($author['id']) && $author['id'] === $id) {
                $authorIndexToDelete = $index;
                break;
            }
        }

        // If the author was found, remove them from the array and re-index it
        if ($authorIndexToDelete !== -1) {
            unset($_SESSION['authors'][$authorIndexToDelete]);
            $_SESSION['authors'] = array_values($_SESSION['authors']);
        }
        // If the author was not found, nothing happens.

    }
}