<?php


namespace App\Attendance\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class CreateRequestCommand implements CommandInterface
{
    /**
     * @var array
     */
    private $status;

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
     * @var int
     */
    private $assignedTo;

    /**
     * CreateRequestCommand constructor.
     * @param array $status
     * @param int $companyId
     * @param string $title
     * @param string $description
     * @param int $priority
     * @param string $section
     * @param int $assignedTo
     */
    public function __construct(array $status, int $companyId, string $title, string $description, int $priority, string $section, int $assignedTo)
    {
        $this->status = $status;
        $this->companyId = $companyId;
        $this->title = $title;
        $this->description = $description;
        $this->priority = $priority;
        $this->section = $section;
        $this->assignedTo = $assignedTo;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'status', "Field status is required");
        Assert::keyExists($data, 'companyId', 'Field companyId is required');
        Assert::keyExists($data, 'title', 'Field title is required');
        Assert::keyExists($data, 'description', 'Field description is required');
        Assert::keyExists($data, 'priority', 'Field priority is required');
        Assert::keyExists($data, 'section', 'Field section is required');
        Assert::keyExists($data, 'assignedTo', 'Field assigned to is required');

        Assert::isArray($data['status'], ' Field status is not an array');
        Assert::integer($data['companyId'], ' Field company id is not an integer');
        Assert::string($data['title'], ' Field title is not a string');
        Assert::string($data['description'], ' Field description is not a string');
        Assert::integer($data['priority'], ' Field priority is not an integer');
        Assert::string($data['section'], ' Field section is not a string');
        Assert::integer($data['assignedTo'], ' Field assigned to is not an integer');

        Assert::stringNotEmpty($data['title'], 'Field title is empty');
        Assert::stringNotEmpty($data['description'], 'Field description is empty');
        Assert::stringNotEmpty($data['section'], 'Field section is empty');

        $status = $data['status'];

        Assert::keyExists($status, 'id', 'Field status id is required');
        Assert::keyExists($status, 'name', 'Field status name is required');

        Assert::integer($status['id'], ' Field status id is not an integer');
        Assert::string($status['name'], ' Field status name is not a string');

        Assert::stringNotEmpty($status['name'], 'Field status name is empty');

        return new self(
            $data['status'],
            $data['companyId'],
            $data['title'],
            $data['description'],
            $data['priority'],
            $data['section'],
            $data['assignedTo']
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
     * @return array
     */
    public function getStatus(): array
    {
        return $this->status;
    }

    /**
     * @param array $status
     */
    public function setStatus(array $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    /**
     * @param int $companyId
     */
    public function setCompanyId(int $companyId): void
    {
        $this->companyId = $companyId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
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
    public function getDescription(): string
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
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getSection(): string
    {
        return $this->section;
    }

    /**
     * @param string $section
     */
    public function setSection(string $section): void
    {
        $this->section = $section;
    }

    /**
     * @return int
     */
    public function getAssignedTo(): int
    {
        return $this->assignedTo;
    }

    /**
     * @param int $assignedTo
     */
    public function setAssignedTo(int $assignedTo): void
    {
        $this->assignedTo = $assignedTo;
    }
}