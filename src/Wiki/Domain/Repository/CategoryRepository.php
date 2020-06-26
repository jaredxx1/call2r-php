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
}