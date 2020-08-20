<?php


namespace App\User\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

/**
 * Class CreateUserCommand
 * @package App\User\Application\Command
 */
class UpdateUserCommand implements CommandInterface
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $newPassword;

    /**
     * @var string|null
     */
    private $oldPassword;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var boolean|null
     */
    private $active;

    /**
     * @var string|null
     */
    private $role;

    /**
     * UpdateUserCommand constructor.
     * @param int $id
     * @param string|null $name
     * @param string|null $newPassword
     * @param string|null $oldPassword
     * @param string|null $email
     * @param bool|null $active
     * @param string|null $role
     */
    public function __construct(int $id, ?string $name, ?string $newPassword, ?string $oldPassword, ?string $email, ?bool $active, ?string $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->newPassword = $newPassword;
        $this->oldPassword = $oldPassword;
        $this->email = $email;
        $this->active = $active;
        $this->role = $role;
    }

    /**
     * @param array $data
     * @return UpdateUserCommand
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'id', 'Param id is required');
        Assert::integer($data['id'], 'Field id must be an integer');

        if (key_exists('name', $data)) {
            Assert::stringNotEmpty($data['name'], 'Field name cannot be empty');
        }

        if (key_exists('email', $data)) {
            Assert::stringNotEmpty($data['email'], 'Field email cannot be empty');
        }

        if (key_exists('newPassword', $data)) {
            Assert::stringNotEmpty($data['newPassword'], 'Field password cannot be empty');
        }

        if (key_exists('oldPassword', $data)) {
            Assert::stringNotEmpty($data['oldPassword'], 'Field password cannot be empty');
        }

        if (key_exists('isActive', $data)) {
            Assert::boolean($data['isActive'], 'Field isActive is not a boolean');
        }

        if (key_exists('role', $data)) {
            Assert::oneOf($data['role'], ['ROLE_SUPPORT', 'ROLE_CLIENT', 'ROLE_MANAGER_CLIENT', 'ROLE_MANAGER_SUPPORT', 'ROLE_ADMIN'], 'Field role is neither admin neither manager neither user');
        }
        return new self(
            $data['id'],
            $data['name'] ?? null,
            $data['newPassword'] ?? null,
            $data['oldPassword'] ?? null,
            $data['email'] ?? null,
            $data['isActive'] ?? null,
            $data['role'] ?? null
        );
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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    /**
     * @return string|null
     */
    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }
}