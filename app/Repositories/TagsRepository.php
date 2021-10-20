<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Models\Collections\TagsCollection;

interface TagsRepository
{
    public function getAll(): TagsCollection;
    public function getOne(string $id): ?Tag;
}
