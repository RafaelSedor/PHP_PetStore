<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthController extends Controller
{
    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (Auth::attempt($email, $password)) {
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                FlashMessage::success('Login como administrador realizado com sucesso.');
                $this->redirectTo('/admin/dashboard');
            } else {
                FlashMessage::success('Login realizado com sucesso.');
                $this->redirectTo('/');
            }
        } else {
            FlashMessage::danger('E-mail ou senha inválidos.');
            $this->redirectTo('/login');
        }
    }

    public function logout()
    {
        Auth::logout();
        FlashMessage::success('Logout realizado com sucesso.');
        $this->redirectTo('/');
    }

    public function showLoginForm()
{
    $this->render('auth/login');
}
}
