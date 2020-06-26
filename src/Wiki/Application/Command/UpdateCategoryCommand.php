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
     * @var boolean
     */
    private $active;

    /**
     * UpdateCategoryCommand constructor.
     * @param int $id
     * @param string $title
     * @param bool $active
     */
    public function __construct(int $id, string $title, bool $active)
    {
        $this->id = $id;
        $this->title = $title;
        $this->active = $active;
    }

    /**
     * @param array $data
     * @return UpdateCategoryCommand
     */
    public static function fromArray($data)
    {
        Assert::eq($data['urlCategory'], $data['id'], 'Id category not the same');
        Assert::eq($data['urlCompany'], $data['idCompany'], 'Id company not the same');

        Assert::keyExists($data, 'id', 'Field id is required');
        Assert::keyExists($data, 'title', 'Field category title name is required');
        Assert::keyExists($data, 'active', 'Field category active is required');

        Assert::stringNotEmpty($data['title'], 'Field category title is empty');

        Assert::integer($data['id'],  'Field id is not an integer');
        Assert::string($data['title'], 'Field category title is not a string');
        Assert::boolean($data['active'], 'Field category active is not a boolean');

        return new self(
            $data['id'],
            $data['title'],
            $data['active']
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
     * @return bool
     */
    public function active(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}