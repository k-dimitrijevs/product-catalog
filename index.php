<?php

use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\View;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require 'vendor/autoload.php';

session_start();

$container = new \DI\Container();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'ProductsController@index');
    $r->addRoute('GET', '/products', 'ProductsController@index');
    $r->addRoute('GET', '/products/search', 'ProductsController@search');
    $r->addRoute('GET', '/products/create', 'ProductsController@create');
    $r->addRoute('POST', '/products', 'ProductsController@store');
    $r->addRoute('POST', '/products/{id}', 'ProductsController@delete');
    $r->addRoute('GET', '/products/{id}/edit', 'ProductsController@editView');
    $r->addRoute('POST', '/products/{id}/edit', 'ProductsController@edit');

    $r->addRoute('GET', '/login', 'UsersController@loginView');
    $r->addRoute('POST', '/login', 'UsersController@login');
    $r->addRoute('GET', '/register', 'UsersController@registerView');
    $r->addRoute('POST', '/register', 'UsersController@register');
    $r->addRoute('GET', '/logout', 'UsersController@logout');
});

function base_path(): string
{
    return __DIR__;
}

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

$loader = new FilesystemLoader(base_path() . '/app/Views');
$templateEngine = new Environment($loader, []);
$templateEngine->addGlobal('session', $_SESSION);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars

        $middlewares = [
            "ProductsController@index" => [
                AuthMiddleware::class
            ],
            "ProductsController@create" => [
                AuthMiddleware::class
            ],
            "ProductsController@delete" => [
                AuthMiddleware::class
            ],
            "ProductsController@editView" => [
                AuthMiddleware::class
            ],
            "ProductsController@edit" => [
                AuthMiddleware::class
            ],

            "UsersController@loginView" =>[
                GuestMiddleware::class
            ],
            "UsersController@login" =>[
                GuestMiddleware::class
            ],
            "UsersController@registerView" =>[
                GuestMiddleware::class
            ],
            "UsersController@register" =>[
                GuestMiddleware::class
            ]
        ];

        if (array_key_exists($handler, $middlewares)) {
            foreach ($middlewares[$handler] as $middleware) {
                (new $middleware)->handle();
            }
        }

        [$controller, $method] = explode('@', $handler);
        $controller = "App\\Controllers\\" . $controller;
        try {
            $controller = $container->get($controller);
        } catch (\DI\DependencyException | \DI\NotFoundException $e) {
        }
        $response = $controller->$method($vars);

        if($response instanceof View)
        {
            echo $templateEngine->render($response->getTemplate(), $response->getArgs());
        }
        break;
}