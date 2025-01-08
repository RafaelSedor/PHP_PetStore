<?php

use Core\Router\Route;
use App\Middleware\AdminMiddleware;
use App\Middleware\Authenticate;
use App\Middleware\UserProfileMiddleware;
use App\Controllers\ProductsController;
use App\Controllers\ShoppingListController;

Route::get('/', ['App\Controllers\HomeController', 'index']);

Route::get('/login', ['App\Controllers\AuthController', 'showLoginForm']);
Route::post('/login', ['App\Controllers\AuthController', 'login']);
Route::get('/logout', ['App\Controllers\AuthController', 'logout']);
Route::get('/user/create', ['App\Controllers\UserController', 'create']);
Route::post('/user/store', ['App\Controllers\UserController', 'store']);

Route::get('/admin/dashboard', ['App\Controllers\AdminController', 'index'])->addMiddleware(new AdminMiddleware());

Route::get('/users', ['App\Controllers\UserController', 'index'])->addMiddleware(new AdminMiddleware());
Route::get('/user/{id}', ['App\Controllers\UserController', 'show'])->addMiddleware(new UserProfileMiddleware());
Route::get('/user/{id}/edit', ['App\Controllers\UserController', 'edit'])->addMiddleware(new AdminMiddleware());
Route::post('/user/update', ['App\Controllers\UserController', 'update'])->addMiddleware(new AdminMiddleware());
Route::post('/user/{id}/delete', ['App\Controllers\UserController', 'delete'])->addMiddleware(new AdminMiddleware());

Route::get('/user/{id}/pets', ['App\Controllers\PetsController', 'index'])->addMiddleware(new Authenticate());
Route::get('/pets/create', ['App\Controllers\PetsController', 'create'])->addMiddleware(new Authenticate());
Route::post('/pets/store', ['App\Controllers\PetsController', 'store'])->addMiddleware(new Authenticate());
Route::get('/pets/{id}/edit', ['App\Controllers\PetsController', 'edit'])->addMiddleware(new Authenticate());
Route::post('/pets/{id}/update', ['App\Controllers\PetsController', 'update'])->addMiddleware(new Authenticate());
Route::post('/pets/{id}/delete', ['App\Controllers\PetsController', 'delete'])->addMiddleware(new Authenticate());

Route::get('/admin/products', [ProductsController::class, 'index'])->addMiddleware(new AdminMiddleware());
Route::get('/admin/products/create', [ProductsController::class, 'create'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/products', [ProductsController::class, 'store'])->addMiddleware(new AdminMiddleware());
Route::get('/admin/products/{id}/edit', [ProductsController::class, 'edit'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/products/{id}/update', [ProductsController::class, 'update'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/products/{id}/delete', [ProductsController::class, 'delete'])->addMiddleware(new AdminMiddleware());

Route::get('/admin/shopping-lists', [ShoppingListController::class, 'index'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/shopping-lists/{id}/delete', [ShoppingListController::class, 'delete'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/shopping-lists/{id}/finalize', [ShoppingListController::class, 'finalize'])->addMiddleware(new AdminMiddleware());

Route::get('/user/shopping-lists', [ShoppingListController::class, 'userOrders'])->addMiddleware(new Authenticate());
Route::post('/user/shopping-lists/{id}/delete', [ShoppingListController::class, 'delete'])->addMiddleware(new Authenticate());


Route::get('/user/{id}/cart', ['App\Controllers\CartController', 'index'])->addMiddleware(new Authenticate());
Route::post('/user/{id}/cart/add', ['App\Controllers\CartController', 'add'])->addMiddleware(new Authenticate());
Route::post('/user/{id}/cart/remove', ['App\Controllers\CartController', 'remove'])->addMiddleware(new Authenticate());
Route::post('/user/{id}/cart/checkout', ['App\Controllers\CartController', 'checkout'])->addMiddleware(new Authenticate());


Route::get('/admin/categories', ['App\Controllers\CategoriesController', 'index'])->addMiddleware(new AdminMiddleware());
Route::get('/admin/categories/create', ['App\Controllers\CategoriesController', 'create'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/categories/store', ['App\Controllers\CategoriesController', 'store'])->addMiddleware(new AdminMiddleware());
Route::get('/admin/categories/{id}/edit', ['App\Controllers\CategoriesController', 'edit'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/categories/{id}/update', ['App\Controllers\CategoriesController', 'update'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/categories/{id}/delete', ['App\Controllers\CategoriesController', 'delete'])->addMiddleware(new AdminMiddleware());
