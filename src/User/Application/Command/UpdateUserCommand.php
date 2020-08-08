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
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var string
     */
    private $role;

    /**
     * UpdateUserCommand constructor.
     * @param int $id
     * @param string $name
     * @param string $password
     * @param string $email
     * @param bool $active
     * @param string $role
     */
    public function __construct(int $id, string $name, string $password, string $email, bool $active, string $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->password = $password;
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

        if (key_exists('password', $data)) {
            Assert::stringNotEmpty($data['password'], 'Field password cannot be empty');
        }

        if (key_exists('isActive', $data)) {
            Assert::boolean($data['isActive'], 'Field isActive is not a boolean');
        }

        if (key_exists('role', $data)) {
            Assert::oneOf($data['role'], ['ROLE_MANAGER', 'ROLE_USER', 'ROLE_ADMIN'], 'Field role is neither admin neither manager neither user');
        }

        return new self(
            $data['id'],
            $data['name'] ?? null,
            $data['password'] ?? null,
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }
}