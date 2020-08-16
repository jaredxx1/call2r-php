<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Application\Exception\ApproveRequestException;
use App\Attendance\Application\Exception\DisapproveRequestException;
use App\Attendance\Domain\Entity\Request;
use App\Company\Application\Service\CompanyService;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Domain\Entity\User;
use Webmozart\Assert\Assert;

class DisapproveRequestCommand implements CommandInterface
{

    /**
     * @var int
     */
    private $requestId;

    /**
     * @var string
     */
    private $message;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Request
     */
    private $request;

    /**
     * DisapproveRequestCommand constructor.
     * @param int $requestId
     * @param string $message
     * @param User $user
     * @param Request $request
     */
    public function __construct(int $requestId, string $message, User $user, Request $request)
    {
        $this->requestId = $requestId;
        $this->message = $message;
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * @param array $data
     * @return DisapproveRequestCommand
     * @throws DisapproveRequestException
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'requestId', 'Field requestId is required.');
        Assert::keyExists($data, 'message', 'Field message is required.');

        Assert::stringNotEmpty($data['message'], 'Field message cannot be empty.');

        Assert::string($data['message'], 'Field message is not a string.');
        Assert::integer($data['requestId'], 'Field requestId not an integer.');

        if(!self::validationDisapproveRequest($data['request'], $data['user'])){
            throw new DisapproveRequestException();
        }

        return new self(
            $data['requestId'],
            $data['message'],
            $data['user'],
            $data['request']
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @return bool
     */
    private static function validationDisapproveRequest(Request $request, User $user)
    {
        if($user->getRole() == User::client){
            return $user->getId() == $request->getRequestedBy();
        }

        if($user->getRole() == User::manager){
            return $user->getCompanyId() == CompanyService::motherId;
        }

        return false;
    }

    public function toArray(): array
    {
        return [];
    }

    /**
     * @return int
     */
    public function getRequestId(): int
    {
        return $this->requestId;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
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
    public function getRequest(): Request
    {
        return $this->request;
    }

}