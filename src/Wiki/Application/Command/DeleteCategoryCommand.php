<?php


namespace App\Wiki\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Query\QueryInterface;
use App\Wiki\Application\Query\FindArticlesByIdQuery;
use Webmozart\Assert\Assert;

class DeleteCategoryCommand implements QueryInterface
{
    /**
     * @var int
     */
    private $idCompany;

    /**
     * @var int
     */
    private $idCategory;

    /**
     * DeleteCategoryCommand constructor.
     * @param int $idCompany
     * @param int $idCategory
     */
    public function __construct(int $idCompany, int $idCategory)
    {
        $this->idCompany = $idCompany;
        $this->idCategory = $idCategory;
    }

    /**
     * @param array $data
     * @return DeleteCategoryCommand
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'urlCompany', "Id company must be a integer");
        Assert::keyExists($data, 'urlCategory', "Id category must be a integer");
        Assert::integer($data['urlCompany'], "Id company must be a integer");
        Assert::integer($data['urlCategory'], "Id category must be a integer");

        return new self(
            $data['urlCompany'],
            $data['urlCategory']
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
    public function idCompany(): int
    {
        return $this->idCompany;
    }

    /**
     * @param int $idCompany
     */
    public function setIdCompany(int $idCompany): void
    {
        $this->idCompany = $idCompany;
    }

    /**
     * @return int
     */
    public function idCategory(): int
    {
        return $this->idCategory;
    }

    /**
     * @param int $idCategory
     */
    public function setIdCategory(int $idCategory): void
    {
        $this->idCategory = $idCategory;
    }

}