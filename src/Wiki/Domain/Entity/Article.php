<?php


namespace App\Wiki\Domain\Entity;


use App\Company\Domain\Entity\Company;
use JsonSerializable;

class Article implements JsonSerializable
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var integer
     */
    private $requestNumber;

    /**
     * @var Company
     */
    private $company;

    /**
     * Article constructor.
     * @param int $id
     * @param string $title
     * @param string $description
     * @param int $requestNumber
     * @param Company $company
     */
    public function __construct(int $id, string $title, string $description, int $requestNumber, Company $company)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->requestNumber = $requestNumber;
        $this->company = $company;
    }


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
            'title' => $this->title(),
            'description' => $this->description(),
            'requestNumber' => $this->requestNumber()
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
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function title(): string
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
    public function description(): string
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
     * @return int
     */
    public function requestNumber(): int
    {
        return $this->requestNumber;
    }

    /**
     * @param int $requestNumber
     */
    public function setRequestNumber(int $requestNumber): void
    {
        $this->requestNumber = $requestNumber;
    }

    /**
     * @return Company
     */
    public function company(): Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }


}