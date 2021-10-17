<?php

namespace App\Validations;
use App\Validations\FormValidationException;
use Exception;

class ProductsFormValidation
{
    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }
    public function validateProductFields(array $data)
    {
        if (empty($data['title']) || empty($data['category']) || empty($data['quantity']))
        {
            $this->errors[] = "All fields must be filled!";
        }

        $qty = (int) $data['quantity'];
        if ($qty <= 0)
        {
            $this->errors[] = "Quantity must be a positive integer!";
        }

        $categories = ['Components', 'Accessories', 'Storage'];
        if (!in_array($data['category'], $categories))
        {
            $this->errors[] = "Category you entered does not exist!";
        }

        if (count($this->errors) > 0)
        {
            throw new FormValidationException();
        }
    }
}