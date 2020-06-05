<?php


namespace App\Company\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class CreateCompanyCommand implements CommandInterface
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $cnpj;

    /**
     * @var bool
     */
    private $mother;

    /**
     * @var bool
     */
    private $active;

    /**
     * CreateCompanyCommand constructor.
     * @param string $name
     * @param string $description
     * @param string $cnpj
     * @param bool $mother
     * @param bool $active
     */
    public function __construct(
        string $name,
        string $description,
        string $cnpj,
        bool $mother,
        bool $active
    )
    {
        $this->name = $name;
        $this->description =  $description;
        $this->cnpj =  $cnpj;
        $this->mother =  $mother;
        $this->active =  $active;
    }

    public static function fromArray($data)
    {
        Assert::notNull($data['name'], 'Name is required');
        Assert::notNull($data['description'], 'Description is required');
        Assert::notNull($data['cnpj'], 'CNPJ is required');
        Assert::notNull($data['mother'], 'Mother is required');
        Assert::notNull($data['active'], 'Active is required');

        Assert::string($data['name'], 'Name is not a string');
        Assert::string($data['description'], 'Description is not a string');
        Assert::string($data['cnpj'], 'CNPJ is not a string');
        Assert::boolean($data['mother'], 'Mother is not a boolean');
        Assert::boolean($data['active'], 'Active is not a boolean');

        Assert::stringNotEmpty($data['name'], 'Name is empty');
        Assert::stringNotEmpty($data['description'], 'Description is empty');
        Assert::stringNotEmpty($data['cnpj'], 'CNPJ is empty');

        Assert::length($data['cnpj'], 13, "CNPJ dont have 14 digits");

        return new self(
            $data['name'],
            $data['description'],
            $data['cnpj'],
            $data['mother'],
            $data['active']
        );
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
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
    public function cnpj(): string
    {
        return $this->cnpj;
    }

    /**
     * @return bool
     */
    public function isMother(): bool
    {
        return $this->mother;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
}