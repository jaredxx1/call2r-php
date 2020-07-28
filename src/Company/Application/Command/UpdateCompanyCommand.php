<?php


namespace App\Company\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

/**
 * Class UpdateCompanyCommand
 * @package App\Company\Application\Command
 */
class UpdateCompanyCommand implements CommandInterface
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $description;

    /**
     * @var bool|null
     */
    private $active;

    /**
     * @var array|null
     */
    private $sla;

    /**
     * @var array|null
     */
    private $sections;

    /**
     * UpdateCompanyCommand constructor.
     * @param int $id
     * @param string|null $name
     * @param string|null $description
     * @param bool|null $active
     * @param array|null $sla
     * @param array|null $sections
     */
    public function __construct(int $id, ?string $name, ?string $description, ?bool $active, ?array $sla, ?array $sections)
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
        Assert::eq($data['url'], $data['id'], 'Id company not the same');

        if (key_exists('name', $data)) {
            Assert::stringNotEmpty($data['name'], 'Field name is empty');
        }
        if (key_exists('description', $data)) {
            Assert::stringNotEmpty($data['description'], 'Field description is empty');
        }
        if (key_exists('isActive', $data)) {
            Assert::boolean($data['isActive'], ' Field isActive is not a boolean');
        }

        if (key_exists('sla', $data)) {
            self::validateSla($data['sla']);

        }

        if (key_exists('sections', $data)) {
            self::validateSections($data['sections']);
        }

        return new self(
            $data['id'],
            $data['name'] ?? null,
            $data['description'] ?? null,
            $data['isActive'] ?? null,
            $data['sla'] ?? null,
            $data['sections'] ?? null
        );
    }

    /**
     * @param $sections
     */
    private static function validateSections($sections): void
    {
        Assert::isArray($sections, 'Field sections is not an array');

        foreach ($sections as $section) {
            if (key_exists('name', $section)) {
                Assert::stringNotEmpty($section['name'], 'Field section name is empty');
            }
        }
    }

    /**
     * @param $sla
     */
    private static function validateSla($sla): void
    {
        Assert::keyExists($sla, 'id', 'Field sla id is required');

        if (key_exists('p1', $sla)) {
            Assert::integer($sla['p1'], 'Field sla p1 is not a integer');
            Assert::notEq($sla['p1'], 0, 'Field sla p1 not be 0');
        }

        if (key_exists('p2', $sla)) {
            Assert::integer($sla['p2'], 'Field sla p2 is not a integer');
            Assert::notEq($sla['p2'], 0, 'Field sla p2 not be 0');
        }

        if (key_exists('p3', $sla)) {
            Assert::integer($sla['p3'], 'Field sla p3 is not a integer');
            Assert::notEq($sla['p3'], 0, 'Field sla p3 not be 0');
        }

        if (key_exists('p4', $sla)) {
            Assert::integer($sla['p4'], 'Field sla p4 is not a integer');
            Assert::notEq($sla['p4'], 0, 'Field sla p4 not be 0');
        }

        if (key_exists('p5', $sla)) {
            Assert::integer($sla['p5'], 'Field sla p5 is not a integer');
            Assert::notEq($sla['p5'], 0, 'Field sla p5 not be 0');
        }
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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @return array|null
     */
    public function getSla(): ?array
    {
        return $this->sla;
    }

    /**
     * @return array|null
     */
    public function getSections(): ?array
    {
        return $this->sections;
    }
}