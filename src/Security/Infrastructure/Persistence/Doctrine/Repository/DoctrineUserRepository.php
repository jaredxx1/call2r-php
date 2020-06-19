<?php


namespace App\Security\Infrastructure\Persistence\Doctrine\Repository;


use App\Security\Domain\Entity\User;
use App\Security\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

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

    public function fromId(int $id): ?User
    {
        return $this->entityManager
            ->getRepository(User::class)
            ->find($id);
    }

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
}