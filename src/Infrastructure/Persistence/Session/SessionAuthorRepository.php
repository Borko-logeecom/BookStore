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