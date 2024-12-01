<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $this->render('admin/dashboard');
    }
}
