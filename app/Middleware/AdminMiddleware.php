<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AdminMiddleware implements Middleware
{
    public function handle(Request $request): void
    {
        if (!Auth::check()) {
            FlashMessage::danger('Você precisa estar logado para acessar esta página.');
            $this->redirectTo('/login');
        }

        if (!Auth::isAdmin()) {
            FlashMessage::danger('Acesso negado. Você precisa ser um administrador para acessar esta página.');
            $this->redirectTo('/dashboard');
        }
    }

    private function redirectTo(string $location): void
    {
        header('Location: ' . $location, true, 302);
        exit;
    }
}
