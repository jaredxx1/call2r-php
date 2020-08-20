<?php


namespace App\Wiki\Domain\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Exception;
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
     * @var ArrayCollection
     */
    private $categories;

    /**
     * Article constructor.
     * @param int|null $id
     * @param int $idCompany
     * @param string $title
     * @param string $description
     * @param ArrayCollection $categories
     */
    public function __construct(?int $id, int $idCompany, string $title, string $description, ArrayCollection $categories)
    {
        $this->id = $id;
        $this->idCompany = $idCompany;
        $this->title = $title;
        $this->description = $description;
        $this->categories = $categories;
    }


    /**
     * @return array|mixed
     * @throws Exception
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'idCompany' => $this->getIdCompany(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'categories' => $this->getCategories()->getValues()
        ];
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
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIdCompany(): int
    {
        return $this->idCompany;
    }

    /**
     * @param int $idCompany
     */
    public function setIdCompany(int $idCompany): void
    {
        $this->idCompany = $idCompany;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param ArrayCollection $categories
     */
    public function setCategories(ArrayCollection $categories): void
    {
        $this->categories = $categories;
    }


}