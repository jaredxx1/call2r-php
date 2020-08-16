<?php

namespace App\User\Domain\Entity;

use DateTime;
use JsonSerializable;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package App\User\Domain\Entity
 */
class User implements UserInterface, JsonSerializable
{
    const client = "ROLE_CLIENT";
    const support = "ROLE_USER";
    const manager = "ROLE_MANAGER";
    const admin = "ROLE_ADMIN";

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $cpf;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string|null
     */
    private $image;

    /**
     * @var Datetime
     */
    private $birthdate;

    /**
     * @var boolean
     */
    private $active;

    /**
     * @var int
     */
    private $companyId;

    /**
     * User constructor.
     * @param int|null $id
     * @param string $name
     * @param string $cpf
     * @param string $password
     * @param string $role
     * @param string $email
     * @param string|null $image
     * @param Datetime $birthdate
     * @param bool $active
     * @param int $companyId
     */
    public function __construct(?int $id, string $name, string $cpf, string $password, string $role, string $email, ?string $image, Datetime $birthdate, bool $active, int $companyId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->cpf = $cpf;
        $this->password = $password;
        $this->role = $role;
        $this->email = $email;
        $this->image = $image;
        $this->birthdate = $birthdate;
        $this->active = $active;
        $this->companyId = $companyId;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'cpf' => $this->getCpf(),
            'birthdate' => $this->getBirthdate(),
            'email' => $this->getEmail(),
            'image' => $this->getImage(),
            'companyId' => $this->getCompanyId(),
            'isActive' => $this->isActive(),
            'roles' => $this->getRoles(),
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * @return DateTime
     */
    public function getBirthdate(): DateTime
    {
        return $this->birthdate;
    }

    /**
     * @param DateTime $birthdate
     */
    public function setBirthdate(DateTime $birthdate): void
    {
        $this->birthdate = $birthdate;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return int|null
     */
    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    /**
     * @param int $companyId
     */
    public function setCompanyId(int $companyId): void
    {
        $this->companyId = $companyId;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this->role];
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

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}
