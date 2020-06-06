<?php


namespace App\Company\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class UpdateCompanyCommand implements CommandInterface
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
     * @var string
     */
    private $description;

    /**
     * @var bool
     */
    private $active;

    public function __construct(int $id, string $name, string $description, bool $active)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->active = $active;
    }

    /**
     * @param array $data
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'id', 'field id is required');
        Assert::keyExists($data, 'name', 'field name is required');
        Assert::keyExists($data, 'description', 'field description is required');
        Assert::keyExists($data, 'isActive', 'field isActive is required');

        Assert::string($data['description'], ' Field description is not a string');
        Assert::boolean($data['isActive'], ' Field isActive is not a boolean');

        Assert::stringNotEmpty($data['name'], 'Field name is empty');

        return new self(
            $data['id'],
            $data['name'],
            $data['description'],
            $data['isActive']
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
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
}