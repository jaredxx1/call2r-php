<?php


namespace App\Company\Application\Service;


use App\Company\Application\Command\CreateCompanyCommand;
use App\Company\Application\Command\UpdateCompanyCommand;
use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Application\Exception\NonUniqueMotherCompanyException;
use App\Company\Application\Query\FindCompanyByIdQuery;
use App\Company\Domain\Entity\Company;
use App\Company\Domain\Repository\CompanyRepository;

final class CompanyService
{

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    public function __construct(
        CompanyRepository $companyRepository
    )
    {
        $this->companyRepository = $companyRepository;
    }


    public function getAll()
    {
        return $this->companyRepository->getAll();
    }

    public function create(CreateCompanyCommand $command): ?Company
    {
        $company = new Company(
            $command->name(),
            $command->cnpj(),
            $command->description(),
            $command->isMother(),
            $command->isActive()
        );

        if ($company->isMother()) {
            $motherCompany = $this->companyRepository->getMother();

            if (!is_null($motherCompany)) {
                throw new NonUniqueMotherCompanyException();
            }
        }

        $company = $this->companyRepository->create($company);

        return $company;
    }

    public function fromId(FindCompanyByIdQuery $query): ?Company
    {
        $id = $query->id();
        $company = $this->companyRepository->fromId($id);

        if (empty($company)) {
            throw new CompanyNotFoundException();
        }

        return $company;
    }


    public function update(UpdateCompanyCommand $command): ?Company
    {
        $id = $command->id();
        $company = $this->companyRepository->fromId($id);

        if (empty($company)) {
            throw new CompanyNotFoundException();
        }

        $company->setName($command->name());
        $company->setDescription($command->description());
        $company->setActive($command->isActive());

        $this->companyRepository->update($company);

        return $company;
    }

    public function getMother(): ?Company
    {
        $company = $this->companyRepository->getMother();

        if (empty($company)) {
            throw new CompanyNotFoundException();
        }

        return $company;
    }
}