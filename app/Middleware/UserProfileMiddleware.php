<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;

class UserProfileMiddleware implements Middleware
{
    public function handle(Request $request): void
    {
        $id = (int) $request->getParam('id');
        $currentUser = Auth::user();

        if (!$currentUser || ($currentUser->id !== $id && !$currentUser->role === 'admin')) {
            throw new \Exception('Acesso negado ao perfil.');
        }
    }
}
