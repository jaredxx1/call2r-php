<?php


namespace App\Company\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class UpdateSlaCommand implements CommandInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $p1;

    /**
     * @var int
     */
    private $p2;

    /**
     * @var int
     */
    private $p3;

    /**
     * @var int
     */
    private $p4;

    /**
     * @var int
     */
    private $p5;

    /**
     * UpdateSlaCommand constructor.
     * @param int $id
     * @param int $p1
     * @param int $p2
     * @param int $p3
     * @param int $p4
     * @param int $p5
     */
    public function __construct(int $id, int $p1, int $p2, int $p3, int $p4, int $p5)
    {
        $this->id = $id;
        $this->p1 = $p1;
        $this->p2 = $p2;
        $this->p3 = $p3;
        $this->p4 = $p4;
        $this->p5 = $p5;
    }

    /**
     * @param array $data
     * @return UpdateSlaCommand
     */
    public static function fromArray($data)
    {

        Assert::keyExists($data, 'p1', 'Field sla p1 is required');
        Assert::keyExists($data, 'p2', 'Field sla p2 is required');
        Assert::keyExists($data, 'p3', 'Field sla p3 is required');
        Assert::keyExists($data, 'p4', 'Field sla p4 is required');
        Assert::keyExists($data, 'p5', 'Field sla p5 is required');

        Assert::integer($data['p1'], 'Field sla p1 is not a integer');
        Assert::integer($data['p2'], 'Field sla p2 is not a integer');
        Assert::integer($data['p3'], 'Field sla p3 is not a integer');
        Assert::integer($data['p4'], 'Field sla p4 is not a integer');
        Assert::integer($data['p5'], 'Field sla p5 is not a integer');

        Assert::notEq($data['p1'], 0, 'Field sla p1 not be 0');
        Assert::notEq($data['p2'], 0, 'Field sla p2 not be 0');
        Assert::notEq($data['p3'], 0, 'Field sla p3 not be 0');
        Assert::notEq($data['p4'], 0, 'Field sla p4 not be 0');
        Assert::notEq($data['p5'], 0, 'Field sla p5 not be 0');

        return new self(
            $data['id'],
            $data['p1'],
            $data['p2'],
            $data['p3'],
            $data['p4'],
            $data['p5']
        );
    }

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
     * @return int
     */
    public function p1(): int
    {
        return $this->p1;
    }

    /**
     * @return int
     */
    public function p2(): int
    {
        return $this->p2;
    }

    /**
     * @return int
     */
    public function p3(): int
    {
        return $this->p3;
    }

    /**
     * @return int
     */
    public function p4(): int
    {
        return $this->p4;
    }

    /**
     * @return int
     */
    public function p5(): int
    {
        return $this->p5;
    }
}