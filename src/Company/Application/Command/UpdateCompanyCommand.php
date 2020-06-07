<?php


namespace App\Company\Application\Command;


use App\Company\Domain\Entity\SLA;
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

    /**
     * @var array
     */
    private $sla;

    /**
     * UpdateCompanyCommand constructor.
     * @param int $id
     * @param string $name
     * @param string $description
     * @param bool $active
     * @param array $sla
     */
    public function __construct(int $id, string $name, string $description, bool $active, array $sla)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->active = $active;
        $this->sla = $sla;
    }

    /**
     * @param array $data
     * @return UpdateCompanyCommand
     */
    public static function fromArray($data)
    {
        // Validation company

        Assert::keyExists($data, 'id', 'field id is required');
        Assert::keyExists($data, 'name', 'field name is required');
        Assert::keyExists($data, 'description', 'field description is required');
        Assert::keyExists($data, 'isActive', 'field isActive is required');

        Assert::string($data['description'], ' Field description is not a string');
        Assert::boolean($data['isActive'], ' Field isActive is not a boolean');

        Assert::stringNotEmpty($data['name'], 'Field name is empty');

        // Validation sla

        $sla = $data['sla'];

        Assert::keyExists($sla, 'p1', 'Field sla p1 is required');
        Assert::keyExists($sla, 'p2', 'Field sla p2 is required');
        Assert::keyExists($sla, 'p3', 'Field sla p3 is required');
        Assert::keyExists($sla, 'p4', 'Field sla p4 is required');
        Assert::keyExists($sla, 'p5', 'Field sla p5 is required');

        Assert::integer($sla['p1'], 'Field sla p1 is not a integer');
        Assert::integer($sla['p2'], 'Field sla p2 is not a integer');
        Assert::integer($sla['p3'], 'Field sla p3 is not a integer');
        Assert::integer($sla['p4'], 'Field sla p4 is not a integer');
        Assert::integer($sla['p5'], 'Field sla p5 is not a integer');

        Assert::notEq($sla['p1'], 0, 'Field sla p1 not be 0');
        Assert::notEq($sla['p2'], 0, 'Field sla p2 not be 0');
        Assert::notEq($sla['p3'], 0, 'Field sla p3 not be 0');
        Assert::notEq($sla['p4'], 0, 'Field sla p4 not be 0');
        Assert::notEq($sla['p5'], 0, 'Field sla p5 not be 0');

        return new self(
            $data['id'],
            $data['name'],
            $data['description'],
            $data['isActive'],
            $data['sla']
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

    /**
     * @return array
     */
    public function sla(): array
    {
        return $this->sla;
    }


}