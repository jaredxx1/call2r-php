<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Application\Exception\UnauthorizedTransferCompanyException;
use App\Attendance\Domain\Entity\Request;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Domain\Entity\User;
use Webmozart\Assert\Assert;

/**
 * Class TransferCompanyCommand
 * @package App\Attendance\Application\Command
 */
class TransferCompanyCommand implements CommandInterface
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
     * @var string|null
     */
    private $message;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var User
     */
    private $user;

    /**
     * TransferCompanyCommand constructor.
     * @param string $section
     * @param int $companyId
     * @param int $requestId
     * @param string|null $message
     * @param Request $request
     * @param User $user
     */
    public function __construct(string $section, int $companyId, int $requestId, ?string $message, Request $request, User $user)
    {
        $this->section = $section;
        $this->companyId = $companyId;
        $this->requestId = $requestId;
        $this->message = $message;
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * @param array $data
     * @return TransferCompanyCommand
     * @throws UnauthorizedTransferCompanyException
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

        if (key_exists('message', $data)) {
            Assert::stringNotEmpty($data['message'], 'Field message cannot be empty.');
        }

        self::validateRequest($data['request'], $data['user']);

        return new self(
            $data['section'],
            $data['companyId'],
            $data['requestId'],
            $data['message'] ?? null,
            $data[],
            $data[]
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @throws UnauthorizedTransferCompanyException
     */
    private static function validateRequest(Request $request, User $user)
    {
        if (!(
            ($request->getCompanyId() == $user->getCompanyId()) &&
            ($request->getAssignedTo() == $user->getId())
        )) {
            throw new UnauthorizedTransferCompanyException();
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

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}