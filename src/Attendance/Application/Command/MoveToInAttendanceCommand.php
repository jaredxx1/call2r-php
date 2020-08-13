<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Domain\Entity\Request;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Application\Exception\InvalidUserPrivileges;
use App\User\Domain\Entity\User;
use Webmozart\Assert\Assert;

class MoveToInAttendanceCommand   implements CommandInterface
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
     * MoveToInAttendanceCommand constructor.
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

        if(!self::validationMoveToInAttendance($data['request'], $data['user'])){
            throw new InvalidUserPrivileges();
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
    private static function validationMoveToInAttendance(Request $request, User $user)
    {
        if ($user->getRole() == User::support || $user->getRole() == User::manager) {
            return $request->getCompanyId() == $user->getCompanyId();
        }

        return false;
    }

    public function toArray(): array
    {
        return [];
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
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}