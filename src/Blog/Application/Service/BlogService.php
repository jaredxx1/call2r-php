<?php


namespace App\Blog\Application\Service;


use App\Blog\Domain\Respository\PostRepository;
use Exception;

final class BlogService
{

    /**
     * @var PostRepository
     */
    private $postRepository;

    public function __construct(
        PostRepository $postRepository
    )
    {
        $this->postRepository = $postRepository;
    }

    public function getPostById(int $id)
    {
        $post = $this->postRepository->fromId($id);

        if (empty($post)) {
            throw new Exception('Post not found', 404);
        }

        return $post;
    }

    public function getPostBySlug(string $slug)
    {
    }
}