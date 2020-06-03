<?php


namespace App\Blog\Domain\Respository;


interface PostRepository
{
    public function fromId(int $id);
}