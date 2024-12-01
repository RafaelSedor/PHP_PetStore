<?php

use App\Middleware\AdminMiddleware;
use Core\Router\Route;

Route::get('/', ['App\Controllers\HomeController', 'index']);

Route::get('/login', ['App\Controllers\AuthController', 'showLoginForm']);
Route::post('/login', ['App\Controllers\AuthController', 'login']);
Route::get('/logout', ['App\Controllers\AuthController', 'logout']);
Route::get('/user/create', ['App\Controllers\UserController', 'create']);
Route::post('/user/store', ['App\Controllers\UserController', 'store']);

Route::get('/admin/dashboard', ['App\Controllers\AdminController', 'index'])
    ->addMiddleware(new AdminMiddleware());

Route::get('/users', ['App\Controllers\UserController', 'index'])->addMiddleware(new AdminMiddleware());
Route::get('/user/{id}', ['App\Controllers\UserController', 'show'])->addMiddleware(new AdminMiddleware());
Route::get('/user/{id}/edit', ['App\Controllers\UserController', 'edit'])->addMiddleware(new AdminMiddleware());
Route::post('/user/update', ['App\Controllers\UserController', 'update'])->addMiddleware(new AdminMiddleware());
Route::post('/user/{id}/delete', ['App\Controllers\UserController', 'delete'])->addMiddleware(new AdminMiddleware());
    
