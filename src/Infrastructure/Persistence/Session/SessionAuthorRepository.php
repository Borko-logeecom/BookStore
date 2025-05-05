<?php

namespace BookStore\Infrastructure\Persistence\Session;

/**
 * Repository class for interacting with author data stored in PHP sessions.
 * NOTE: This repository is intended for legacy data handling and is being replaced by MySQL.
 */
class SessionAuthorRepository
{
    /**
     * Retrieves all authors from the session.
     *
     * @return array A list of all authors stored in the session.
     */
    public function getAll(): array
    {
        return $_SESSION['authors'] ?? [];
    }

    /**
     * Retrieves a specific author from the session by their ID.
     *
     * @param int $id The ID of the author to retrieve.
     * @return array|null The author data as an associative array, or null if not found.
     */
    public function getById(int $id): ?array
    {
        if (!isset($_SESSION['authors']) || !is_array($_SESSION['authors'])) {
            return null;
        }
        foreach ($_SESSION['authors'] as $author) {
            if (isset($author['id']) && $author['id'] === $id) {
                return $author;
            }
        }
        return null;
    }

    /**
     * Saves author data to the session. If the author data contains an 'id', it updates
     * the existing author. Otherwise, it creates a new author with an auto-incrementing ID.
     *
     * @param array $authorData Associative array containing author data (['id' => ..., 'name' => ..., 'books' => ...] or ['name' => ..., 'books' => ...]).
     * @return void
     */
    public function save(array $authorData): void
    {
        if (!isset($_SESSION['author_id_counter'])) {
            $_SESSION['author_id_counter'] = 0;
        }
        if (isset($authorData['id'])) {
            $authorId = $authorData['id'];
            foreach ($_SESSION['authors'] as $index => $author) {
                if (isset($author['id']) && $author['id'] === $authorId) {
                    $_SESSION['authors'][$index] = $authorData;
                    break;
                }
            }
        } else {
            $_SESSION['author_id_counter']++;
            $authorData['id'] = $_SESSION['author_id_counter'];
            $_SESSION['authors'][] = $authorData;
        }
    }

    /**
     * Deletes an author from the session by their ID.
     *
     * @param int $id The ID of the author to delete.
     * @return void
     */
    public function delete(int $id): void
    {
        if (!isset($_SESSION['authors']) || !is_array($_SESSION['authors'])) {
            return;
        }
        $authorIndexToDelete = -1;
        foreach ($_SESSION['authors'] as $index => $author) {
            if (isset($author['id']) && $author['id'] === $id) {
                $authorIndexToDelete = $index;
                break;
            }
        }
        if ($authorIndexToDelete !== -1) {
            unset($_SESSION['authors'][$authorIndexToDelete]);
            $_SESSION['authors'] = array_values($_SESSION['authors']);
        }
    }
}