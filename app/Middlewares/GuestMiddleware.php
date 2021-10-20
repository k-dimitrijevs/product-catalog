<?php

namespace App\Middlewares;

use App\Auth;
use App\Redirect;

class GuestMiddleware implements Middleware
{
    public function handle(): void
    {
        if (Auth::loggedIn()) Redirect::url('/');
    }
}