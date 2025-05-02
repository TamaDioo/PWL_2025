<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesController extends Controller
{
    // Method index() menampilkan halaman transaksi POS
    public function index()
    {
        return view('sales.index');
    }
}
