<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (Auth::isAdmin()) {
                $this->redirectTo('/admin/dashboard');
            }

            $this->render('home/index', ['user' => $user]);
        } else {
            $this->render('home/index');
        }
    }
}
