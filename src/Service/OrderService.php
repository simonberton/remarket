<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Order;
use App\Repository\OrderRepository;

class OrderService
{
    private OrderRepository $repository;

    /**
     * @param OrderRepository $repository
     */
    public function __construct(OrderRepository $repository)
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

    public function findOneById(string $id): ?Order
    {
        return $this->repository->findOneById($id);
    }

    public function findOneBySlug($orderSlug): ?Order
    {
        return $this->repository->findOneBySlug($orderSlug);
    }

    public function findOneByName(string $name): ?Order
    {
        return $this->repository->findOneByName($name);
    }
}