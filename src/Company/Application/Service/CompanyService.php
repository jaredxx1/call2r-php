<?php


namespace App\Company\Application\Service;


use App\Company\Application\Command\CreateCompanyCommand;
use App\Company\Application\Command\UpdateCompanyCommand;
use App\Company\Application\Exception\CompanyNotFoundException;
use App\Company\Application\Exception\NonUniqueMotherCompanyException;
use App\Company\Application\Exception\SlaNotFoundException;
use App\Company\Application\Query\FindCompanyByIdQuery;
use App\Company\Domain\Entity\Company;
use App\Company\Domain\Entity\Section;
use App\Company\Domain\Entity\SLA;
use App\Company\Domain\Repository\CompanyRepository;
use App\Company\Domain\Repository\SectionRepository;
use App\Company\Domain\Repository\SlaRepository;

/**
 * Class CompanyService
 * @package App\Company\Application\Service
 */
class CompanyService
{

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * @var SlaRepository
     */
    private $slaRepository;

    /**
     * @var SectionRepository
     */
    private $sectionRepository;

    /**
     * CompanyService constructor.
     * @param CompanyRepository $companyRepository
     * @param SlaRepository $slaRepository
     * @param SectionRepository $sectionRepository
     */
    public function __construct(
        CompanyRepository $companyRepository,
        SlaRepository $slaRepository,
        SectionRepository $sectionRepository
    )
    {
        $this->companyRepository = $companyRepository;
        $this->slaRepository = $slaRepository;
        $this->sectionRepository = $sectionRepository;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        $companies = $this->companyRepository->getAll();

        return $companies;
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

        foreach ($command->sections() as $section) {
            $foundSection = $this->sectionRepository->fromName($section['name']);
            if (is_null($foundSection)) {
                $sections[] = new Section(
                    null,
                    $section['name'],
                    $section['priority']
                );
            } else {
                $sections[] = $foundSection;
            }
        }

        $company = new Company(
            $command->name(),
            $command->cnpj(),
            $command->description(),
            $command->isMother(),
            $command->isActive(),
            $sla,
            $sections
        );

        if ($company->isMother()) {
            $motherCompany = $this->companyRepository->getMother();

            if (!is_null($motherCompany)) {
                throw new NonUniqueMotherCompanyException();
            }

            $sla->setP1(0);
            $sla->setP2(0);
            $sla->setP3(0);
            $sla->setP4(0);
            $sla->setP5(0);

            $company->setSla($sla);

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
     * @throws SlaNotFoundException
     */
    public function update(UpdateCompanyCommand $command): ?Company
    {

        $id = $command->id();
        $company = $this->companyRepository->fromId($id);
        $sla = $this->slaRepository->fromId($company->sla()->id());

        if (empty($company)) {
            throw new CompanyNotFoundException();
        }

        if (is_null($sla)) {
            throw new SlaNotFoundException();
        }

        foreach ($command->sections() as $section) {
            $foundSection = $this->sectionRepository->fromName($section['name']);
            if (is_null($foundSection)) {
                $sections[] = new Section(
                    null,
                    $section['name'],
                    $section['priority']
                );
            } else {
                $sections[] = $foundSection;
            }
        }

        // Save sla
        $sla->setP1($command->sla()['p1']);
        $sla->setP2($command->sla()['p2']);
        $sla->setP3($command->sla()['p3']);
        $sla->setP4($command->sla()['p4']);
        $sla->setP5($command->sla()['p5']);

        $this->slaRepository->update($sla);

        // set new sections to company

        $company->setSections($sections);

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