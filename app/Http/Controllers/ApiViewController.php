<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiViewController extends Controller
{
    public function index()
    {
        return view('api-view.index');
    }
} 