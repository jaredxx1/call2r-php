<?php


namespace App\Security\Infrastructure\Persistence\Doctrine\Repository;


use App\Security\Domain\Entity\User;
use App\Security\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class DoctrineUserRepository
 * @package App\Security\Infrastructure\Persistence\Doctrine\Repository
 */
class DoctrineUserRepository implements UserRepository
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * DoctrineUserRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @param string $cpf
     * @return User|null
     */
    public function fromCpf(string $cpf): ?User
    {
        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('u')
                ->from('App\Security\Domain\Entity\User', 'u')
                ->where('u.cpf = :cpf')
                ->setParameter('cpf', $cpf)
                ->getQuery()
                ->getOneOrNullResult();

        } catch (NonUniqueResultException $e) {
        }

    }

    /**
     * @param string $cpf
     * @param string $password
     * @return User|null
     */
    public function fromLoginCredentials(string $cpf, string $password): ?User
    {
        try {
            return $this->entityManager
                ->createQueryBuilder()
                ->select('u')
                ->from('App\Security\Domain\Entity\User', 'u')
                ->where('u.cpf = :cpf AND u.password = :password')
                ->setParameter('cpf', $cpf)
                ->setParameter('password', $password)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function fromId(int $id): ?User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->find($id);
    }

    /**
     * @return array
     */
    public function findSupportUsers(): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\Security\Domain\Entity\User', 'u')
            ->where('u.role = :role')
            ->setParameter('role', 'ROLE_USER')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function findManagers(): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\Security\Domain\Entity\User', 'u')
            ->where('u.role = :role')
            ->setParameter('role', 'ROLE_MANAGER')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return User|null
     */
    public function createUser(User $user): ?User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * @param User $user
     * @return User
     */
    public function updateUser(User $user): User
    {
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @param string $cpf
     * @param $birthdate
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function fromCpfBirthdate(string $cpf, $birthdate): ?User
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\Security\Domain\Entity\User', 'u')
            ->where('u.cpf=:cpf and u.birthdate=:birthdate')
            ->setParameter('cpf', $cpf)
            ->setParameter('birthdate', $birthdate)
            ->getQuery()
            ->getOneOrNullResult();
    }
}