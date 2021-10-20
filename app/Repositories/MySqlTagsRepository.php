<?php

namespace App\Repositories;

use App\Models\Collections\TagsCollection;
use App\Models\Tag;
use Carbon\Carbon;
use PDO;
use PDOException;

class MySqlProductsRepository implements TagsRepository
{
    private PDO $connection;

    public function __construct()
    {
        require_once "config.php";

        $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
        try {
            $this->connection = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getAll(): TagsCollection
    {
        $sql = "SELECT * FROM tags ORDER BY created_at DESC";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $tags = $statement->fetchAll(PDO::FETCH_ASSOC);
        $collection = new TagsCollection();

        foreach ($tags as $tag) {
            $collection->add(new Tag(
                $tags['id'],
                $tags['name']
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

        return new Tag(
            $tag['id'],
            $tag['title'],
        );
    }
}
