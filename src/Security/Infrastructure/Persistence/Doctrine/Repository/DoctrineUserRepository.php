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
}