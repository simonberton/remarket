<?php

namespace App\Service;

use App\Entity\Envase;
use App\Repository\EnvaseRepository;

class EnvaseService
{
    private EnvaseRepository $repository;

    /**
     * @param EnvaseRepository $repository
     */
    public function __construct(EnvaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function save(Envase $envase)
    {
        $this->repository->add($envase, true);
    }

    public function findOneById(string $id): ?Envase
    {
        return $this->repository->findOneById($id);
    }

    public function findOneBySlug($envaseSlug): ?Envase
    {
        return $this->repository->findOneBySlug($envaseSlug);
    }

}