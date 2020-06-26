<?php


namespace App\Wiki\Application\Service;


use App\Wiki\Application\Command\UpdateCategoryCommand;
use App\Wiki\Application\Exception\CategoryNotFoundException;
use App\Wiki\Application\Query\FindAllCategoriesFromCompanyQuery;
use App\Wiki\Domain\Repository\CategoryRepository;

class CategoryService
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryService constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param FindAllCategoriesFromCompanyQuery $query
     * @return \App\Wiki\Domain\Entity\Category|null
     */
    public function getAll(FindAllCategoriesFromCompanyQuery $query){
        return $this->categoryRepository->fromCompany($query->id());
    }

    public function update(UpdateCategoryCommand $command){
        $category = $this->categoryRepository->fromId($command->id());

        if(is_null($category)){
            throw new CategoryNotFoundException();
        }

        $category->setTitle($command->title());
        $category->setActive($command->active());

        return $this->categoryRepository->update($category);
    }
}