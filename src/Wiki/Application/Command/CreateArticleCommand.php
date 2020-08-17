<?php


namespace App\Wiki\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Domain\Entity\User;
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
     * @var array
     */
    private $categories;

    /**
     * CreateArticleCommand constructor.
     * @param int $idCompany
     * @param string $title
     * @param string $description
     * @param array $categories
     */
    public function __construct(int $idCompany, string $title, string $description, array $categories)
    {
        $this->idCompany = $idCompany;
        $this->title = $title;
        $this->description = $description;
        $this->categories = $categories;
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
        Assert::keyExists($data, 'categories', 'Field categories is required');

        Assert::integer($data['idCompany'], ' Field id company is not an integer');
        Assert::string($data['title'], ' Field title is not a string');
        Assert::string($data['description'], ' Field description is not a string');

        Assert::stringNotEmpty($data['title'], 'Field title is empty');
        Assert::stringNotEmpty($data['description'], 'Field description is empty');

        $categories = $data['categories'];
        Assert::isArray($categories, 'Field categories is not an array');

        foreach ($categories as $category) {
            Assert::keyExists($category, 'title', 'Field category title name is required');
            Assert::stringNotEmpty($category['title'], 'Field category title is empty');
        }
        return new self(
            $data['idCompany'],
            $data['title'],
            $data['description'],
            $data['categories']
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

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }
}