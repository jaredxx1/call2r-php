<?php

namespace App\Wiki\Domain\Repository;

use App\Wiki\Domain\Entity\Article;

interface ArticleRepository
{
    public function fromCompany(int $id);

    public function create(Article $article): ?Article;
}