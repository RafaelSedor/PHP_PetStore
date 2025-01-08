<?php

namespace App\Controllers;

use App\Models\Product;
use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::check() ? Auth::user() : null;
        $products = Product::all();
        $this->render('home/index', ['user' => $user, 'products' => $products]);
    }
}
