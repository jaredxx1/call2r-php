<?php

namespace App\Wiki\Domain\Repository;

use App\Wiki\Domain\Entity\Article;

interface ArticleRepository
{
    public function fromCompany(int $id);

    public function fromId(int $id): ?Article;

    public function create(Article $article): ?Article;

    public function update(Article $article): ?Article;

    public function delete(Article $article);
}