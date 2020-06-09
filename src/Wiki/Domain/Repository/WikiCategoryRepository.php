<?php


namespace App\Wiki\Domain\Repository;


use App\Wiki\Domain\Entity\WikiCategory;

interface WikiCategoryRepository
{
    public function getAll();

    public function create(WikiCategory $wikiCategory): ?WikiCategory;
}