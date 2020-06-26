<?php


namespace App\Wiki\Domain\Entity;


use JsonSerializable;

/**
 * Class Article
 * @package App\Wiki\Domain\Entity
 */
class Article implements JsonSerializable
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $idCompany;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var array
     */
    private $categories;

    /**
     * Article constructor.
     * @param int $id
     * @param int $idCompany
     * @param string $title
     * @param string $description
     * @param array $categories
     */
    public function __construct(?int $id, int $idCompany, string $title, string $description, array $categories)
    {
        $this->id = $id;
        $this->idCompany = $idCompany;
        $this->title = $title;
        $this->description = $description;
        $this->categories = $categories;
    }


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'idCompany' => $this->idCompany(),
            'title' => $this->title(),
            'description' => $this->description(),
            'categories' => $this->categories()->getIterator()
        ];
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function idCompany(): int
    {
        return $this->idCompany;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param int $idCompany
     */
    public function setIdCompany(int $idCompany): void
    {
        $this->idCompany = $idCompany;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function categories()
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }


}