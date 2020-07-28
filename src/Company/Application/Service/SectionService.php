<?php


namespace App\Company\Application\Service;


use App\Company\Application\Command\UpdateSectionCommand;
use App\Company\Application\Exception\SectionNotFoundException;
use App\Company\Domain\Entity\Section;
use App\Company\Domain\Repository\SectionRepository;

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
     * SectionService constructor.
     * @param SectionRepository $sectionRepository
     */
    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
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
     * @return Section|null
     * @throws SectionNotFoundException
     */
    public function update(UpdateSectionCommand $command)
    {
        $section = $this->sectionRepository->fromId($command->id());

        // Not found
        if (is_null($section)) {
            throw new SectionNotFoundException();
        }

        $section->setName($command->name());
        return $this->sectionRepository->update($section);
    }

}