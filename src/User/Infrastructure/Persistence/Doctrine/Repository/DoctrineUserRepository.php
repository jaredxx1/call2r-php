<?php


namespace App\User\Infrastructure\Persistence\Doctrine\Repository;


use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class DoctrineUserRepository
 * @package App\User\Infrastructure\Persistence\Doctrine\Repository
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
     * @throws NonUniqueResultException
     */
    public function fromCpf(string $cpf): ?User
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\User\Domain\Entity\User', 'u')
            ->where('u.cpf = :cpf')
            ->setParameter('cpf', $cpf)
            ->getQuery()
            ->getOneOrNullResult();

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
                ->from('App\User\Domain\Entity\User', 'u')
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
            ->from('App\User\Domain\Entity\User', 'u')
            ->where('u.cpf=:cpf and u.birthdate=:birthdate')
            ->setParameter('cpf', $cpf)
            ->setParameter('birthdate', $birthdate)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return int|mixed|string
     */
    public function findClientManagers()
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\User\Domain\Entity\User', 'u')
            ->where('u.role = :role')
            ->setParameter('role', User::managerClient)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int|mixed|string
     */
    public function findSupportsManagers()
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\User\Domain\Entity\User', 'u')
            ->where('u.role = :role')
            ->setParameter('role', User::managerSupport)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int|mixed|string
     */
    public function findClient()
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\User\Domain\Entity\User', 'u')
            ->where('u.role = :role')
            ->setParameter('role', User::client)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $companyId
     * @return int|mixed|string
     */
    public function findSupport(int $companyId)
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\User\Domain\Entity\User', 'u')
            ->where('u.role = :role')
            ->andWhere('u.companyId = :companyId')
            ->setParameter('role', User::support)
            ->setParameter('companyId', $companyId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $email
     * @return User|null
     * @throws NonUniqueResultException
     */
    public function fromEmail(string $email): ?User
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\User\Domain\Entity\User', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }
}