<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductService
{
    private ProductRepository $repository;

    /**
     * @param ProductRepository $repository
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function save(Product $product)
    {
        $this->repository->add($product, true);
    }

    public function findOneById(string $id): ?Product
    {
        return $this->repository->findOneById($id);
    }

    public function findAllByCategory(Category $category)
    {
        return $this->repository->findBy(['category' => $category]);
    }

    public function findByQuery(string $searchQuery)
    {
        return $this->repository->findByQuery($searchQuery);
    }

    public function findOneBySlug($slug)
    {
        return $this->repository->findOneBySlug($slug);
    }

    public function getDividors(Product $product)
    {
        return array_explode($product->getDivisibleBy());
    }

    public function deleteAll()
    {
        $this->repository->deleteAll();
    }

    public function findOneBySku($sku)
    {
        return $this->repository->findOneBySku($sku);
    }

}