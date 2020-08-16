<?php

declare(strict_types=1);


namespace App\Company\Domain\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use JsonSerializable;

/**
 * Class Company
 * @package App\Company\Domain\Entity
 */
class Company implements JsonSerializable
{

    /**
     * @var int|null
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
     * @param int|null $id
     * @param string $name
     * @param string $description
     * @param string $cnpj
     * @param bool $mother
     * @param bool $active
     * @param SLA $sla
     * @param ArrayCollection $sections
     */
    public function __construct(?int $id, string $name, string $description, string $cnpj, bool $mother, bool $active, SLA $sla, ArrayCollection $sections)
    {
        $this->id = $id;
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
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'cnpj' => $this->getCnpj(),
            'isMother' => $this->isMother(),
            'isActive' => $this->isActive(),
            'sla' => $this->getSla(),
            'sections' => $this->getSections()->getValues()
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @return string
     */
    public function getCnpj(): string
    {
        return $this->cnpj;
    }

    /**
     * @param string $cnpj
     */
    public function setCnpj(string $cnpj): void
    {
        $this->cnpj = $cnpj;
    }

    /**
     * @return bool
     */
    public function isMother(): bool
    {
        return $this->mother;
    }

    /**
     * @param bool $mother
     */
    public function setMother(bool $mother): void
    {
        $this->mother = $mother;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return SLA
     */
    public function getSla(): SLA
    {
        return $this->sla;
    }

    /**
     * @param SLA $sla
     */
    public function setSla(SLA $sla): void
    {
        $this->sla = $sla;
    }

    /**
     * @return ArrayCollection
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param ArrayCollection $sections
     */
    public function setSections(ArrayCollection $sections): void
    {
        $this->sections = $sections;
    }


}