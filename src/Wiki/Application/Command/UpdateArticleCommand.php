<?php


namespace App\Wiki\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class UpdateArticleCommand implements CommandInterface
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
     * @var string
     */
    private $description;

    /**
     * UpdateArticleCommand constructor.
     * @param int $id
     * @param string $title
     * @param string $description
     */
    public function __construct(int $id, string $title, string $description)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }


    public static function fromArray($data)
    {
        Assert::eq($data['url'], $data['id'], 'Id article not the same');

        Assert::keyExists($data, 'id', 'Field id is required');
        Assert::keyExists($data, 'title', 'Field title is required');
        Assert::keyExists($data, 'description', 'Field description is required');

        Assert::integer($data['id'], ' Field id  is not an integer');
        Assert::string($data['title'], ' Field title is not a string');
        Assert::string($data['description'], ' Field description is not a string');

        Assert::stringNotEmpty($data['title'], 'Field title is empty');
        Assert::stringNotEmpty($data['description'], 'Field description is empty');

        return new self(
            $data['id'],
            $data['description'],
            $data['title']
        );
    }

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



}