<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //Method index() akan menampilkan halaman home
    // dengan menggunakan view home
    public function index()
    {
        return view('home');
    }
}
