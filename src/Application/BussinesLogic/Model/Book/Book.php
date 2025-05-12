<?php

namespace BookStore\Application\BussinesLogic\Model\Book;

class Book{

    private int $id;
    private string $tittle;
    private string $publishYear;
    private int $authorId;

    /**
     * @param int $id
     * @param string $tittle
     * @param string $publishYear
     * @param int $authorId
     */
    public function __construct(int $id, string $tittle, string $publishYear, int $authorId)
    {
        $this->id = $id;
        $this->tittle = $tittle;
        $this->publishYear = $publishYear;
        $this->authorId = $authorId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTittle(): string
    {
        return $this->tittle;
    }

    public function setTittle(string $tittle): void
    {
        $this->tittle = $tittle;
    }

    public function getPublishYear(): string
    {
        return $this->publishYear;
    }

    public function setPublishYear(string $publishYear): void
    {
        $this->publishYear = $publishYear;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }
}