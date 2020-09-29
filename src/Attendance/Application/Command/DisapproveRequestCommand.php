<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Application\Exception\UnauthorizedDisapproveRequestException;
use App\Attendance\Domain\Entity\Request;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Domain\Entity\User;
use Webmozart\Assert\Assert;

/**
 * Class DisapproveRequestCommand
 * @package App\Attendance\Application\Command
 */
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
     * @var Request
     */
    private $request;

    /**
     * @var User
     */
    private $user;

    /**
     * DisapproveRequestCommand constructor.
     * @param int $requestId
     * @param string $message
     * @param Request $request
     * @param User $user
     */
    public function __construct(int $requestId, string $message, Request $request, User $user)
    {
        $this->requestId = $requestId;
        $this->message = $message;
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * @param array $data
     * @return DisapproveRequestCommand
     * @throws UnauthorizedDisapproveRequestException
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'requestId', 'Field requestId is required.');
        Assert::keyExists($data, 'message', 'Field message is required.');

        Assert::stringNotEmpty($data['message'], 'Field message cannot be empty.');

        Assert::string($data['message'], 'Field message is not a string.');
        Assert::integer($data['requestId'], 'Field requestId not an integer.');

        if(key_exists('request', $data) && (key_exists('user', $data))){
            self::validateRequest($data['request'], $data['user']);
        }

        return new self(
            $data['requestId'],
            $data['message'],
            $data['request'],
            $data['user']
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @throws UnauthorizedDisapproveRequestException
     */
    private static function validateRequest(Request $request, User $user)
    {
        if(!($user->getRole() == User::managerClient)) {
            if ($request->getRequestedBy() != $user->getId()) {
                throw new UnauthorizedDisapproveRequestException();
            }
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
}