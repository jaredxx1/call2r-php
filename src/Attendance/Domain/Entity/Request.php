<?php


namespace App\Attendance\Domain\Entity;


use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use JsonSerializable;

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
    private $sla;

    /**
     * @var string
     */
    private $section;

    /**
     * @var int|null
     */
    private $assignedTo;

    /**
     * @var int|null
     */
    private $requestedBy;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @var DateTime
     */
    private $finishedAt;

    /**
     * @var array|ArrayCollection|null
     */
    private $logs;

    /**
     * Request constructor.
     * @param int|null $id
     * @param Status $status
     * @param int $companyId
     * @param string $title
     * @param string $description
     * @param int $priority
     * @param string $section
     * @param int|null $assignedTo
     * @param int $requestedBy
     * @param object $createdAt
     * @param object $updatedAt
     * @param object $finishedAt
     * @param ArrayCollection $logs
     */
    public function __construct(?int $id, Status $status, int $companyId, string $title, string $description, int $priority, string $section, ?int $assignedTo, ?int $requestedBy, ?object $createdAt, ?object $updatedAt, ?object $finishedAt, ?ArrayCollection $logs)
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
        $this->logs = $logs;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'priority' => $this->getPriority(),
            'sla' => $this->getSla(),
            'section' => $this->getSection(),
            'assignedTo' => $this->getAssignedTo(),
            'requestedBy' => $this->getRequestedBy(),
            'createdAt' => $this->getCreatedAt() ? (new Carbon($this->getCreatedAt())) : null,
            'updatedAt' => $this->getUpdatedAt() ? (new Carbon($this->getUpdatedAt())) : null,
            'finishedAt' => $this->getFinishedAt() ? (new Carbon($this->getFinishedAt())) : null,
            'companyId' => $this->getCompanyId(),
            'status' => $this->getStatus()->getName(),
            'logs' => $this->getLogs()->getValues()
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
    public function getSla(): ?string
    {
        return $this->sla;
    }

    /**
     * @param string $sla
     */
    public function setSla(string $sla): void
    {
        $this->sla = $sla;
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
     * @return int|null
     */
    public function getAssignedTo(): ?int
    {
        return $this->assignedTo;
    }

    /**
     * @param int|null $assignedTo
     */
    public function setAssignedTo(?int $assignedTo): void
    {
        $this->assignedTo = $assignedTo;
    }

    /**
     * @return int|null
     */
    public function getRequestedBy(): ?int
    {
        return $this->requestedBy;
    }

    /**
     * @param int|null $requestedBy
     */
    public function setRequestedBy(?int $requestedBy): void
    {
        $this->requestedBy = $requestedBy;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return DateTime
     */
    public function getFinishedAt(): ?DateTime
    {
        return $this->finishedAt;
    }

    /**
     * @param DateTime $finishedAt
     */
    public function setFinishedAt(DateTime $finishedAt): void
    {
        $this->finishedAt = $finishedAt;
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
     * @return Status
     */
    public function getStatus(): ?Status
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
     * @return array|ArrayCollection|null
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param array|ArrayCollection|null $logs
     */
    public function setLogs($logs): void
    {
        $this->logs = $logs;
    }
}