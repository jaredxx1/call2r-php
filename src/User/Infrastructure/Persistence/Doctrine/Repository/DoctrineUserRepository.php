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
     * @return array
     */
    public function findSupportUsers(User $user): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\User\Domain\Entity\User', 'u')
            ->where('u.role = :role')
            ->andWhere('u.companyId = :userRequestCompany')
            ->setParameter('role', 'ROLE_USER')
            ->setParameter('userRequestCompany', $user->getCompanyId())
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return array
     */
    public function findManagers(User $user): array
    {
        $query =  $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\User\Domain\Entity\User', 'u')
            ->where('u.role = :role')
            ->setParameter('role', 'ROLE_MANAGER');

        if($user->getRole() == 'ROLE_MANAGER'){
            $query->andWhere('u.companyId = :userRequestCompany')
                ->setParameter('userRequestCompany', $user->getCompanyId());
        }

        return $query->getQuery()->getResult();
    }

    public function findAdmins(User $user): array
    {
        return $this->entityManager
        ->createQueryBuilder()
        ->select('u')
        ->from('App\User\Domain\Entity\User', 'u')
        ->where('u.role = :role')
        ->setParameter('role', 'ROLE_ADMIN')
        ->getQuery()
        ->getResult();
    }


    public function findClientUsers(User $user): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from('App\User\Domain\Entity\User', 'u')
            ->where('u.role = :role')
            ->setParameter('role', 'ROLE_CLIENT')
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
            ->from('App\User\Domain\Entity\User', 'u')
            ->where('u.cpf=:cpf and u.birthdate=:birthdate')
            ->setParameter('cpf', $cpf)
            ->setParameter('birthdate', $birthdate)
            ->getQuery()
            ->getOneOrNullResult();
    }
}