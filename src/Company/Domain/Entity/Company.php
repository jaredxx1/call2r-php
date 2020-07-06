<?php

declare(strict_types=1);


namespace App\Company\Domain\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;

/**
 * Class Company
 * @package App\Company\Domain\Entity
 */
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
     * @var SLA
     */
    private $sla;

    /**
     * @var ArrayCollection
     */
    private $sections;

    /**
     * Company constructor.
     * @param string $name
     * @param string $description
     * @param string $cnpj
     * @param bool $mother
     * @param bool $active
     * @param SLA $sla
     * @param ArrayCollection $sections
     */
    public function __construct(string $name, string $description, string $cnpj, bool $mother, bool $active, SLA $sla, ArrayCollection $sections)
    {
        $this->name = $name;
        $this->description = $description;
        $this->cnpj = $cnpj;
        $this->mother = $mother;
        $this->active = $active;
        $this->sla = $sla;
        $this->sections = $sections;
    }


    /**
     * @return array|mixed
     * @throws \Exception
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'description' => $this->description(),
            'cnpj' => $this->cnpj(),
            'isMother' => $this->isMother(),
            'isActive' => $this->isActive(),
            'sla' => $this->sla(),
            'sections' => $this->sections()->getValues()
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

    /**
     * @return SLA
     */
    public function sla(): SLA
    {
        return $this->sla;
    }

    /**
     * @return ArrayCollection
     */
    public function sections()
    {
        return $this->sections;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $cnpj
     */
    public function setCnpj(string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param bool $mother
     */
    public function setMother(bool $mother): void
    {
        $this->mother = $mother;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @param SLA $sla
     */
    public function setSla(SLA $sla): void
    {
        $this->sla = $sla;
    }

    /**
     * @param ArrayCollection $sections
     */
    public function setSections(ArrayCollection $sections): void
    {
        $this->sections = $sections;
    }


}