<?php

namespace App\Services\ProductsServices;

use App\Models\Collections\TagsCollection;
use App\Repositories\TagsRepository\MySqlTagsRepository;

class ListProductTagsService
{
    private MySqlTagsRepository $repository;

    public function __construct(MySqlTagsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listTags(): TagsCollection
    {
        return $this->repository->getAll();
    }
}