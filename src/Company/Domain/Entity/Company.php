<?php

declare(strict_types=1);


namespace App\Company\Domain\Entity;


use JsonSerializable;

class Company implements JsonSerializable
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $cnpj;

    /**
     * @var bool
     */
    private $mother;

    /**
     * @var bool
     */
    private $active;

    /**
     * Company constructor.
     * @param string $name
     * @param string $cnpj
     * @param string $description
     * @param bool $mother
     * @param bool $active
     */
    public function __construct(
        string $name,
        string $cnpj,
        string $description,
        bool $mother,
        bool $active
    )
    {
        $this->name = $name;
        $this->cnpj = $cnpj;
        $this->description = $description;
        $this->mother = $mother;
        $this->active = $active;
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
            'name' => $this->name(),
            'description' => $this->description(),
            'cnpj' => $this->cnpj(),
            'isMother' => $this->isMother(),
            'isActive' => $this->isActive()
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
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function cnpj(): string
    {
        return $this->cnpj;
    }

    /**
     * @return bool
     */
    public function isMother(): bool
    {
        return $this->mother;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
}