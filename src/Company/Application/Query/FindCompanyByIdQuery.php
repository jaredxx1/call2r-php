<?php


namespace App\Company\Application\Query;


use App\Core\Infrastructure\Container\Application\Utils\Query\QueryInterface;
use Webmozart\Assert\Assert;

/**
 * Class FindCompanyByIdQuery
 * @package App\Company\Application\Query
 */
class FindCompanyByIdQuery implements QueryInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * FindCompanyByIdQuery constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param array $data
     * @return FindCompanyByIdQuery
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
    public function id(): int
    {
        return $this->id;
    }
}