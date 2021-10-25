<?php

namespace App\Services\UsersServices;

use App\Models\User;
use App\Repositories\UsersRepository\MysqlUsersRepository;
use Ramsey\Uuid\Uuid;

class RegisterUserService
{
    private MysqlUsersRepository $repository;

    public function __construct(MysqlUsersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function registerUser(RegisterUserRequest $request): void
    {
        $user = new User(
            $request->getId(),
            $request->getEmail(),
            $request->getUsername(),
            password_hash($request->getPassword(), PASSWORD_DEFAULT)
        );
        $this->repository->register($user);
    }
}