<?php


namespace App\Wiki\Application\Query;


use App\Core\Infrastructure\Container\Application\Utils\Query\QueryInterface;
use Webmozart\Assert\Assert;

/**
 * Class FindAllCategoriesFromCompanyQuery
 * @package App\Wiki\Application\Query
 */
class FindAllCategoriesFromCompanyQuery implements QueryInterface
{
    /**
     * @var int
     */
    private $idCompany;

    /**
     * FindAllCategoriesFromCompanyQuery constructor.
     * @param int $idCompany
     */
    public function __construct(int $idCompany)
    {
        $this->idCompany = $idCompany;
    }

    /**
     * @param array $data
     * @return FindAllCategoriesFromCompanyQuery
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'idCompany', "Id must be a integer");
        Assert::integer($data['idCompany'], "Id must be a integer");

        return new self(
            $data['idCompany']
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
    public function getIdCompany(): int
    {
        return $this->idCompany;
    }
}