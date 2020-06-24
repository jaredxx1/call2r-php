<?php


namespace App\Company\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class UpdateSectionCommand implements CommandInterface
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $priority;

    /**
     * UpdateSectionCommand constructor.
     * @param int $id
     * @param string $name
     * @param int $priority
     */
    public function __construct(int $id, string $name, int $priority)
    {
        $this->id = $id;
        $this->name = $name;
        $this->priority = $priority;
    }

    /**
     * @param array $data
     * @return UpdateSectionCommand
     */
    public static function fromArray($data)
    {
        Assert::eq($data['url'], $data['id'], 'Id section not the same');

        Assert::keyExists($data, 'id', 'Field id is required');
        Assert::keyExists($data, 'name', 'Field name is required');
        Assert::keyExists($data, 'priority', 'Field priority is required');

        Assert::stringNotEmpty($data['name'], 'Field name cannot be empty');

        Assert::integer($data['id'], 'Field id is not an integer');
        Assert::string($data['name'], 'Field name is not a string');
        Assert::integer($data['priority'], 'Field priority is not an integer');

        return new self(
            $data['id'],
            $data['name'],
            $data['priority']
        );

    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function priority(): int
    {
        return $this->priority;
    }


}