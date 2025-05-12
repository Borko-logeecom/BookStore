<?php

namespace BookStore\Application\BussinesLogic\Model\Author;

class Author
{
    private int $id;
    private string $name;
    private int $bookCount = 0;

    public function __construct(string $name, int $bookCount = 0)
    {
        $this->name = $name;
        $this->bookCount = $bookCount;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void{
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Getter for first name
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return explode(' ', $this->name)[0];
    }

    /**
     * Getter for last name
     *
     * @return string
     */
    public function getLastName(): string
    {
        return explode(' ', $this->name)[1];
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getBookCount(): int
    {
        return $this->bookCount;
    }

    /**
     * @param int $bookCount
     * @return void
     */
    public function setBookCount(int $bookCount): void
    {
        $this->bookCount = $bookCount;
    }

}