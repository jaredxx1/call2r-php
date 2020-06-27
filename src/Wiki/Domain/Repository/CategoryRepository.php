<?php


namespace App\Wiki\Domain\Repository;


use App\Wiki\Domain\Entity\Category;

/**
 * Interface CategoryRepository
 * @package App\Wiki\Domain\Repository
 */
interface CategoryRepository
{
    /**
     * @param string $title
     * @return Category|null
     */
    public function fromTitle(string $title): ?Category;

    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param int $id
     * @return Category|null
     */
    public function fromId(int $id): ?Category;

    /**
     * @param Category $category
     * @return Category|null
     */
    public function update(Category $category): ?Category;

    /**
     * @param int $id
     * @return Category|null
     */
    public function fromCompany(int $id);

    /**
     * @param string $title
     * @param int $idCompany
     * @return Category|null
     */
    public function fromArticleTitle(string $title, int $idCompany): ?Category;

    /**
     * @param Category $category
     * @return mixed
     */
    public function delete(Category $category);
}