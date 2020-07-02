<?php


namespace App\Attendance\Domain\Entity;


use DateTime;
use JsonSerializable;

/**
 * Class Log
 * @package App\Attendance\Domain\Entity
 */
class Log implements JsonSerializable
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $message;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $command;

    /**
     * @var int
     */
    private $requestId;

    /**
     * Log constructor.
     * @param int $id
     * @param string $message
     * @param DateTime $createdAt
     * @param string $command
     * @param int $requestId
     */
    public function __construct(?int $id, string $message, ?DateTime $createdAt, string $command, int $requestId)
    {
        $this->id = $id;
        $this->message = $message;
        $this->createdAt = $createdAt;
        $this->command = $command;
        $this->requestId = $requestId;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'message' => $this->getMessage(),
            'command' => $this->getCommand(),
            'createdAt' => $this->getCreatedAt()
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
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
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
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    /**
     * @return int
     */
    public function getRequestId(): int
    {
        return $this->requestId;
    }

    /**
     * @param int $requestId
     */
    public function setRequestId(int $requestId): void
    {
        $this->requestId = $requestId;
    }
}