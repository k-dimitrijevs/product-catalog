<?php

namespace App\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;

interface ProductsRepository
{
    public function getAll(): ProductsCollection;
    public function getOne(string $id): ?Product;
    public function save(Product $product): void;
    public function delete(Product $product): void;
}