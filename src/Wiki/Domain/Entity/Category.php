<?php


namespace App\Wiki\Domain\Entity;


use JsonSerializable;

/**
 * Class Category
 * @package App\Wiki\Domain\Entity
 */
class Category implements JsonSerializable
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
     * Category constructor.
     * @param int $id
     * @param int $idCompany
     * @param string $title
     */
    public function __construct(?int $id, int $idCompany, string $title)
    {
        $this->id = $id;
        $this->idCompany = $idCompany;
        $this->title = $title;
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
            'id' => $this->getId(),
            'idCompany' => $this->getIdCompany(),
            'title' => $this->getTitle(),
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


}