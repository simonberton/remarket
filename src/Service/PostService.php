<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\PostRepository;

class PostService
{
    private PostRepository $repository;

    /**
     * @param PostRepository $repository
     */
    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findWithLimit(int $limit): array
    {
        return $this->repository->findBy([], [], $limit);
    }

    public function save(Post $post)
    {
        $this->repository->add($post, true);
    }

    public function findOneById(string $id): ?Post
    {
        return $this->repository->findOneById($id);
    }

    public function findOneBySlug($postSlug): ?Post
    {
        return $this->repository->findOneBySlug($postSlug);
    }

}