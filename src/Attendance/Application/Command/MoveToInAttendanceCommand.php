<?php


namespace App\Attendance\Application\Command;


use App\Attendance\Application\Exception\UnauthorizedMoveToCanceledException;
use App\Attendance\Domain\Entity\Request;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use App\User\Domain\Entity\User;
use Webmozart\Assert\Assert;

/**
 * Class MoveToInAttendanceCommand
 * @package App\Attendance\Application\Command
 */
class MoveToInAttendanceCommand implements CommandInterface
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
     * MoveToInAttendanceCommand constructor.
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
     * @return MoveToInAttendanceCommand
     * @throws UnauthorizedMoveToCanceledException
     */
    public static function fromArray($data)
    {
        if (key_exists('message', $data)) {
            Assert::stringNotEmpty($data['message'], 'Field message cannot be empty.');
        }

        if (key_exists('request', $data) && (key_exists('user', $data))) {
            self::validateRequest($data['request'], $data['user']);
        }

        return new self(
            $data['message'] ?? null,
            $data['request'],
            $data['user']
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @throws UnauthorizedMoveToCanceledException
     */
    private static function validateRequest(Request $request, User $user)
    {
        if ($request->getCompanyId() != $user->getCompanyId()) {
            throw new UnauthorizedMoveToCanceledException();
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