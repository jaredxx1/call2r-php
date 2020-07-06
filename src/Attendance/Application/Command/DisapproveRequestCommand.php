<?php


namespace App\Attendance\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class DisapproveRequestCommand implements CommandInterface
{

    /**
     * @var int
     */
    private $requestId;

    /**
     * @var string
     */
    private $message;

    /**
     * DisapproveRequestCommand constructor.
     * @param int $requestId
     * @param string $message
     */
    public function __construct(int $requestId, string $message)
    {
        $this->requestId = $requestId;
        $this->message = $message;
    }

    public static function fromArray($data)
    {
        Assert::keyExists($data, 'requestId', 'Field requestId is required.');
        Assert::keyExists($data, 'message', 'Field message is required.');

        Assert::stringNotEmpty($data['message'], 'Field message cannot be empty.');

        Assert::string($data['message'], 'Field message is not a string.');
        Assert::integer($data['requestId'], 'Field requestId not an integer.');

        return new self(
            $data['requestId'],
            $data['message']
        );
    }

    public function toArray(): array
    {
        return [];
    }

    /**
     * @return int
     */
    public function getRequestId(): int
    {
        return $this->requestId;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}