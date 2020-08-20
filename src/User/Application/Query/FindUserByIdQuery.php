<?php


namespace App\User\Application\Query;


use App\Core\Infrastructure\Container\Application\Utils\Query\QueryInterface;

/**
 * Class FindUserByIdQuery
 * @package App\User\Application\Query
 */
class FindUserByIdQuery implements QueryInterface
{

    /**
     * @var int
     */
    private $id;

    /**
     * FindUserByIdQuery constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param array $data
     * @return FindUserByIdQuery
     */
    public static function fromArray($data)
    {
        return new self($data['id']);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}