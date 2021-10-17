<?php

namespace App\Controllers;

use App\Auth;
use App\Models\User;
use App\Redirect;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepository;
use App\View;
use Ramsey\Uuid\Uuid;

class UsersController
{
    private UsersRepository $usersRepository;

    public function __construct()
    {
        $this->usersRepository = new MysqlUsersRepository();
    }

    public function login(): void
    {
        if (Auth::loggedIn()) Redirect::url('/');

        $user = $this->usersRepository->getByEmail($_POST['email']);

        if ($user !== null && password_verify($_POST['password'], $user->getPassword()))
        {
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['username'] = $user->getUsername();
            Redirect::url('/products');
            exit;
        }

        Redirect::url('/login');
    }

    public function register(): void
    {

        if ($_POST['password'] !== $_POST['password-confirm']) {
            var_dump('invalid');
        } elseif ($this->usersRepository->getByEmail($_POST['email']) > 0) {
            var_dump('invalid');
        }else {
            $user = new User(
                Uuid::uuid4(),
                $_POST['email'],
                $_POST['username'],
                password_hash($_POST['password'], PASSWORD_DEFAULT)
            );

            $this->usersRepository->register($user);
            Redirect::url('/products');
        }
    }

    public function logout(): View
    {
        session_destroy();
        return new View('users/register.twig');
    }

    public function loginView(): View
    {
        if (Auth::loggedIn()) Redirect::url('/');
        return new View('users/login.twig');
    }

    public function registerView(): View
    {
        return new View('users/register.twig');
    }
}