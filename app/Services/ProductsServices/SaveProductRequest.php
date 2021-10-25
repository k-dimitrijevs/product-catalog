<?php

namespace App\Services\ProductsServices;

class SaveProductRequest
{
//Uuid::uuid4(),
//$_POST['title'],
//$_POST['category'],
//$_POST['quantity'],
//$_SESSION['id'],
//$_POST['tags']

    private string $id;
    private string $title;
    private string $category;
    private string $quantity;
    private string $userId;
    private ?string $tags;

    public function __construct
    (
        string $id,
        string $title,
        string $category,
        string $quantity,
        string $userId,
        ?string $tags = null
    )
    {

        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
        $this->quantity = $quantity;
        $this->userId = $userId;
        $this->tags = $tags;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getQuantity(): string
    {
        return $this->quantity;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }
}