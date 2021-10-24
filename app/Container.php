<?php

namespace App;

use App\Repositories\ProductsRepository;
use App\Repositories\UsersRepository;

class Container
{
    private array $container = [];
    private string $interface;
    private Object $object;

    public function __construct(array $container)
    {
        $this->container[$interface] = $object;
    }

    public function register(string $interface, Object $object): void
    {
        $this->container[$interface] = $object;
    }

    public function get(string $interface): Object
    {
        return $this->container[$interface];
    }
}