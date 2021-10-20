<?php

namespace App\Controllers;

use App\Auth;
use App\Exceptions\FormValidationException;
use App\Models\User;
use App\Redirect;
use App\Repositories\MysqlUsersRepository;
use App\Repositories\UsersRepository;
use App\Validations\LoginFormValidation;
use App\Validations\RegisterFormValidation;
use App\View;
use Ramsey\Uuid\Uuid;

class UsersController
{
    private UsersRepository $usersRepository;
    private LoginFormValidation $loginValidator;
    private RegisterFormValidation $registerValidator;

    public function __construct()
    {
        $this->usersRepository = new MysqlUsersRepository();
        $this->loginValidator = new LoginFormValidation();
        $this->registerValidator = new RegisterFormValidation();
    }

    public function login(): void
    {
        try
        {
            $user = $this->usersRepository->getByEmail($_POST['email']);
            $this->loginValidator->validateLoginFields($_POST, $user);

            $_SESSION['id'] = $user->getId();
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['username'] = $user->getUsername();
            Redirect::url('/products');

            Redirect::url('/login');
        } catch (FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->loginValidator->getErrors();
            Redirect::url('/login');
        }
    }

    public function register(): void
    {
        try
        {
            $user = $this->usersRepository->getByEmail($_POST['email']);
            $this->registerValidator->validateRegisterFields($_POST, $user);

            $user = new User(
                Uuid::uuid4(),
                $_POST['email'],
                $_POST['username'],
                password_hash($_POST['password'], PASSWORD_DEFAULT)
            );

            $this->usersRepository->register($user);
            Redirect::url('/products');
        } catch (FormValidationException $exception)
        {
            $_SESSION['_errors'] = $this->registerValidator->getErrors();
            Redirect::url('/register');
        }
    }

    public function logout(): View
    {
        session_destroy();
        return new View('users/register.twig');
    }

    public function loginView(): View
    {
//        if (Auth::loggedIn()) Redirect::url('/');
        return new View('users/login.twig');
    }

    public function registerView(): View
    {
        Auth::unsetErrors();
        return new View('users/register.twig');
    }
}