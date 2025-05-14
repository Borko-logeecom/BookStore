<?php

namespace BookStore\Application\BussinesLogic\Model\Author;

/**
 * Represents an author entity in the BookStore application.
 *
 * This class provides methods to get and set the author's ID, name, and the number of books they have authored.
 */
class Author
{
    private int $id;
    private string $name;
    private int $bookCount = 0;

    /**
     * Constructor to initialize the Author object.
     *
     * @param string $name The name of the author.
     * @param int $bookCount The number of books authored (default: 0).
     */
    public function __construct(string $name, int $bookCount = 0)
    {
        $this->name = $name;
        $this->bookCount = $bookCount;
    }

    /**
     * Gets the ID of the author.
     *
     * @return int The author's ID.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the ID of the author.
     *
     * @param int $id The author's ID.
     * @return void
     */
    public function setId(int $id): void{
        $this->id = $id;
    }


    /**
     * Gets the name of the author.
     *
     * @return string The author's name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the first name of the author.
     *
     * @return string The first name of the author.
     */
    public function getFirstName(): string
    {
        return explode(' ', $this->name)[0];
    }

    /**
     * Gets the last name of the author.
     *
     * @return string The last name of the author.
     */
    public function getLastName(): string
    {
        return explode(' ', $this->name)[1];
    }

    /**
     * Sets the name of the author.
     *
     * @param string $name The new name of the author.
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Gets the number of books associated with the author.
     *
     * @return int The number of books.
     */
    public function getBookCount(): int
    {
        return $this->bookCount;
    }

    /**
     * Sets the number of books associated with the author.
     *
     * @param int $bookCount The number of books.
     * @return void
     */
    public function setBookCount(int $bookCount): void
    {
        $this->bookCount = $bookCount;
    }

}