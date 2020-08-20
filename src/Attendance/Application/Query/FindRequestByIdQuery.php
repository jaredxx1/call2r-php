<?php


namespace App\Attendance\Application\Query;


use App\Core\Infrastructure\Container\Application\Utils\Query\QueryInterface;
use Webmozart\Assert\Assert;

/**
 * Class FindRequestByIdQuery
 * @package App\Attendance\Application\Query
 */
class FindRequestByIdQuery implements QueryInterface
{

    /**
     * @var int
     */
    private $id;

    /**
     * FindRequestByIdQuery constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param array $data
     * @return FindRequestByIdQuery
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'id', "Id must be a integer");
        Assert::integer($data['id'], "Id must be a integer");

        return new self(
            $data['id']
        );
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