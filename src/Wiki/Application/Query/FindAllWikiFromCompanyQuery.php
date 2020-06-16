<?php


namespace App\Wiki\Application\Query;


use App\Core\Infrastructure\Container\Application\Utils\Query\QueryInterface;
use Webmozart\Assert\Assert;

class FindAllWikiFromCompanyQuery implements QueryInterface
{
    /**
     * @var int
     */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param array $data
     * @return FindAllWikiFromCompanyQuery
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