<?php


namespace App\Company\Domain\Entity;


use JsonSerializable;

/**
 * Class SLA
 * @package App\Company\Domain\Entity
 */
class SLA implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var int
     */
    private $p1;

    /**
     * @var int
     */
    private $p2;

    /**
     * @var int
     */
    private $p3;

    /**
     * @var int
     */
    private $p4;

    /**
     * @var int
     */
    private $p5;

    /**
     * SLA constructor.
     * @param int|null $id
     * @param int $p1
     * @param int $p2
     * @param int $p3
     * @param int $p4
     * @param int $p5
     */
    public function __construct(?int $id, int $p1, int $p2, int $p3, int $p4, int $p5)
    {
        $this->id = $id;
        $this->p1 = $p1;
        $this->p2 = $p2;
        $this->p3 = $p3;
        $this->p4 = $p4;
        $this->p5 = $p5;
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
            'p1' => $this->getP1(),
            'p2' => $this->getP2(),
            'p3' => $this->getP3(),
            'p4' => $this->getP4(),
            'p5' => $this->getP5()
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
     * @return int
     */
    public function getP1(): int
    {
        return $this->p1;
    }

    /**
     * @param int $p1
     */
    public function setP1(int $p1): void
    {
        $this->p1 = $p1;
    }

    /**
     * @return int
     */
    public function getP2(): int
    {
        return $this->p2;
    }

    /**
     * @param int $p2
     */
    public function setP2(int $p2): void
    {
        $this->p2 = $p2;
    }

    /**
     * @return int
     */
    public function getP3(): int
    {
        return $this->p3;
    }

    /**
     * @param int $p3
     */
    public function setP3(int $p3): void
    {
        $this->p3 = $p3;
    }

    /**
     * @return int
     */
    public function getP4(): int
    {
        return $this->p4;
    }

    /**
     * @param int $p4
     */
    public function setP4(int $p4): void
    {
        $this->p4 = $p4;
    }

    /**
     * @return int
     */
    public function getP5(): int
    {
        return $this->p5;
    }

    /**
     * @param int $p5
     */
    public function setP5(int $p5): void
    {
        $this->p5 = $p5;
    }


}