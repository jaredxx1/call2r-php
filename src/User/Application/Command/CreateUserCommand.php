<?php


namespace App\User\Application\Command;


use App\Core\Infrastructure\Container\Application\Exception\InvalidDateFormatException;
use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Carbon\Carbon;
use Exception;
use Webmozart\Assert\Assert;

/**
 * Class CreateUserCommand
 * @package App\User\Application\Command
 */
class CreateUserCommand implements CommandInterface
{

    /**
     * @var string
     */
    private $cpf;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $birthdate;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var int
     */
    private $companyId;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var string
     */
    private $role;

    /**
     * CreateUserCommand constructor.
     * @param string $cpf
     * @param string $name
     * @param string $birthdate
     * @param string $email
     * @param string $password
     * @param int $companyId
     * @param bool $active
     * @param string $role
     */
    public function __construct(string $cpf, string $name, string $birthdate, string $email, string $password, int $companyId, bool $active, string $role)
    {
        $this->cpf = $cpf;
        $this->name = $name;
        $this->birthdate = $birthdate;
        $this->email = $email;
        $this->password = $password;
        $this->companyId = $companyId;
        $this->active = $active;
        $this->role = $role;
    }


    /**
     * @param array $data
     * @return CreateUserCommand
     * @throws InvalidDateFormatException
     */
    public static function fromArray($data)
    {
        Assert::keyExists($data, 'cpf', 'Field cpf is required');
        Assert::keyExists($data, 'name', 'Field name is required');
        Assert::keyExists($data, 'birthdate', 'Field birthdate is required');
        Assert::keyExists($data, 'email', 'Field email is required');
        Assert::keyExists($data, 'password', 'Field password is required');
        Assert::keyExists($data, 'companyId', 'Field companyId is required');
        Assert::keyExists($data, 'isActive', 'Field isActive is required');
        Assert::keyExists($data, 'role', 'Field role is required');

        try {
            $birthdate = Carbon::createFromFormat('Y-m-d', $data['birthdate']);
            $data['birthdate'] = $birthdate;
        } catch (Exception $e) {
            throw new InvalidDateFormatException();
        }

        Assert::stringNotEmpty($data['cpf'], 'Field cpf cannot be empty');
        Assert::stringNotEmpty($data['name'], 'Field name cannot be empty');
        Assert::stringNotEmpty($data['email'], 'Field email cannot be empty');
        Assert::stringNotEmpty($data['password'], 'Field password cannot be empty');
        Assert::integer($data['companyId'], 'Field companyId is not an integer');
        Assert::boolean($data['isActive'], 'Field isActive is not a boolean');

        Assert::oneOf($data['role'], ['ROLE_MANAGER', 'ROLE_USER', 'ROLE_ADMIN'], 'Field role is neither admin neither manager neither user');

        return new self(
            $data['cpf'],
            $data['name'],
            $data['birthdate'],
            $data['email'],
            $data['password'],
            $data['companyId'],
            $data['isActive'],
            $data['role']
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
     * @return string
     */
    public function getCpf(): string
    {
        return $this->cpf;
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
    public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getCompanyId(): int
    {
        return $this->companyId;
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