<?php

namespace App\Company\Infrastructure\Persistence\Doctrine\Repository;

use App\Company\Application\Exception\DuplicatedCompanyException;
use App\Company\Application\Exception\NonUniqueMotherCompanyException;
use App\Company\Domain\Entity\Company;
use App\Company\Domain\Repository\CompanyRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class DoctrineCompanyRepository
 * @package App\Company\Infrastructure\Persistence\Doctrine\Repository
 */
class DoctrineCompanyRepository implements CompanyRepository
{

    /**
     * @var ObjectRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * DoctrineCompanyRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $entityManager->getRepository(Company::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return Company|null
     */
    public function fromId(int $id): ?Company
    {
        return $this->repository->find($id);
    }

    /**
     * @return object[]
     */
    public function getAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @return Company|null
     * @throws NonUniqueMotherCompanyException
     */
    public function getMother(): ?Company
    {
        $query = $this->entityManager->createQuery('SELECT c FROM App\Company\Domain\Entity\Company c WHERE c.mother = TRUE');

        try {
            $company = $query->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            throw new NonUniqueMotherCompanyException();
        }

        return $company;
    }

    /**
     * @param Company $company
     * @return Company|null
     * @throws DuplicatedCompanyException
     */
    public function create(Company $company): ?Company
    {
        try {
            $this->entityManager->persist($company);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new DuplicatedCompanyException();
        }

        return $company;
    }

    /**
     * @param Company $company
     * @return Company|null
     */
    public function update(Company $company): ?Company
    {
        $this->entityManager->flush();

        return $company;
    }

    /**
     * @param int $sectionId
     * @return array|null
     */
    public function findCompaniesBySection(int $sectionId): ?array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('c')
            ->from('Company:Company', 'c')
            ->leftJoin('c.sections', 'section')
            ->where('section.id = :id')
            ->setParameter('id', $sectionId)
            ->getQuery()
            ->getResult();
    }
}