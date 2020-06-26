<?php


namespace App\Wiki\Application\Query;


use App\Core\Infrastructure\Container\Application\Utils\Query\QueryInterface;
use Webmozart\Assert\Assert;

/**
 * Class FindArticlesByIdQuery
 * @package App\Wiki\Application\Query
 */
class FindArticlesByIdQuery implements QueryInterface
{
    /**
     * @var int
     */
    private $idCompany;

    /**
     * @var int
     */
    private $idArticle;

    /**
     * FindArticlesByIdQuery constructor.
     * @param int $idCompany
     * @param int $idArticle
     */
    public function __construct(int $idCompany, int $idArticle)
    {
        $this->idCompany = $idCompany;
        $this->idArticle = $idArticle;
    }


    /**
     * @param array $data
     * @return FindArticlesByIdQuery
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'urlCompany', "Id company must be a integer");
        Assert::keyExists($data, 'urlArticle', "Id article must be a integer");
        Assert::integer($data['urlCompany'], "Id company must be a integer");
        Assert::integer($data['urlArticle'], "Id article must be a integer");

        return new self(
            $data['urlCompany'],
            $data['urlArticle']
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
    public function idArticle(): int
    {
        return $this->idArticle;
    }

    /**
     * @param int $idArticle
     */
    public function setIdArticle(int $idArticle): void
    {
        $this->idArticle = $idArticle;
    }
}