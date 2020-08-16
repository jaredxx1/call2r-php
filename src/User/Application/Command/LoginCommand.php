<?php


namespace App\User\Application\Command;


use App\Core\Infrastructure\Container\Application\Utils\Command\CommandInterface;
use Webmozart\Assert\Assert;

class LoginCommand implements CommandInterface
{

    /**
     * @var string
     */
    private $cpf;

    /**
     * @var string
     */
    private $password;

    /**
     * LoginCommand constructor.
     * @param string $cpf
     * @param string $password
     */
    public function __construct(string $cpf, string $password)
    {
        $this->cpf = $cpf;
        $this->password = $password;
    }


    public static function fromArray($data)
    {
        Assert::keyExists($data, 'cpf', 'Field cpf is required');
        Assert::keyExists($data, 'password', 'Field password is required');

        Assert::string($data['cpf'], 'Field cpf must be a string');
        Assert::string($data['password'], 'Field password must be a string');

        return new self(
            $data['cpf'],
            $data['password']
        );
    }

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
     * @param string $cpf
     */
    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}