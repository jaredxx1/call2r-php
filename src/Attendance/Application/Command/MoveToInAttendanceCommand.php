<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Domain\Entity\Request;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Application\Exception\InvalidUserPrivileges;
use App\User\Domain\Entity\User;
use Webmozart\Assert\Assert;

/**
 * Class MoveToInAttendanceCommand
 * @package App\Attendance\Application\Command
 */
class MoveToInAttendanceCommand   implements CommandInterface
{
    /**
     * @var string|null
     */
    private $message;

    /**
     * MoveToInAttendanceCommand constructor.
     * @param string|null $message
     */
    public function __construct(?string $message)
    {
        $this->message = $message;
    }

    /**
     * @param array $data
     * @return MoveToInAttendanceCommand
     */
    public static function fromArray($data)
    {
        if (key_exists('message', $data)) {
            Assert::stringNotEmpty($data['message'], 'Field message cannot be empty.');
        }

        return new self(
            $data['message'] ?? null
        );
    }

    public function toArray(): array
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}