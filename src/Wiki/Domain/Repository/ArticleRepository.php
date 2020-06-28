<?php

namespace App\Wiki\Domain\Repository;

use App\Wiki\Domain\Entity\Article;

/**
 * Interface ArticleRepository
 * @package App\Wiki\Domain\Repository
 */
interface ArticleRepository
{
    /**
     * @param int $id
     * @return mixed
     */
    public function fromCompany(int $id);

    /**
     * @param int $id
     * @return Article|null
     */
    public function fromId(int $id): ?Article;

    public function create(Article $article): ?Article;

    /**
     * @param Article $article
     * @return Article|null
     */
    public function update(Article $article): ?Article;

    /**
     * @param Article $article
     * @return mixed
     */
    public function delete(Article $article);
}