<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::check() ? Auth::user() : null;

        $products = Product::all();
        $services = Service::all();
        $categories = Category::all();

        if ($user) {
            $user->pets = $user->getPets();
        }

        $search = $request->getParam('search');
        $categoryFilter = $request->getParam('category');

        if ($search) {
            $products = array_filter($products, function ($product) use ($search) {
                return stripos($product->name, $search) !== false;
            });

            $services = array_filter($services, function ($service) use ($search) {
                return stripos($service->name, $search) !== false;
            });
        }

        if ($categoryFilter) {
            $products = array_filter($products, function ($product) use ($categoryFilter) {
                $categoryIds = array_column($product->categories ?? [], 'id');
                return in_array($categoryFilter, $categoryIds);
            });
        }

        $this->render('home/index', [
            'user' => $user,
            'products' => array_values($products),
            'services' => array_values($services),
            'categories' => $categories,
        ]);
    }

    public function search(Request $request)
    {
        $name = $request->getParam('name');
        $category = $request->getParam('category');

        $products = Product::all();
        $services = Service::all();

        if ($name) {
            $products = array_filter($products, function ($product) use ($name) {
                return stripos($product->name, $name) !== false;
            });

            $services = array_filter($services, function ($service) use ($name) {
                return stripos($service->name, $name) !== false;
            });
        }

        if ($category) {
            $products = array_filter($products, function ($product) use ($category) {
                $categoryIds = array_column($product->categories ?? [], 'id');
                return in_array($category, $categoryIds);
            });
        }

        $this->renderJson([
            'products' => array_values($products),
            'services' => array_values($services),
        ]);
    }
}
