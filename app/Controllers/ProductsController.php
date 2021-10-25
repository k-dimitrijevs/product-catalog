<?php

namespace App\Controllers;

use App\Auth;
use App\Redirect;
use App\Exceptions\FormValidationException;
use App\Services\ProductsServices\DeleteProductService;
use App\Services\ProductsServices\EditProductService;
use App\Services\ProductsServices\ListProductsService;
use App\Services\ProductsServices\ListProductTagsService;
use App\Services\ProductsServices\SaveProductRequest;
use App\Services\ProductsServices\SaveProductService;
use App\Services\ProductsServices\SearchOneProductService;
use App\Services\ProductsServices\SearchProductsService;
use App\Validations\ProductsFormValidation;
use App\View;
use Ramsey\Uuid\Uuid;

class ProductsController
{
    private ProductsFormValidation $validator;
    private ListProductsService $listProductsService;
    private SearchProductsService $searchProductsService;
    private SearchOneProductService $searchOneProductsService;
    private ListProductTagsService $listProductTagsService;
    private SaveProductService $saveProductService;
    private DeleteProductService $deleteProductService;
    private EditProductService $editProductService;

    public function __construct(
        ProductsFormValidation $validator,
        ListProductsService $listProductsService,
        SearchProductsService $searchProductsService,
        SearchOneProductService $searchOneProductsService,
        ListProductTagsService $listProductTagsService,
        SaveProductService $saveProductService,
        DeleteProductService $deleteProductService,
        EditProductService $editProductService
    )
    {
        $this->validator = $validator;
        $this->listProductsService = $listProductsService;
        $this->searchProductsService = $searchProductsService;
        $this->searchOneProductsService = $searchOneProductsService;
        $this->listProductTagsService = $listProductTagsService;
        $this->saveProductService = $saveProductService;
        $this->deleteProductService = $deleteProductService;
        $this->editProductService = $editProductService;
    }

    public function index(): View
    {
        Auth::unsetErrors();
        return new View('products/index.twig', [
            'products' => $this->listProductsService->listProducts(),
        ]);
    }

    public function search(): View
    {
        return new View('products/index.twig', [
            'products' => $this->searchProductsService->searchProducts()
        ]);
    }

    public function create()
    {
        return new View('products/create.twig', [
            'tags' => $this->listProductTagsService->listTags()
        ]);
    }

    public function store()
    {
        try {
            $this->validator->validateProductFields($_POST);
            $this->saveProductService->saveProduct(
                new SaveProductRequest(
                    Uuid::uuid4(),
                    $_POST['title'],
                    $_POST['category'],
                    $_POST['quantity'],
                    $_SESSION['id'],
                    $_POST['tags']
                )
            );
            Redirect::url('/products');
        } catch (FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->validator->getErrors();
            Redirect::url('products/create');
        }
    }

    public function delete(array $vars)
    {
        $this->deleteProductService->deleteProduct($vars['id']);
        Redirect::url('/');
    }

    public function editView(array $vars): View
    {
        return new View('products/edit.twig', [
            'products' => $this->searchOneProductsService->searchProducts($vars['id']),
            'tags' => $this->listProductTagsService->listTags()
        ]);
    }

    public function edit(array $vars): void
    {
        try {
            $this->validator->validateProductFields($_POST);
            $this->editProductService->editProduct($vars['id']);
            Redirect::url('/');
        } catch (FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->validator->getErrors();
            Redirect::url("/products/{$vars['id']}/edit");
        }
    }

}