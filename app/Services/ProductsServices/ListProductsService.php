<?php

namespace App\Services\ProductsServices;

use App\Models\Collections\ProductsCollection;
use App\Repositories\ProductsRepository\MySqlProductsRepository;

class ListProductsService
{
    private MySqlProductsRepository $repository;

    public function __construct(MySqlProductsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listProducts(): ProductsCollection
    {
        return $this->repository->getAll($_SESSION['id']);
    }
}