<?php

namespace App\Validations;

use App\Exceptions\FormValidationException;
use App\Models\User;
use Exception;

class RegisterFormValidation
{
    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @throws FormValidationException
     */
    public function validateRegisterFields(array $data, ?User $user)
    {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->errors[] = "Please enter a valid e-mail address";
        }

        if ($user && !isset($_SESSION['id'])) $this->errors['user'] = "E-mail is already used";
        if (empty($data['username'])) $this->errors[] = "Username is required";
        if (empty($data['password'])) $this->errors[] = "Password is required";
        if (strlen($data['password']) < 8) $this->errors[] = "Password must be at least 8 characters long";
        if ($data['password'] !== $data['password-confirm']) $this->errors[] = "Passwords don't match";

        if (count($this->errors) > 0) throw new FormValidationException();
    }
}