<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Application\Exception\MoveToFinishedException;
use App\Attendance\Application\Exception\SupportApproveException;
use App\Attendance\Domain\Entity\Request;
use App\Company\Application\Service\CompanyService;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Domain\Entity\User;
use Webmozart\Assert\Assert;

class SupportApproveCommand  implements CommandInterface
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
     * SupportApproveCommand constructor.
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

    /**
     * @param array $data
     * @return SupportApproveCommand
     * @throws SupportApproveException
     */
    public static function fromArray($data)
    {
        if (key_exists('message', $data)) {
            Assert::stringNotEmpty($data['message'], 'Field message cannot be empty.');
        }

        if(!self::validationSupportApprove($data['request'], $data['user'])){
            throw new SupportApproveException();
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
    private static function validationSupportApprove(Request $request, User $user)
    {
        if(($user->getRole() == User::support) || ($user->getRole() == User::manager)){
            return ($request->getCompanyId() == $user->getCompanyId())
                && ($request->getAssignedTo() == $user->getId());
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