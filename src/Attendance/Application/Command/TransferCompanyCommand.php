<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Domain\Entity\Request;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

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
     * @var Request
     */
    private $request;

    /**
     * TransferCompanyCommand constructor.
     * @param string $section
     * @param int $companyId
     * @param Request $request
     */
    public function __construct(string $section, int $companyId, Request $request)
    {
        $this->section = $section;
        $this->companyId = $companyId;
        $this->request = $request;
    }


    public static function fromArray($data)
    {
        Assert::keyExists($data, 'section', 'Field section is required.');
        Assert::keyExists($data, 'companyId', 'Field companyId is required.');

        Assert::stringNotEmpty($data['section'], 'Field section cannot be empty.');

        Assert::string($data['section'], 'Field section is not a string.');
        Assert::integer($data['companyId'], 'Field companyId not an integer.');

        return new self(
            $data['section'],
            $data['companyId'],
            $data['request']
        );
    }

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
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}