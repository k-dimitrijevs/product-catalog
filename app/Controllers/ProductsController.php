<?php

namespace App\Controllers;

use App\Auth;
use App\Models\Product;
use App\Redirect;
use App\Repositories\MySqlProductsRepository;
use App\Repositories\ProductsRepository;
use App\Validations\FormValidationException;
use App\Validations\ProductsFormValidation;
use App\View;
use Ramsey\Uuid\Uuid;

class ProductsController
{
    private ProductsRepository $productsRepository;
    private ProductsFormValidation $validator;

    public function __construct()
    {
        $this->productsRepository = new MySqlProductsRepository();
        $this->validator = new ProductsFormValidation();
    }

    public function index(): View
    {
//        var_dump($_SESSION['id']);die;
        if (! Auth::loggedIn()) Redirect::url('login');

        $products = $this->productsRepository->getAll($_SESSION['id']);

        return new View('products/index.twig', [
            'products' => $products
        ]);
    }

    public function search(): View
    {
        $products = $this->productsRepository->searchByCategory($_GET['category'], $_SESSION['id']);
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

        try {
            $this->validator->validateProductFields($_POST);

            $product = new Product(
                Uuid::uuid4(),
                $_POST['title'],
                $_POST['category'],
                $_POST['quantity'],
                $_SESSION['id']
            );

            $this->productsRepository->save($product);
            Redirect::url('/products');
        } catch (FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->validator->getErrors();
            Redirect::url('products/create');
        }
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
        try {
            $this->validator->validateProductFields($_POST);

            $product = $this->productsRepository->getOne($id);
            $this->productsRepository->edit($product);
            Redirect::url('/');
        } catch (FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->validator->getErrors();
            Redirect::url("/products/{$id}/edit");
        }
    }

}