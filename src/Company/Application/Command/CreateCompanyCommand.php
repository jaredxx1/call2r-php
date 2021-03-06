<?php


namespace App\Company\Application\Command;


use App\Company\Application\Exception\InvalidCnpjException;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\Core\Infrastructure\Container\Application\Utils\Validations\CNPJ;
use Webmozart\Assert\Assert;

/**
 * Class CreateCompanyCommand
 * @package App\Company\Application\Command
 */
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
     * @var array
     */
    private $sla;


    /**
     * @var array
     */
    private $sections;

    /**
     * CreateCompanyCommand constructor.
     * @param string $name
     * @param string $description
     * @param string $cnpj
     * @param bool $mother
     * @param bool $active
     * @param array $sla
     * @param array $sections
     */
    public function __construct(string $name, string $description, string $cnpj, bool $mother, bool $active, array $sla, array $sections)
    {
        $this->name = $name;
        $this->description = $description;
        $this->cnpj = $cnpj;
        $this->mother = $mother;
        $this->active = $active;
        $this->sla = $sla;
        $this->sections = $sections;
    }


    /**
     * @param array $data
     * @return CreateCompanyCommand
     * @throws InvalidCnpjException
     */
    public static function fromArray($data)
    {

        //Company object validation
        Assert::keyExists($data, 'description', 'Field description is required');
        Assert::keyExists($data, 'name', 'Field name is required');
        Assert::keyExists($data, 'cnpj', 'Field CNPJ is required');
        Assert::keyExists($data, 'mother', 'Field mother is required');
        Assert::keyExists($data, 'active', 'Field active is required');
        Assert::keyExists($data, 'sla', 'Object sla is required');
        Assert::keyExists($data, 'sections', 'Array sections is required');

        Assert::string($data['name'], ' Field name is not a string');
        Assert::string($data['description'], ' Field description is not a string');
        Assert::string($data['cnpj'], ' Field CNPJ is not a string');
        Assert::boolean($data['mother'], ' Field mother is not a boolean');
        Assert::boolean($data['active'], ' Field active is not a boolean');

        Assert::stringNotEmpty($data['name'], 'Field name is empty');
        Assert::stringNotEmpty($data['cnpj'], 'Field CNPJ is empty');

        if(!CNPJ::validate($data['cnpj'])){
            throw new InvalidCnpjException();
        }

        Assert::stringNotEmpty($data['description'], 'Field description is empty');

        Assert::length($data['cnpj'], 14, "Field CNPJ don't have 14 digits");

        //SLA object validation
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
            Assert::stringNotEmpty($section['name'], 'Field section name is empty');
            Assert::string($section['name'], 'Field section name is not a string');
        }

        return new self(
            $data['name'],
            $data['description'],
            $data['cnpj'],
            $data['mother'],
            $data['active'],
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCnpj(): string
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

    /**
     * @return array
     */
    public function getSla(): array
    {
        return $this->sla;
    }

    /**
     * @return array
     */
    public function getSections(): array
    {
        return $this->sections;
    }


}