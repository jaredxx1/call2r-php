<?php


namespace App\Company\Domain\Entity;


use JsonSerializable;

/**
 * Class Section
 * @package App\Company\Domain\Entity
 */
class Section implements JsonSerializable
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
     * @var int
     */
    private $priority;

    /**
     * Section constructor.
     * @param int|null $id
     * @param string $name
     * @param int $priority
     */
    public function __construct(?int $id, string $name, int $priority)
    {
        $this->id = $id;
        $this->name = $name;
        $this->priority = $priority;
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
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'priority' => $this->getPriority()
        ];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
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
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

}