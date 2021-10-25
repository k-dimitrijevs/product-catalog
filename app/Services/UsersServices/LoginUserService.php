<?php

namespace App\Services\UsersServices;

use App\Models\User;
use App\Repositories\UsersRepository\MysqlUsersRepository;

class LoginUserService
{
    private MysqlUsersRepository $repository;

    public function __construct(MysqlUsersRepository $repository)
    {
        $this->repository = $repository;
    }

    public function loginUser(): ?User
    {
        return $this->repository->getByEmail($_POST['email']);
    }
}