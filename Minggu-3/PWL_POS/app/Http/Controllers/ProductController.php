<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Method foodBeverage() menampilkan halaman produk food & beverage
    public function foodBeverage()
    {
        return view('products.food-beverage');
    }

    // Method beautyHealth() menampilkan halaman produk beauty & health
    public function beautyHealth()
    {
        return view('products.beauty-health');
    }

    // Method homeCare() menampilkan halaman produk home care
    public function homeCare()
    {
        return view('products.home-care');
    }

    // Method babyKid() menampilkan halaman produk baby & kid
    public function babyKid()
    {
        return view('products.baby-kid');
    }
}
