<?php


namespace App\Wiki\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

/**
 * Class UpdateArticleCommand
 * @package App\Wiki\Application\Command
 */
class UpdateArticleCommand implements CommandInterface
{
    /**
     * @var int
     */
    private $id;

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
     * UpdateArticleCommand constructor.
     * @param int $id
     * @param int $idCompany
     * @param string $title
     * @param string $description
     * @param array $categories
     */
    public function __construct(int $id, int $idCompany, string $title, string $description, array $categories)
    {
        $this->id = $id;
        $this->idCompany = $idCompany;
        $this->title = $title;
        $this->description = $description;
        $this->categories = $categories;
    }


    /**
     * @param array $data
     * @return UpdateArticleCommand
     */
    public static function fromArray($data)
    {
        Assert::eq($data['urlArticle'], $data['id'], 'Id article not the same');
        Assert::eq($data['urlCompany'], $data['idCompany'], 'Id company not the same');
        Assert::keyExists($data, 'id', 'Field id is required');
        Assert::keyExists($data, 'title', 'Field title is required');
        Assert::keyExists($data, 'description', 'Field description is required');
        Assert::keyExists($data, 'categories', 'Field categories is required');

        Assert::integer($data['id'], ' Field id  is not an integer');
        Assert::string($data['title'], ' Field title is not a string');
        Assert::string($data['description'], ' Field description is not a string');

        Assert::stringNotEmpty($data['title'], 'Field title is empty');
        Assert::stringNotEmpty($data['description'], 'Field description is empty');

        $categories = $data['categories'];
        Assert::isArray($categories, 'Field categories is not an array');

        foreach ($categories as $category) {
            Assert::keyExists($category, 'title', 'Field category title name is required');
            Assert::keyExists($category, 'active', 'Field category active is required');

            Assert::stringNotEmpty($category['title'], 'Field category title is empty');

            Assert::string($category['title'], 'Field category title is not a string');
            Assert::boolean($category['active'], 'Field category active is not a boolean');
            Assert::eq($category['idCompany'], $data['urlCompany'], 'Id company is different between company and article');
        }

        $data['idCompany'] = $data['urlCompany'];
        return new self(
            $data['id'],
            $data['idCompany'],
            $data['description'],
            $data['title'],
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
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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

    /**
     * @return array
     */
    public function categories(): array
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
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


}