<?php


namespace App\Company\Application\Service;


use App\Company\Application\Command\UpdateSectionCommand;
use App\Company\Application\Exception\SectionNotFoundException;
use App\Company\Domain\Entity\Section;
use App\Company\Domain\Repository\CompanyRepository;
use App\Company\Domain\Repository\SectionRepository;
use App\User\Domain\Entity\User;

/**
 * Class SectionService
 * @package App\Company\Application\Service
 */
class SectionService
{

    /**
     * @var SectionRepository
     */
    private $sectionRepository;

    /**
     * @var CompanyRepository
     */
    private $companyRepository;

    /**
     * SectionService constructor.
     * @param SectionRepository $sectionRepository
     * @param CompanyRepository $companyRepository
     */
    public function __construct(SectionRepository $sectionRepository, CompanyRepository $companyRepository)
    {
        $this->sectionRepository = $sectionRepository;
        $this->companyRepository = $companyRepository;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->sectionRepository->getAll();
    }

    /**
     * @param UpdateSectionCommand $command
     * @param User $user
     * @return Section|null
     * @throws SectionNotFoundException
     */
    public function update(UpdateSectionCommand $command, User $user)
    {
        $section = $this->sectionRepository->fromId($command->id());
        $company = $this->companyRepository->fromId($user->getCompanyId());

        if (is_null($section)) {
            throw new SectionNotFoundException();
        }

        $section->setName($command->name());
        return $this->sectionRepository->update($section);
    }

}