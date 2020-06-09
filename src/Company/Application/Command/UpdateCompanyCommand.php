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
     * @var array
     */
    private $sections;

    /**
     * UpdateCompanyCommand constructor.
     * @param int $id
     * @param string $name
     * @param string $description
     * @param bool $active
     * @param array $sla
     */
    public function __construct(int $id, string $name, string $description, bool $active, array $sla, array $sections)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->active = $active;
        $this->sla = $sla;
        $this->sections = $sections;
    }

    /**
     * @param array $data
     * @return UpdateCompanyCommand
     */
    public static function fromArray($data)
    {
        Assert::eq($data['id'], $data['url'], 'Id is not equal');

        // Validation company

        Assert::keyExists($data, 'id', 'field id is required');
        Assert::keyExists($data, 'name', 'field name is required');
        Assert::keyExists($data, 'description', 'field description is required');
        Assert::keyExists($data, 'isActive', 'field isActive is required');
        Assert::keyExists($data, 'sla', 'Field sla is required');
        Assert::keyExists($data, 'sections', 'Array sections is required');

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

        // Section array validation
        $sections = $data['sections'];

        Assert::isArray($sections, 'Field sections is not an array');

        foreach ($sections as $section) {
            Assert::keyExists($section, 'name', 'Field section name is required');
            Assert::keyExists($section, 'priority', 'Field section priority is required');
            Assert::stringNotEmpty($section['name'], 'Field section name is empty');
            Assert::string($section['name'], 'Field section name is not a string');
            Assert::integer($section['priority'], 'Field section priority is not an int');
            Assert::notEq($section['priority'], 0, 'Field sections priority not be 0');
        }

        return new self(
            $data['id'],
            $data['name'],
            $data['description'],
            $data['isActive'],
            $data['sla'],
            $data['sections']
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

    /**
     * @return array
     */
    public function sections(): array
    {
        return $this->sections;
    }
}