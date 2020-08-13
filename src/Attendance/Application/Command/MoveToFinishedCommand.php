<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Application\Exception\AnsweredResponseException;
use App\Attendance\Application\Exception\MoveToFinishedException;
use App\Attendance\Domain\Entity\Request;
use App\Company\Application\Service\CompanyService;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Domain\Entity\User;
use Webmozart\Assert\Assert;

/**
 * Class MoveToFinishedCommand
 * @package App\Attendance\Application\Command
 */
class MoveToFinishedCommand implements CommandInterface
{
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
    private $request;

    /**
     * MoveToFinishedCommand constructor.
     * @param string|null $message
     * @param User $user
     * @param Request $request
     */
    public function __construct(?string $message, User $user, Request $request)
    {
        $this->message = $message;
        $this->user = $user;
        $this->request = $request;
    }

    public static function fromArray($data)
    {
        if (key_exists('message', $data)) {
            Assert::stringNotEmpty($data['message'], 'Field message cannot be empty.');
        }

        if(!self::validationMoveToFinished($data['request'], $data['user'])){
            throw new MoveToFinishedException();
        }

        return new self(
            $data['message'] ?? null,
            $data['user'],
            $data['request']
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @return bool
     */
    private static function validationMoveToFinished(Request $request, User $user)
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