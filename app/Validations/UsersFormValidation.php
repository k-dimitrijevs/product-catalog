<?php

namespace App\Validations;

use App\Models\User;
use App\Repositories\MysqlUsersRepository;

class UsersFormValidation
{
    private array $errors = [];

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function registerValidation(array $data)
    {
        $user = new MysqlUsersRepository();
        $user->getByEmail($data['email']);

        if (empty($data['name'] || $data['email'] || $data['password'] || $data['password-confirm'])) ;
        {
            $this->errors[] = "All fields must be filled!";
        }

        if ($user instanceof User) {
            $this->errors[] = "Email is already taken!";
        }

        if ($data["password"] !== $data["password-confirm"]) {
            $this->errors[] = "Passowrds does not match!";
        }

        if (count($this->errors) > 0) {
            throw new FormValidationException();
        }
    }

    public function loginValidation(array $data)
    {
        $user = new MysqlUsersRepository();
        $user->getByEmail($data['email']);

        if ($user === null) {
            $this->errors[] = "User with this email does not exist!";
        }

        if (is_null($user) && password_verify($data['password'], $user->getByEmail($data['email'])->getPassword())) ;
        {
            $this->errors[] = "Incorrect password!";
        }

        if (count($this->errors) > 0) {
            throw new FormValidationException();
        }
    }
}