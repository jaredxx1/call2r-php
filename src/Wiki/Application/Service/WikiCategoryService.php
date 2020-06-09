<?php


namespace App\Wiki\Application\Service;


use App\Wiki\Application\Command\CreateWikiCategoryCommand;
use App\Wiki\Domain\Entity\WikiCategory;
use App\Wiki\Domain\Repository\WikiCategoryRepository;

class WikiCategoryService
{
    /**
     * @var WikiCategoryRepository
     */
    private $wikiRepository;

    /**
     * CompanyService constructor.
     * @param WikiCategoryRepository $wikiRepository
     */
    public function __construct(
        WikiCategoryRepository $wikiRepository
    )
    {
        $this->wikiRepository = $wikiRepository;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->wikiRepository->getAll();
    }

    /**
     * @param CreateWikiCategoryCommand $command
     * @return WikiCategory|null
     */
    public function create(CreateWikiCategoryCommand $command){
        $wikiCategory = new WikiCategory(
            null,
            $command->title(),
            $command->active()
        );

        return $this->wikiRepository->create($wikiCategory);
    }
}