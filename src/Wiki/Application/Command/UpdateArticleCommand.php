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
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var array|null
     */
    private $categories;

    /**
     * UpdateArticleCommand constructor.
     * @param int $id
     * @param int $idCompany
     * @param string|null $title
     * @param string|null $description
     * @param array|null $categories
     */
    public function __construct(int $id, int $idCompany, ?string $title, ?string $description, ?array $categories)
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
        Assert::keyExists($data, 'id', 'Field id is required');
        Assert::eq($data['urlArticle'], $data['id'], 'Id article not the same');
        $data['idCompany'] = $data['urlCompany'];

        if (key_exists('title', $data)) {
            Assert::stringNotEmpty($data['title'], 'Field title cannot be empty');
        }

        if (key_exists('description', $data)) {
            Assert::stringNotEmpty($data['description'], 'Field description cannot be empty');
        }

        if (key_exists('categories', $data)) {
            self::validateCategories($data['categories']);
        }

        return new self(
            $data['id'],
            $data['idCompany'],
            $data['title'] ?? null,
            $data['description'] ?? null,
            $data['categories'] ?? null
        );
    }

    /**
     * @param $categories
     */
    private static function validateCategories($categories): void
    {
        Assert::isArray($categories, 'Field categories is not an array');

        foreach ($categories as $category) {
            if (key_exists('title', $category)) {
                Assert::stringNotEmpty($category['title'], 'Field title cannot be empty');
            }
        }
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

    /**
     * @return int
     */
    public function getIdCompany(): int
    {
        return $this->idCompany;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return array|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }
}