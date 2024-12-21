<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::check() ? Auth::user() : null;
        $this->render('home/index', ['user' => $user]);
    }
}
