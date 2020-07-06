<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Domain\Entity\Request;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

/**
 * Class TransferCompanyCommand
 * @package App\Attendance\Application\Command
 */
class TransferCompanyCommand  implements CommandInterface
{

    /**
     * @var string
     */
    private $section;

    /**
     * @var integer
     */
    private $companyId;

    /**
     * @var integer
     */
    private $requestId;

    /**
     * TransferCompanyCommand constructor.
     * @param string $section
     * @param int $companyId
     * @param int $requestId
     */
    public function __construct(string $section, int $companyId, int $requestId)
    {
        $this->section = $section;
        $this->companyId = $companyId;
        $this->requestId = $requestId;
    }

    /**
     * @param array $data
     * @return TransferCompanyCommand
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'section', 'Field section is required.');
        Assert::keyExists($data, 'companyId', 'Field companyId is required.');
        Assert::keyExists($data, 'requestId', 'Field requestId is required.');

        Assert::stringNotEmpty($data['section'], 'Field section cannot be empty.');

        Assert::string($data['section'], 'Field section is not a string.');
        Assert::integer($data['companyId'], 'Field companyId not an integer.');
        Assert::integer($data['requestId'], 'Field requestId not an integer.');

        return new self(
            $data['section'],
            $data['companyId'],
            $data['requestId']
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
    public function getSection(): string
    {
        return $this->section;
    }

    /**
     * @return int
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    /**
     * @return int
     */
    public function getRequestId(): int
    {
        return $this->requestId;
    }
}