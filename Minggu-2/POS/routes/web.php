<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;

// Route untuk Halaman Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk Halaman Products menggunakan Route Prefix
Route::prefix('category')->group(function () {
    Route::get('/food-beverage', [ProductController::class, 'foodBeverage'])->name('products.food-beverage');
    Route::get('/beauty-health', [ProductController::class, 'beautyHealth'])->name('products.beauty-health');
    Route::get('/home-care', [ProductController::class, 'homeCare'])->name('products.home-care');
    Route::get('/baby-kid', [ProductController::class, 'babyKid'])->name('products.baby-kid');
});

// Route untuk Halaman User menggunakan Route Param 'id' dan 'name'
Route::get('/user/{id}/name/{name}', [UserController::class, 'profile'])->name('user.profile');

// Route untuk Halaman Penjualan
Route::get('/sales', [SalesController::class, 'index'])->name('sales');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route di bawah ini harus dinonaktifkan agar tidak konflik dengan route untuk halaman home
// Route::get('/', function () {
//     return view('welcome');
// });
