<?php

use Core\Router\Route;

Route::get('/', ['App\Controllers\HomeController', 'index']);

Route::get('/login', ['App\Controllers\AuthController', 'showLoginForm']);
Route::post('/login', ['App\Controllers\AuthController', 'login']);
Route::get('/logout', ['App\Controllers\AuthController', 'logout']);

Route::get('/admin/dashboard', ['App\Controllers\AdminController', 'index'])
    ->addMiddleware(new App\Middleware\Authenticate());

Route::get('/users', ['App\Controllers\UserController', 'index']);
Route::get('/user/{id}', ['App\Controllers\UserController', 'show']);
Route::get('/user/create', ['App\Controllers\UserController', 'create']);
Route::post('/user/store', ['App\Controllers\UserController', 'store']);
Route::get('/user/{id}/edit', ['App\Controllers\UserController', 'edit']);
Route::post('/user/update', ['App\Controllers\UserController', 'update']);
Route::post('/user/{id}/delete', ['App\Controllers\UserController', 'delete']);
