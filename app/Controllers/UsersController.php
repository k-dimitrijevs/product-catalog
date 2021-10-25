<?php

namespace App\Controllers;

use App\Auth;
use App\Exceptions\FormValidationException;
use App\Redirect;
use App\Services\UsersServices\LoginUserService;
use App\Services\UsersServices\RegisterUserRequest;
use App\Services\UsersServices\RegisterUserService;
use App\Validations\LoginFormValidation;
use App\Validations\RegisterFormValidation;
use App\View;
use Ramsey\Uuid\Uuid;

class UsersController
{
    private LoginFormValidation $loginValidator;
    private RegisterFormValidation $registerValidator;
    private LoginUserService $loginUserService;
    private RegisterUserService $registerUserService;

    public function __construct(
        LoginFormValidation $loginValidator,
        RegisterFormValidation $registerValidator,
        LoginUserService $loginUserService,
        RegisterUserService $registerUserService
    )
    {
        $this->loginValidator = $loginValidator;
        $this->registerValidator = $registerValidator;
        $this->loginUserService = $loginUserService;
        $this->registerUserService = $registerUserService;
    }

    public function login(): void
    {
        try
        {
            $user = $this->loginUserService->loginUser();
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
            $user = $this->loginUserService->loginUser();
            $this->registerValidator->validateRegisterFields($_POST, $user);

            $this->registerUserService->registerUser(
                new RegisterUserRequest(
                Uuid::uuid4(),
                $_POST['email'],
                $_POST['username'],
                $_POST['password']
            )
            );
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
        return new View('users/login.twig');
    }

    public function registerView(): View
    {
        Auth::unsetErrors();
        return new View('users/register.twig');
    }
}