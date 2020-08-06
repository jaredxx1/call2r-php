<?php


namespace App\Wiki\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

/**
 * Class DeleteArticleCommand
 * @package App\Wiki\Application\Query
 */
class DeleteArticleCommand implements CommandInterface
{
    /**
     * @var int
     */
    private $idArticle;

    /**
     * @var int
     */
    private $idCompany;

    /**
     * DeleteArticleCommand constructor.
     * @param int $idArticle
     * @param int $idCompany
     */
    public function __construct(int $idArticle, int $idCompany)
    {
        $this->idArticle = $idArticle;
        $this->idCompany = $idCompany;
    }


    /**
     * @param array $data
     * @return DeleteArticleCommand
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'urlArticle', "Id article is required");
        Assert::keyExists($data, 'urlCompany', "Id company is required");
        Assert::integer($data['urlArticle'], "Id article must be a integer");
        Assert::integer($data['urlCompany'], "Id company must be a integer");

        return new self(
            $data['urlArticle'],
            $data['urlCompany']
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
     * @return int
     */
    public function idArticle(): int
    {
        return $this->idArticle;
    }
}