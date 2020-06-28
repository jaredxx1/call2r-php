<?php


namespace App\Attendance\Domain\Entity;


use JsonSerializable;
use PhpParser\Node\Expr\Cast\Object_;

/**
 * Class Request
 * @package App\Attendance\Domain\Entity
 */
class Request implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Status
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
     * @var int
     */
    private $requestedBy;

    /**
     * @var object
     */
    private $createdAt;

    /**
     * @var object
     */
    private $updatedAt;

    /**
     * @var object
     */
    private $finishedAt;

    /**
     * Request constructor.
     * @param int $id
     * @param Status $status
     * @param int $companyId
     * @param string $title
     * @param string $description
     * @param int $priority
     * @param string $section
     * @param int $assignedTo
     * @param int $requestedBy
     * @param object $createdAt
     * @param object $updatedAt
     * @param object $finishedAt
     */
    public function __construct(int $id, Status $status, int $companyId, string $title, string $description, int $priority, string $section, int $assignedTo, int $requestedBy, object $createdAt, object $updatedAt, object $finishedAt)
    {
        $this->id = $id;
        $this->status = $status;
        $this->companyId = $companyId;
        $this->title = $title;
        $this->description = $description;
        $this->priority = $priority;
        $this->section = $section;
        $this->assignedTo = $assignedTo;
        $this->requestedBy = $requestedBy;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->finishedAt = $finishedAt;
    }


    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray():array
    {
        return [
            'id' => $this->getId(),
            'status' => $this->getStatus(),
            'companyId' => $this->getCompanyId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'priority' => $this->getPriority(),
            'section' => $this->getSection(),
            'assignedTo' => $this->getAssignedTo(),
            'requestedBy' => $this->getRequestedBy(),
            'createdAt' => $this->getCreatedAt(),
            'updatedAt' => $this->getUpdatedAt(),
            'finishedAt' => $this->getFinishedAt()
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
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
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status): void
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

    /**
     * @return int
     */
    public function getRequestedBy(): int
    {
        return $this->requestedBy;
    }

    /**
     * @param int $requestedBy
     */
    public function setRequestedBy(int $requestedBy): void
    {
        $this->requestedBy = $requestedBy;
    }

    /**
     * @return object
     */
    public function getCreatedAt(): object
    {
        return $this->createdAt;
    }

    /**
     * @param object $createdAt
     */
    public function setCreatedAt(object $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return object
     */
    public function getUpdatedAt(): object
    {
        return $this->updatedAt;
    }

    /**
     * @param object $updatedAt
     */
    public function setUpdatedAt(object $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return object
     */
    public function getFinishedAt(): object
    {
        return $this->finishedAt;
    }

    /**
     * @param object $finishedAt
     */
    public function setFinishedAt(object $finishedAt): void
    {
        $this->finishedAt = $finishedAt;
    }

}