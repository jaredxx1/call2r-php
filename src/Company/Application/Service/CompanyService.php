<?php


namespace App\Company\Application\Service;


use App\Company\Application\Command\CreateCompanyCommand;
use App\Company\Application\Command\UpdateCompanyCommand;
use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Application\Exception\NonUniqueMotherCompanyException;
use App\Company\Application\Exception\SlaNotFoundException;
use App\Company\Application\Query\FindCompanyByIdQuery;
use App\Company\Domain\Entity\Company;
use App\Company\Domain\Entity\SLA;
use App\Company\Domain\Repository\CompanyRepository;
use App\Company\Domain\Repository\SlaRepository;

final class CompanyService
{

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var $SlaRepository
     */
    private $slaRepository;

    /**
     * CompanyService constructor.
     * @param CompanyRepository $companyRepository
     * @param SlaRepository $slaRepository
     */
    public function __construct(
        CompanyRepository $companyRepository,
        SlaRepository $slaRepository
    )
    {
        $this->companyRepository = $companyRepository;
        $this->slaRepository = $slaRepository;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->companyRepository->getAll();
    }

    /**
     * @param CreateCompanyCommand $command
     * @return Company|null
     * @throws NonUniqueMotherCompanyException
     */
    public function create(CreateCompanyCommand $command): ?Company
    {
        $sla = new SLA(
            null,
            $command->sla()['p1'],
            $command->sla()['p2'],
            $command->sla()['p3'],
            $command->sla()['p4'],
            $command->sla()['p5']
        );

        $company = new Company(
            $command->name(),
            $command->cnpj(),
            $command->description(),
            $command->isMother(),
            $command->isActive(),
            $sla
        );
        if ($company->isMother()) {
            $motherCompany = $this->companyRepository->getMother();
            $sla->setP1(0);
            $sla->setP2(0);
            $sla->setP3(0);
            $sla->setP4(0);
            $sla->setP5(0);
            $company->setSla($sla);
            if (!is_null($motherCompany)) {
                throw new NonUniqueMotherCompanyException();
            }
        }
        $company = $this->companyRepository->create($company);
        return $company;
    }

    /**
     * @param FindCompanyByIdQuery $query
     * @return Company|null
     * @throws CompanyNotFoundException
     */
    public function fromId(FindCompanyByIdQuery $query): ?Company
    {
        $id = $query->id();
        $company = $this->companyRepository->fromId($id);

        if (empty($company)) {
            throw new CompanyNotFoundException();
        }

        return $company;
    }

    /**
     * @param UpdateCompanyCommand $command
     * @return Company|null
     * @throws CompanyNotFoundException
     */
    public function update(UpdateCompanyCommand $command): ?Company
    {
        $id = $command->id();
        $company = $this->companyRepository->fromId($id);
        $sla = $this->slaRepository->fromId($company->sla()->id());

        if (empty($company)) {
            throw new CompanyNotFoundException();
        }

        if(is_null($sla)){
            throw new SlaNotFoundException();
        }

        // Save sla
        $sla->setP1($command->sla()['p1']);
        $sla->setP2($command->sla()['p2']);
        $sla->setP3($command->sla()['p3']);
        $sla->setP4($command->sla()['p4']);
        $sla->setP5($command->sla()['p5']);

        $this->slaRepository->update($sla);

        // Save company
        $company->setName($command->name());
        $company->setDescription($command->description());
        $company->setActive($command->isActive());

        $this->companyRepository->update($company);

        return $company;
    }

    /**
     * @return Company|null
     * @throws CompanyNotFoundException
     */
    public function getMother(): ?Company
    {
        $company = $this->companyRepository->getMother();

        if (empty($company)) {
            throw new CompanyNotFoundException();
        }

        return $company;
    }
}