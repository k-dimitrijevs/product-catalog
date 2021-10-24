<?php

namespace App\Repositories\TagsRepository;

use App\Models\Collections\TagsCollection;
use App\Models\Tag;
use App\MySQLConfig;
use PDO;

class MySqlTagsRepository extends MySQLConfig implements TagsRepository
{
    public function getAll(): TagsCollection
    {
        $sql = "SELECT * FROM tags";
        $statement = $this->connect()->prepare($sql);
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
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$id]);
        $tag = $stmt->fetch();

        return new Tag($tag['id'], $tag['name']);
    }

    public function setProductTags(string $productId, string $tagId): void
    {
            $sql = "INSERT INTO products_tags (product_id, tag_id) VALUES (?, ?)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute([$productId, $tagId]);
    }

    public function getProductTags($productId): string
    {
        $sql = "SELECT tag_id FROM products_tags WHERE product_id = ?";
        $stmt = $this->connect()->prepare($sql);
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
            $stmt = $this->connect()->prepare($sql);
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
