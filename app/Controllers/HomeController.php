<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::check() ? Auth::user() : null;
        $products = Product::all();
        $categories = Category::all();

        $search = $request->getParam('search');
        $categoryFilter = $request->getParam('category');

        if ($search) {
            $products = array_filter($products, function ($product) use ($search) {
                return stripos($product->name, $search) !== false;
            });
        }

        if ($categoryFilter) {
            $products = array_filter($products, function ($product) use ($categoryFilter) {
                $categoryIds = array_column($product->categories, 'id');
                return in_array($categoryFilter, $categoryIds);
            });
        }

        $this->render('home/index', [
            'user' => $user,
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
