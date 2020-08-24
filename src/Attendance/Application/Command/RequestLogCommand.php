<?php


namespace App\Attendance\Application\Command;

use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

/**
 * Class RequestLogCommand
 * @package App\Attendance\Application\Command
 */
class RequestLogCommand implements CommandInterface
{
    /**
     * @var string
     */
    private $message;

    /**
     * RequestLogCommand constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }


    /**
     * @param array $data
     * @return RequestLogCommand
     */
    public static function fromArray($data)
    {
        Assert::stringNotEmpty($data['message'], 'Field message cannot be empty.');

        return new self(
            $data['message']
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

}