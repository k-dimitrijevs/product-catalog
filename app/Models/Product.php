<?php

namespace App\Models;

use App\Repositories\MySqlTagsRepository;
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
    private ?string $tags;

    public function __construct(string $id, string $title, string $category, int $quantity, string $userId, ?string $createdAt = null, ?string $updatedAt = null, ?string $tags = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
        $this->quantity = $quantity;
        $this->userId = $userId;
        $this->createdAt = $createdAt ?? Carbon::now();
        $this->updatedAt = $updatedAt;
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

    public function getTagsRepository(): string
    {
        return $this->tagsRepository->getProductTags($this->id);
    }

    public function getTags(): ?string
    {
        return $this->tags;
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