<?php


namespace App\Attendance\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

/**
 * Class CreateRequestCommand
 * @package App\Attendance\Application\Command
 */
class CreateRequestCommand implements CommandInterface
{
    /**
     * @var int
     */
    private $companyId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var string
     */
    private $section;

    /**
     * CreateRequestCommand constructor.
     * @param int $companyId
     * @param string $title
     * @param string $description
     * @param int $priority
     * @param string $section
     */
    public function __construct(int $companyId, string $title, string $description, int $priority, string $section)
    {
        $this->companyId = $companyId;
        $this->title = $title;
        $this->description = $description;
        $this->priority = $priority;
        $this->section = $section;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'companyId', 'Field companyId is required');
        Assert::keyExists($data, 'title', 'Field title is required');
        Assert::keyExists($data, 'description', 'Field description is required');
        Assert::keyExists($data, 'priority', 'Field priority is required');
        Assert::keyExists($data, 'section', 'Field section is required');

        Assert::integer($data['companyId'], ' Field company id is not an integer');
        Assert::string($data['title'], ' Field title is not a string');
        Assert::string($data['description'], ' Field description is not a string');
        Assert::integer($data['priority'], ' Field priority is not an integer');
        Assert::string($data['section'], ' Field section is not a string');

        Assert::stringNotEmpty($data['title'], 'Field title is empty');
        Assert::stringNotEmpty($data['description'], 'Field description is empty');
        Assert::stringNotEmpty($data['section'], 'Field section is empty');

        return new self(
            $data['companyId'],
            $data['title'],
            $data['description'],
            $data['priority'],
            $data['section']
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
    public function getCompanyId(): int
    {
        return $this->companyId;
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
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @return string
     */
    public function getSection(): string
    {
        return $this->section;
    }
}