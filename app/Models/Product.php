<?php

namespace App\Models;

use Carbon\Carbon;

class Product
{
    private string $id;
    private string $title;
    private string $category;
    private int $quantity;
    private string $userId;
    private string $createdAt;
    private ?string $updatedAt;

    public function __construct(string $id, string $title, string $category, int $quantity, string $userId, ?string $createdAt = null, ?string $updatedAt = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
        $this->quantity = $quantity;
        $this->userId = $userId;
        $this->createdAt = $createdAt ?? Carbon::now();
        $this->updatedAt = $updatedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'category' => $this->getCategory(),
            'quantity' => $this->getQuantity(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
        ];
    }
}