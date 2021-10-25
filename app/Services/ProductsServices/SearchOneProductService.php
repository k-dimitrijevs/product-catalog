<?php

namespace App\Services\ProductsServices;

use App\Models\Product;
use App\Repositories\ProductsRepository\MySqlProductsRepository;

class SearchOneProductService
{
    private MySqlProductsRepository $repository;

    public function __construct(MySqlProductsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function searchProducts(string $id): Product
    {
        return $this->repository->getOne($id);
    }
}