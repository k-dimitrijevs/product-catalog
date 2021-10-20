<?php

namespace App\Middlewares;

use App\Auth;
use App\Redirect;

class AuthMiddleware implements Middleware
{
    public function handle(): void
    {
        if (!Auth::loggedIn()) Redirect::url('../login');
    }
}