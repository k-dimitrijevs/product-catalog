<?php

namespace App;

class Auth
{
    public static function loggedIn(): bool
    {
        return isset($_SESSION['email']);
    }
}