<?php

namespace BookStore\Application\BussinesLogic\Model\Book;

/**
 * Represents a book entity in the BookStore application.
 *
 * This class provides methods to get and set the book's ID, title, publication year, and associated author ID.
 */
class Book{

    private int $id;
    private string $tittle;
    private string $publishYear;
    private int $authorId;

    /**
     * Constructor to initialize the Book object.
     *
     * @param int $id The unique identifier of the book.
     * @param string $tittle The title of the book.
     * @param string $publishYear The publication year of the book.
     * @param int $authorId The ID of the author associated with this book.
     */
    public function __construct(int $id, string $tittle, string $publishYear, int $authorId)
    {
        $this->id = $id;
        $this->tittle = $tittle;
        $this->publishYear = $publishYear;
        $this->authorId = $authorId;
    }

    /**
     * Gets the ID of the book.
     *
     * @return int The book's ID.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the ID of the book.
     *
     * @param int $id The new ID of the book.
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets the title of the book.
     *
     * @return string The book's title.
     */
    public function getTittle(): string
    {
        return $this->tittle;
    }

    /**
     * Sets the title of the book.
     *
     * @param string $tittle The new title of the book.
     * @return void
     */
    public function setTittle(string $tittle): void
    {
        $this->tittle = $tittle;
    }

    /**
     * Gets the publication year of the book.
     *
     * @return string The book's publication year.
     */
    public function getPublishYear(): string
    {
        return $this->publishYear;
    }

    /**
     * Sets the publication year of the book.
     *
     * @param string $publishYear The new publication year of the book.
     * @return void
     */
    public function setPublishYear(string $publishYear): void
    {
        $this->publishYear = $publishYear;
    }

    /**
     * Gets the ID of the author associated with the book.
     *
     * @return int The ID of the author.
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * Sets the ID of the author associated with the book.
     *
     * @param int $authorId The new ID of the author.
     * @return void
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }
}