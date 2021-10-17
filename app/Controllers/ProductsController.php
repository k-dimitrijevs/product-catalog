<?php

namespace App\Controllers;

use App\Models\Product;
use App\Redirect;
use App\Repositories\MySqlProductsRepository;
use App\Repositories\ProductsRepository;
use App\View;
use Ramsey\Uuid\Uuid;

class ProductsController
{
    private ProductsRepository $productsRepository;

    public function __construct()
    {
        $this->productsRepository = new MySqlProductsRepository();
    }

    public function index(): View
    {
        $products = $this->productsRepository->getAll();

        return new View('products/index.twig', [
            'products' => $products
        ]);
    }

    public function search(): View
    {
        $products = $this->productsRepository->searchByCategory($_GET['category']);

        return new View('products/index.twig', [
            'products' => $products
        ]);
    }

    public function create()
    {
        return new View('products/create.twig');
    }

    public function store()
    {
        // validate

        $product = new Product(
            Uuid::uuid4(),
            $_POST['title'],
            $_POST['category'],
            $_POST['quantity']
        );

        $this->productsRepository->save($product);

        Redirect::url('/products');
    }

    public function delete(array $vars)
    {
        $id = $vars['id'] ?? null;
        if ($id == null) header('Location: /');

        $product = $this->productsRepository->getOne($id);
        if ($product != null)
        {
            $this->productsRepository->delete($product);
        }

        Redirect::url('/');
    }

    public function editView(array $vars): View
    {
        $id = $vars['id'] ?? null;
        $products = $this->productsRepository->getOne($id);

        return new View('products/edit.twig', [
            'products' => $products
        ]);
    }

    public function edit(array $vars): void
    {
        $id = $vars['id'] ?? null;
        if ($id == null) header('Location: /');
        $product = $this->productsRepository->getOne($id);
        if ($product != null)
        {
            $this->productsRepository->edit($product);
        }

        Redirect::url('/');
    }

}