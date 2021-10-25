<?php

namespace App\Services\ProductsServices;

use App\Repositories\ProductsRepository\MySqlProductsRepository;
use App\Repositories\TagsRepository\MySqlTagsRepository;

class EditProductService
{
    private MySqlProductsRepository $productsRepository;
    private MySqlTagsRepository $tagsRepository;

    public function __construct(
        MySqlProductsRepository $productsRepository,
        MySqlTagsRepository $tagsRepository
    )
    {
        $this->productsRepository = $productsRepository;
        $this->tagsRepository = $tagsRepository;
    }

    public function editProduct(string $id): void
    {
        $product = $this->productsRepository->getOne($id);

        $tags = array_slice($_POST, 3);

        foreach ($tags as $tag)
        {
            $this->tagsRepository->setProductTags($id, $tag);
        }

        $this->productsRepository->edit($product);
    }
}