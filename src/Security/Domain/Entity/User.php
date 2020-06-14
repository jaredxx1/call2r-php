<?php

namespace App\Security\Domain\Entity;

use JsonSerializable;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, JsonSerializable
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $cpf;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $roles = [];

    /**
     * User constructor.
     * @param int $id
     * @param string $cpf
     * @param string $password
     * @param array $roles
     */
    public function __construct(int $id, string $cpf, string $password, array $roles)
    {
        $this->id = $id;
        $this->cpf = $cpf;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'cpf' => $this->getCpf(),
            'roles' => $this->getRoles()
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): self
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->cpf;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed for apps that do not check user passwords
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
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
