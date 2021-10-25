<?php

namespace App\Services\ProductsServices;

use App\Models\Product;
use App\Repositories\ProductsRepository\MySqlProductsRepository;
use App\Repositories\TagsRepository\MySqlTagsRepository;
use Ramsey\Uuid\Uuid;

class SaveProductService
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

    public function saveProduct(SaveProductRequest $request): void
    {
        $product = new Product(
            $request->getId(),
            $request->getTitle(),
            $request->getCategory(),
            $request->getQuantity(),
            $request->getUserId(),
            $request->getTags()
        );
        $tags = array_slice($_POST, 3);

        foreach ($tags as $tag)
        {
            $this->tagsRepository->setProductTags($product->getId(), $tag);
        }
        $this->productsRepository->save($product);
    }
}