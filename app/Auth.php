<?php

namespace App;

class Auth
{
    public static function loggedIn(): bool
    {
        return isset($_SESSION['email']);
    }

    public static function unsetErrors(): void
    {
        unset($_SESSION['_errors']);
    }
}