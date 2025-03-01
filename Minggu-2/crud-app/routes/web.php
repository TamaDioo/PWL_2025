<?php

use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\ItemController; 

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


Route::get('/', function () { //Mengarahkan request ke URL '/' (root) 
    return view('welcome'); //Mngembalikan tampilan/view 'welcome'.
});

// Membuat semua route CRUD secara otomatis untuk resource 'items' menggunakan ItemController
Route::resource('items', ItemController::class);
