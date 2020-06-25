<?php


namespace App\Wiki\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

/**
 * Class CreateArticleCommand
 * @package App\Wiki\Application\Command
 */
class CreateArticleCommand implements CommandInterface
{

    /**
     * @var int
     */
    private $idCompany;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * CreateArticleCommand constructor.
     * @param int $idCompany
     * @param string $title
     * @param string $description
     */
    public function __construct(int $idCompany, string $title, string $description)
    {
        $this->idCompany = $idCompany;
        $this->title = $title;
        $this->description = $description;
    }


    /**
     * @param array $data
     * @return CreateArticleCommand
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'idCompany', 'Field idCompany is required');
        Assert::keyExists($data, 'title', 'Field title is required');
        Assert::keyExists($data, 'description', 'Field description is required');

        Assert::integer($data['idCompany'], ' Field id company is not an integer');
        Assert::string($data['title'], ' Field title is not a string');
        Assert::string($data['description'], ' Field description is not a string');

        Assert::stringNotEmpty($data['title'], 'Field title is empty');
        Assert::stringNotEmpty($data['description'], 'Field description is empty');

        return new self(
            $data['idCompany'],
            $data['title'],
            $data['description']
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
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }


}