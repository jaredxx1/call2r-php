<?php


namespace App\Attendance\Application\Command;

use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

/**
 * Class MoveToAwaitingResponseCommand
 * @package App\Attendance\Application\Command
 */
class MoveToAwaitingResponseCommand implements CommandInterface
{
    /**
     * @var string|null
     */
    private $message;

    /**
     * MoveToAwaitingResponseCommand constructor.
     * @param string|null $message
     */
    public function __construct(?string $message)
    {
        $this->message = $message;
    }

    /**
     * @param array $data
     * @return MoveToAwaitingResponseCommand
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

    /**
     * @return array
     */
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