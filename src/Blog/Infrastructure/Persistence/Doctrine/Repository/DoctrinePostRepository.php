<?php


namespace App\Blog\Infrastructure\Persistence\Doctrine\Repository;


use App\Blog\Domain\Entity\Post;
use App\Blog\Domain\Respository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DoctrinePostRepository implements PostRepository
{


    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $entityManager->getRepository(Post::class);
    }

    public function fromId(int $id)
    {
        return $this->repository->find($id);
    }


}