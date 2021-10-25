<?php

namespace App\Repositories\ProductsRepository;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;
use App\MySQLConfig;
use App\Repositories\TagsRepository\MySqlTagsRepository;
use Carbon\Carbon;
use PDO;

class MySqlProductsRepository extends MySQLConfig implements ProductsRepository
{
    public function getAll(string $userId): ProductsCollection
    {
        $sql = "SELECT * FROM products WHERE user_id = ? ORDER BY created_at DESC";
        $statement = $this->connect()->prepare($sql);
        $statement->execute([$userId]);
        $products = $statement->fetchAll();
        $collection = new ProductsCollection();

        $tagsRepository = new MySqlTagsRepository();

        foreach ($products as $product)
        {
            $collection->add(new Product(
                $product['id'],
                $product['title'],
                $product['category'],
                $product['quantity'],
                $product['user_id'],
                $product['created_at'],
                $product['updated_at'],
                $tagsRepository->getProductTags($product['id'])

            ));
        }

        return $collection;
    }

    public function getOne(string $id): ?Product
    {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id]);
        $product = $stmt->fetch();


        return new Product(
            $product['id'],
            $product['title'],
            $product['category'],
            $product['quantity'],
            $product['user_id'],
            $product['created_at'],
            $product['updated_at'],
        );
    }

    public function save(Product $product): void
    {
        $sql = "INSERT INTO products (id, title, category, quantity, user_id, created_at) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->connect()->prepare($sql);

        $stmt->execute([
            $product->getId(),
            $product->getTitle(),
            $product->getCategory(),
            $product->getQuantity(),
            $product->getUserId(),
            $product->getCreatedAt(),
        ]);
    }

    public function delete(Product $product): void
    {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$product->getId()]);
    }

    public function edit(Product $product): void
    {
        $sql = "UPDATE products 
                SET title = ?, category = ?, quantity = ?, updated_at = ?
                WHERE id = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([
            $_POST['title'],
            $_POST['category'],
            $_POST['quantity'],
            Carbon::now(),
            $product->getId()
        ]);
    }

    public function searchByCategory(string $category, string $userId): ProductsCollection
    {
        $sql = "SELECT * FROM products WHERE category = '{$category}' and user_id = ? ORDER BY created_at DESC";
        $statement = $this->connect()->prepare($sql);
        $statement->execute([$userId]);

        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
        $collection = new ProductsCollection();

        $tagsRepository = new MySqlTagsRepository();

        foreach ($products as $product)
        {
            $collection->add(new Product(
                $product['id'],
                $product['title'],
                $product['category'],
                $product['quantity'],
                $product['user_id'],
                $product['created_at'],
                $product['updated_at'],
                $tagsRepository->getProductTags($product['id'])
            ));
        }

        return $collection;
    }
}