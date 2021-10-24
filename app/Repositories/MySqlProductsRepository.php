<?php

namespace App\Repositories;

use App\Models\Collections\ProductsCollection;
use App\Models\Product;
use Carbon\Carbon;
use PDO;
use PDOException;

class MySqlProductsRepository implements ProductsRepository
{
    private PDO $connection;

    public function __construct()
    {
        require_once "config.php";

        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
        try
        {
            $this->connection = new PDO($dsn, $user, $pass);
        } catch (PDOException $e)
        {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getAll(string $userId): ProductsCollection
    {
        $sql = "SELECT * FROM products WHERE user_id = ? ORDER BY created_at DESC";
        $statement = $this->connection->prepare($sql);
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
        $stmt = $this->connection->prepare($sql);
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
        $stmt = $this->connection->prepare($sql);

        $tagsRepository = new MySqlTagsRepository();

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
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$product->getId()]);
    }

    public function edit(Product $product): void
    {
        $sql = "UPDATE products 
                SET title = ?, category = ?, quantity = ?, updated_at = ?
                WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
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
        $statement = $this->connection->prepare($sql);
        $statement->execute([$userId]);

        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
        $collection = new ProductsCollection();

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
            ));
        }

        return $collection;
    }
}