<?php


namespace App\Wiki\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class CreateWikiCategoryCommand implements CommandInterface
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var bool
     */
    private $active;

    /**
     * CreateWikiCategoryCommand constructor.
     * @param string $title
     * @param bool $active
     */
    public function __construct(string $title, bool $active)
    {
        $this->title = $title;
        $this->active = $active;
    }


    public static function fromArray($data)
    {

        Assert::keyExists($data, 'title', 'Field title is required');
        Assert::keyExists($data, 'active', 'Field active is required');

        Assert::string($data['title'], ' Field title is not a string');
        Assert::boolean($data['active'], ' Field active is not a boolean');

        Assert::stringNotEmpty($data['title'], 'Field title is empty');

        return new self(
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