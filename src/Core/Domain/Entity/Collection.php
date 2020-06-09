<?php


namespace App\Core\Domain\Entity;


use ArrayIterator;
use Countable;
use IteratorAggregate;

abstract class Collection implements Countable, IteratorAggregate
{

    /** @var array */
    private $items;

    /**
     * Collection constructor.
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }


    /**
     * @return string
     */
    abstract protected function type(): string;

    /**
     * @return ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items());
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items());
    }

    /**
     * @return array
     */
    public function items()
    {
        return $this->items;
    }
}