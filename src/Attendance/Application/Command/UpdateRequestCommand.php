<?php


namespace App\Attendance\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class UpdateRequestCommand implements CommandInterface
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var integer|null
     */
    private $priority;

    /**
     * UpdateRequestCommand constructor.
     * @param int|null $id
     * @param string|null $title
     * @param string|null $description
     * @param int|null $priority
     */
    public function __construct(?int $id, ?string $title, ?string $description, ?int $priority)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->priority = $priority;
    }

    /**
     * @param array $data
     * @return UpdateRequestCommand
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'id', 'Param id is required');
        Assert::integer($data['id'], 'Field id must be an integer');

        if (key_exists('title', $data)) {
            Assert::stringNotEmpty($data['title'], 'Field title cannot be empty');
        }

        if (key_exists('description', $data)) {
            Assert::stringNotEmpty($data['description'], 'Field description cannot be empty');
        }

        if (key_exists('priority', $data)) {
            Assert::integer($data['priority'], 'Field priority is not an integer');
            Assert::oneOf($data['priority'], [1,2,3,4,5], 'Field priority is neither 1 neither 2 neither 3 neither 4 or 5');
        }

        return new self(
            $data['id'],
            $data['title'] ?? null,
            $data['description'] ?? null,
            $data['priority'] ?? null
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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->priority;
    }
}