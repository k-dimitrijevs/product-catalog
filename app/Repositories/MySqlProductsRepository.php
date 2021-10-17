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

    public function getAll(): ProductsCollection
    {
        $sql = "SELECT * FROM products ORDER BY created_at DESC";
        $statement = $this->connection->query($sql);
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);
        $collection = new ProductsCollection();

        foreach ($products as $product)
        {
            $collection->add(new Product(
                $product['id'],
                $product['title'],
                $product['category'],
                $product['quantity'],
                $product['created_at'],
                $product['updated_at'],
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
            $product['created_at'],
            $product['updated_at'],
        );
    }

    public function save(Product $product): void
    {
        $sql = "INSERT INTO products (id, title, category, quantity, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            $product->getId(),
            $product->getTitle(),
            $product->getCategory(),
            $product->getQuantity(),
            $product->getCreatedAt()
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

}