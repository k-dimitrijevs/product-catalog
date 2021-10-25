<?php

namespace App\Services\ProductsServices;

use App\Models\Collections\ProductsCollection;
use App\Repositories\ProductsRepository\MySqlProductsRepository;

class SearchProductsService
{
    private MySqlProductsRepository $repository;

    public function __construct(MySqlProductsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function searchProducts(): ProductsCollection
    {
        return $this->repository->searchByCategory($_GET['category'], $_SESSION['id']);
    }
}