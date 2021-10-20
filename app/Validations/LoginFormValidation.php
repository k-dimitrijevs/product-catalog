<?php

namespace App\Validations;

use App\Exceptions\FormValidationException;
use App\Models\User;
use Exception;

class LoginFormValidation
{
    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @throws FormValidationException
     */
    public function validateLoginFields(array $data, ?User $user): void
    {
        if($user === null) $this->errors[] = "Cannot find user with this email";
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $this->errors[] = "Incorrect e-mail";
        if(empty($data['email'])) $this->errors['email'] = "Email is required";
        if(empty($data['password'])) $this->errors['password'] = "Password is required";


        if($user && !password_verify($data['password'], $user->getPassword()))
        {
            $this->errors['password'] = "Wrong password";
        }

        if(count($this->errors) > 0) throw new FormValidationException();
    }
}