<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Application\Exception\AnsweredResponseException;
use App\Attendance\Application\Exception\AwaitingResponseException;
use App\Attendance\Application\Exception\CanceledRequestException;
use App\Attendance\Domain\Entity\Request;
use App\Company\Application\Service\CompanyService;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Domain\Entity\User;
use Webmozart\Assert\Assert;

class AnsweredRequestActionCommand implements CommandInterface
{
    /**
     * @var string|null
     */
    private $message;

    /**
     * AnsweredRequestActionCommand constructor.
     * @param string|null $message
     */
    public function __construct(?string $message)
    {
        $this->message = $message;
    }

    /**
     * @param array $data
     * @return AnsweredRequestActionCommand
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