<?php

namespace App\Validations;
use App\Exceptions\FormValidationException;
use Exception;

class ProductsFormValidation
{
    private array $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @throws FormValidationException
     */
    public function validateProductFields(array $data)
    {
        if (empty($data['title'])) $this->errors[] = "Title is required";
        if (empty($data['category'])) $this->errors[] = "Category is required";
        if (empty($data['quantity'])) $this->errors[] = "Quantity is required";

        $qty = (int) $data['quantity'];
        if ($qty <= 0) $this->errors[] = "Quantity must be a positive integer!";

        $categories = ['Components', 'Accessories', 'Storage'];
        if (!in_array($data['category'], $categories)) $this->errors[] = "Category you entered doesn't exist";

        if (count($this->errors) > 0) throw new FormValidationException();
    }
}