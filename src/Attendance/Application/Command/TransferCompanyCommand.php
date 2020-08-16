<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Application\Exception\TransferRequestException;
use App\Attendance\Domain\Entity\Request;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Application\Exception\InvalidUserPrivileges;
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
     * @var User
     */
    private $user;

    /**
     * @var Request
     */
    private $oldRequest;

    /**
     * TransferCompanyCommand constructor.
     * @param string $section
     * @param int $companyId
     * @param int $requestId
     * @param string|null $message
     * @param User $user
     * @param Request $oldRequest
     */
    public function __construct(string $section, int $companyId, int $requestId, ?string $message, User $user, Request $oldRequest)
    {
        $this->section = $section;
        $this->companyId = $companyId;
        $this->requestId = $requestId;
        $this->message = $message;
        $this->user = $user;
        $this->oldRequest = $oldRequest;
    }

    /**
     * @param array $data
     * @return TransferCompanyCommand
     * @throws InvalidUserPrivileges
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

        if(!self::validationTransferCompany($data['oldRequest'], $data['user'])){
            throw new TransferRequestException();
        }

        return new self(
            $data['section'],
            $data['companyId'],
            $data['requestId'],
            $data['message'] ?? null,
            $data['user'],
            $data['oldRequest']
        );
    }

    /**
     * @param Request $oldRequest
     * @param User $user
     * @return bool
     */
    private static function validationTransferCompany(Request $oldRequest, User $user)
    {
        if($user->getRole() == User::manager){
            return ($oldRequest->getCompanyId() == $user->getCompanyId());
        }

        return false;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Request
     */
    public function getOldRequest(): Request
    {
        return $this->oldRequest;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}