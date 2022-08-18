<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryService
{
    private CategoryRepository $repository;

    /**
     * @param CategoryRepository $repository
     */
    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function save(Category $category)
    {
        $this->repository->add($category, true);
    }

    public function findOneById(string $id): ?Category
    {
        return $this->repository->findOneById($id);
    }

    public function findOneBySlug($categorySlug): ?Category
    {
        return $this->repository->findOneBySlug($categorySlug);
    }

    public function findOneByName(string $name): ?Category
    {
        return $this->repository->findOneByName($name);
    }

    /**
     * @param $name
     * @return Category
     */
    public function create($name, $slug): Category
    {
        $category = new Category();
        $category->setName($name);
        $category->setDescription($name);
        $category->setSlug($slug);
        $this->save($category);

        return $category;
    }

}