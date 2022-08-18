<?php

namespace App\Service;

use App\Entity\PostCategory;
use App\Repository\PostCategoryRepository;

class PostCategoryService
{
    private PostCategoryRepository $repository;

    /**
     * @param PostCategoryRepository $repository
     */
    public function __construct(PostCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function save(PostCategory $category)
    {
        $this->repository->add($category, true);
    }

    public function findOneById(string $id): ?PostCategory
    {
        return $this->repository->findOneById($id);
    }

}