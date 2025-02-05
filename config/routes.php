<?php

use App\Controllers\AdminController;
use App\Controllers\AppointmentController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\CategoriesController;
use App\Controllers\HomeController;
use App\Controllers\PetsController;
use Core\Router\Route;
use App\Middleware\AdminMiddleware;
use App\Middleware\Authenticate;
use App\Middleware\UserProfileMiddleware;
use App\Controllers\ProductsController;
use App\Controllers\ServiceController;
use App\Controllers\ShoppingListController;
use App\Controllers\UserController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/api/products/search', [HomeController::class, 'search']);

Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/user/create', [UserController::class, 'create']);
Route::post('/user/store', [UserController::class, 'store']);

Route::get('/admin/dashboard', [AdminController::class, 'index'])->addMiddleware(new AdminMiddleware());

Route::get('/users', [UserController::class, 'index'])->addMiddleware(new AdminMiddleware());
Route::get('/user/{id}', [UserController::class, 'show'])->addMiddleware(new UserProfileMiddleware());
Route::get('/user/{id}/edit', [UserController::class, 'edit'])->addMiddleware(new AdminMiddleware());
Route::post('/user/update', [UserController::class, 'update'])->addMiddleware(new AdminMiddleware());
Route::post('/user/{id}/delete', [UserController::class, 'delete'])->addMiddleware(new AdminMiddleware());

Route::get('/user/{id}/pets', [PetsController::class, 'index'])->addMiddleware(new Authenticate());
Route::get('/pets/create', [PetsController::class, 'create'])->addMiddleware(new Authenticate());
Route::post('/pets/store', [PetsController::class, 'store'])->addMiddleware(new Authenticate());
Route::get('/pets/{id}/edit', [PetsController::class, 'edit'])->addMiddleware(new Authenticate());
Route::post('/pets/{id}/update', [PetsController::class, 'update'])->addMiddleware(new Authenticate());
Route::post('/pets/{id}/delete', [PetsController::class, 'delete'])->addMiddleware(new Authenticate());

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


Route::get('/user/{id}/cart', [CartController::class, 'index'])->addMiddleware(new Authenticate());
Route::post('/user/{id}/cart/add', [CartController::class, 'add'])->addMiddleware(new Authenticate());
Route::post('/user/{id}/cart/remove', [CartController::class, 'remove'])->addMiddleware(new Authenticate());
Route::post('/user/{id}/cart/checkout', [CartController::class, 'checkout'])->addMiddleware(new Authenticate());


Route::get('/admin/categories', [CategoriesController::class, 'index'])->addMiddleware(new AdminMiddleware());
Route::get('/admin/categories/create', [CategoriesController::class, 'create'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/categories/store', [CategoriesController::class, 'store'])->addMiddleware(new AdminMiddleware());
Route::get('/admin/categories/{id}/edit', [CategoriesController::class, 'edit'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/categories/{id}/update', [CategoriesController::class, 'update'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/categories/{id}/delete', [CategoriesController::class, 'delete'])->addMiddleware(new AdminMiddleware());

Route::get('/admin/services', [ServiceController::class, 'index'])->addMiddleware(new AdminMiddleware());
Route::get('/admin/services/create', [ServiceController::class, 'create'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/services', [ServiceController::class, 'store'])->addMiddleware(new AdminMiddleware());
Route::get('/admin/services/{id}/edit', [ServiceController::class, 'edit'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/services/{id}/update', [ServiceController::class, 'update'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/services/{id}/delete', [ServiceController::class, 'delete'])->addMiddleware(new AdminMiddleware());

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/user/services/track', [ServiceController::class, 'track'])->addMiddleware(new Authenticate());
Route::post('/appointments/request', [AppointmentController::class, 'request'])->addMiddleware(new Authenticate());

Route::get('/admin/appointments', [AppointmentController::class, 'index'])->addMiddleware(new AdminMiddleware());
Route::post('/admin/appointments/update', [AppointmentController::class, 'updateStatus'])->addMiddleware(new AdminMiddleware());
