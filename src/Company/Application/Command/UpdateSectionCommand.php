<?php


namespace App\Company\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

/**
 * Class UpdateSectionCommand
 * @package App\Company\Application\Command
 */
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
     * UpdateSectionCommand constructor.
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
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

        Assert::stringNotEmpty($data['name'], 'Field name cannot be empty');

        Assert::integer($data['id'], 'Field id is not an integer');
        Assert::string($data['name'], 'Field name is not a string');

        return new self(
            $data['id'],
            $data['name']
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

}