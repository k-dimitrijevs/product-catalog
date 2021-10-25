<?php

namespace App\Services\ProductsServices;

use App\Repositories\ProductsRepository\MySqlProductsRepository;

class DeleteProductService
{
    private MySqlProductsRepository $repository;

    public function __construct(MySqlProductsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function deleteProduct(string $id): void
    {
        $product = $this->repository->getOne($id);
        if ($product != null)
        {
            $this->repository->delete($product);
        }
    }
}