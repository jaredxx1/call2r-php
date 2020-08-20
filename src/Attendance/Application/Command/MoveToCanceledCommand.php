<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Application\Exception\UnauthorizedMoveToInAttendanceException;
use App\Attendance\Domain\Entity\Request;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Domain\Entity\User;
use Webmozart\Assert\Assert;

/**
 * Class MoveToCanceledCommand
 * @package App\Attendance\Application\Command
 */
class MoveToCanceledCommand implements CommandInterface
{
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
     * MoveToCanceledCommand constructor.
     * @param string|null $message
     * @param Request $request
     * @param User $user
     */
    public function __construct(?string $message, Request $request, User $user)
    {
        $this->message = $message;
        $this->request = $request;
        $this->user = $user;
    }


    /**
     * @param array $data
     * @return MoveToCanceledCommand
     * @throws UnauthorizedMoveToInAttendanceException
     */
    public static function fromArray($data)
    {
        if (key_exists('message', $data)) {
            Assert::stringNotEmpty($data['message'], 'Field message cannot be empty.');
        }

        self::validateRequest($data['request'], $data['user']);

        return new self(
            $data['message'] ?? null,
            $data[],
            $data[]
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @throws UnauthorizedMoveToInAttendanceException
     */
    private static function validateRequest(Request $request, User $user)
    {
        if ($request->getCompanyId() != $user->getCompanyId()) {
            throw new UnauthorizedMoveToInAttendanceException();
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
}