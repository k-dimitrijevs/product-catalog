<?php

namespace App\Repositories;

use App\Models\Collections\TagsCollection;
use App\Models\Tag;
use PDO;
use PDOException;

class MySqlTagsRepository implements TagsRepository
{
    private PDO $connection;

    public function __construct()
    {
        include "config.php";

        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
        try
        {
            $this->connection = new PDO($dsn, $user, $pass);
        } catch (PDOException $e)
        {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getAll(): TagsCollection
    {
        $sql = "SELECT * FROM tags";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $tags = $statement->fetchAll(PDO::FETCH_ASSOC);
        $collection = new TagsCollection();

        foreach ($tags as $tag)
        {
            $collection->add(new Tag(
                $tag['id'],
                $tag['name'],
            ));
        }

        return $collection;
    }

    public function getOne(string $id): ?Tag
    {
        $sql = "SELECT * FROM tags WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        $tag = $stmt->fetch();

        return new Tag($tag['id'], $tag['name']);
    }

    public function setProductTags(string $productId, string $tagId): void
    {
            $sql = "INSERT INTO products_tags (product_id, tag_id) VALUES (?, ?)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$productId, $tagId]);
    }

    public function getProductTags($productId): string
    {
        $sql = "SELECT tag_id FROM products_tags WHERE product_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$productId]);
        $productTags = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tags = [];
        foreach ($productTags as $productTag)
        {
            foreach ($productTag as $tag)
            {
                $tags[] = $tag;
            }
        }

        $tagNames = '';
        foreach ($tags as $tag)
        {
            $sql = "SELECT name FROM tags WHERE id IN (?)";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([$tag]);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data as $row)
            {
                foreach ($row as $value)
                {
                    $tagNames .= " ({$value}) ";
                }
            }
        }

        return $tagNames;
    }
}
