<?php


namespace App\Attendance\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class CreateLogCommand implements CommandInterface
{

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $command;

    /**
     * CreateLogCommand constructor.
     * @param string $message
     * @param string $command
     */
    public function __construct(string $message, string $command)
    {
        $this->message = $message;
        $this->command = $command;
    }

    public static function fromArray($data)
    {
        Assert::keyExists($data, 'message', 'Field message is required');
        Assert::keyExists($data, 'command', 'Field command is required');

        Assert::notNull($data['message'], 'Field message cannot be null.');
        Assert::notNull($data['command'], 'Field command cannot be null.');

        Assert::string($data['message'], 'Field message must be a string');
        Assert::string($data['command'], 'Field command must be a string');

        return new self(
            $data['message'],
            $data['command']
        );
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }
}