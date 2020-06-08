<?php


namespace App\Company\Application\Service;


use App\Company\Domain\Repository\SectionRepository;

final class SectionService
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

    public function getAll() {
        return $this->sectionRepository->getAll();
    }


}