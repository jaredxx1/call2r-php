<?php


namespace App\Wiki\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class UpdateCategoryCommand implements CommandInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * UpdateCategoryCommand constructor.
     * @param int $id
     * @param string $title
     */
    public function __construct(int $id, string $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * @param array $data
     * @return UpdateCategoryCommand
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'id', 'Field id is required');

        Assert::eq($data['urlCategory'], $data['id'], 'Id category not the same');

        Assert::keyExists($data, 'title', 'Field category title name is required');

        Assert::stringNotEmpty($data['title'], 'Field category title is empty');

        Assert::integer($data['id'],  'Field id is not an integer');
        Assert::string($data['title'], 'Field category title is not a string');

        return new self(
            $data['id'],
            $data['title']
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
}