<?php


namespace App\Attendance\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

/**
 * Class MoveToFinishedCommand
 * @package App\Attendance\Application\Command
 */
class MoveToFinishedCommand implements CommandInterface
{
    /**
     * @var string|null
     */
    private $message;

    /**
     * MoveToFinishedCommand constructor.
     * @param string|null $message
     */
    public function __construct(?string $message)
    {
        $this->message = $message;
    }

    /**
     * @param array $data
     * @return MoveToFinishedCommand
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