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
     * @var integer
     */
    private $sectionId;

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
     * @param int $sectionId
     * @param int $companyId
     * @param int $requestId
     * @param string|null $message
     * @param Request $request
     * @param User $user
     */
    public function __construct(int $sectionId, int $companyId, int $requestId, ?string $message, Request $request, User $user)
    {
        $this->sectionId = $sectionId;
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
        Assert::keyExists($data, 'sectionId', 'Field sectionId is required.');
        Assert::keyExists($data, 'companyId', 'Field companyId is required.');
        Assert::keyExists($data, 'requestId', 'Field requestId is required.');

        Assert::integer($data['sectionId'], 'Field sectionId is not a string.');
        Assert::integer($data['companyId'], 'Field companyId not an integer.');
        Assert::integer($data['requestId'], 'Field requestId not an integer.');

        if (key_exists('message', $data)) {
            Assert::stringNotEmpty($data['message'], 'Field message cannot be empty.');
        }

        if(key_exists('request', $data) && (key_exists('user', $data))){
            self::validateRequest($data['request'], $data['user']);
        }

        return new self(
            $data['sectionId'],
            $data['companyId'],
            $data['requestId'],
            $data['message'] ?? null,
            $data['request'],
            $data['user']
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
     * @return int
     */
    public function getSectionId(): int
    {
        return $this->sectionId;
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
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

}