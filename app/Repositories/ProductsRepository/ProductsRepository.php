<?php

namespace App\Repositories\ProductsRepository;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;

interface ProductsRepository
{
    public function getAll(string $userId): ProductsCollection;
    public function getOne(string $id): ?Product;
    public function save(Product $product): void;
    public function delete(Product $product): void;
    public function edit(Product $product): void;
    public function searchByCategory(string $category, string $userId): ProductsCollection;

}