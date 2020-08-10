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
use Doctrine\Common\Collections\ArrayCollection;

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
            $command->getSla()['p1'],
            $command->getSla()['p2'],
            $command->getSla()['p3'],
            $command->getSla()['p4'],
            $command->getSla()['p5']
        );

        $sections = $this->createSectionsObjects($command);

        $company = new Company(
            null,
            $command->getName(),
            $command->getDescription(),
            $command->getCnpj(),
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
     * @param mixed $command
     * @return ArrayCollection
     */
    private function createSectionsObjects($command): ArrayCollection
    {
        $sections = new ArrayCollection();
        $nameOfSections = new ArrayCollection();
        foreach ($command->getSections() as $section) {
            $foundSection = $this->sectionRepository->fromName($section['name']);
            if (is_null($foundSection)) {
                $localSection = new Section(
                    null,
                    $section['name']
                );
                if (!$nameOfSections->contains($localSection->getName())) {
                    $sections->add($localSection);
                    $nameOfSections->add($localSection->getName());
                }
            } else {
                $sections->add($foundSection);
            }
        }
        return $sections;
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
        $company = $this->companyRepository->fromId($command->getId());

        if (empty($company)) {
            throw new CompanyNotFoundException();
        }

        if (!is_null($command->getName())) {
            $company->setName($command->getName());
        }

        if (!is_null($command->getDescription())) {
            $company->setDescription($command->getDescription());
        }

        if (!is_null($command->getActive())) {
            $company->setActive($command->getActive());
        }

        if (!is_null($command->getSla())) {
            $sla = $this->slaRepository->fromId($company->getSla()->getId());
            if (is_null($sla)) {
                throw new SlaNotFoundException();
            }

            if (key_exists('p1', $command->getSla())) {
                $sla->setP1($command->getSla()['p1']);
            }

            if (key_exists('p2', $command->getSla())) {
                $sla->setP2($command->getSla()['p2']);
            }

            if (key_exists('p3', $command->getSla())) {
                $sla->setP3($command->getSla()['p3']);
            }

            if (key_exists('p4', $command->getSla())) {
                $sla->setP4($command->getSla()['p4']);
            }

            if (key_exists('p5', $command->getSla())) {
                $sla->setP5($command->getSla()['p5']);
            }

            $company->setSla($sla);
        }

        if (!is_null($command->getSections())) {
            $sections = $this->createSectionsObjects($command);
            $company->setSections($sections);
        }

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