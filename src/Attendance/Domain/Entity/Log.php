<?php


namespace App\Attendance\Domain\Entity;


use Carbon\Carbon;
use DateTime;
use Exception;
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
     * Log constructor.
     * @param int $id
     * @param string $message
     * @param DateTime $createdAt
     * @param string $command
     */
    public function __construct(?int $id, string $message, ?DateTime $createdAt, string $command)
    {
        $this->id = $id;
        $this->message = $message;
        $this->createdAt = $createdAt;
        $this->command = $command;
    }

    /**
     * @return array|mixed
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
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'message' => $this->getMessage(),
            'command' => $this->getCommand(),
            'createdAt' => (new Carbon($this->getCreatedAt()))
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
     * @return string
     */
    public function getCommand(): ?string
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
}