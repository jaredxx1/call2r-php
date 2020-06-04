<?php

namespace App\Company\Infrastructure\Persistence\Doctrine\Repository;

use App\Company\Domain\Entity\Company;
use App\Company\Domain\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

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

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $entityManager->getRepository(Company::class);
        $this->entityManager = $entityManager;
    }

    public function fromId(int $id)
    {
        // TODO: Implement fromId() method.
    }

    public function getAll()
    {
        return $this->repository->findAll();
    }

    public function getMother()
    {
        // TODO: Implement getMother() method.
    }

    public function create(Company $company)
    {
        $this->entityManager->persist($company);
        $this->entityManager->flush();
    }
}