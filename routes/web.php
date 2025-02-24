<?php

use App\Http\Controllers\WelcomeController;
// use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PhotoController;

Route::resource('photos', PhotoController::class);
Route::get('/', HomeController::class);
Route::get('/about', AboutController::class);
Route::get('/articles/{id}', ArticleController::class);

Route::get('/hello', [WelcomeController::class, 'hello']);

Route::get('/world', function () {
    return 'World';
});

Route::get('/user/{name}', function ($name) {
    return 'Nama saya '. $name;
});

Route::get('/posts/{post}/comments/{comment}', function 
($postId, $commentId) {
    return 'Pos ke-'.$postId." Komentar ke-: ".$commentId;
});

Route::get('/user/{name?}', function ($name='John') {
    return 'Nama saya '.$name;
});

Route::resource('photos', PhotoController::class)->only([
    'index', 'show'
]);

Route::resource('photos', PhotoController::class)->except([
    'create', 'store', 'update', 'destroy'
]);

/*
// Contoh Route Name:
Route::get('/user/profile', function () {
    //
})->name('profile');

// Route::get(
//     '/user/profile',
//     [UserProfileController::class, 'show']
// )->name('profile');
    
// Generating URLs...
$url = route('profile');

// Generating Redirects...
return redirect()->route('profile');
*/

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

// Route di bawah ini harus di non aktifkan agar tidak konflik
// dengan route '/' yang baru.
// Route::get('/', function () {
//     return view('welcome');
// });

/* 
// Contoh Route Group:
Route::middleware(['first', 'second'])->group(function () {
    Route::get('/', function () {
        // Uses first & second middleware...
    });

Route::get('/user/profile', function () {
        // Uses first & second middleware...
    });
});

Route::domain('{account}.example.com')->group(function () {
    Route::get('user/{id}', function ($account, $id) {
        //
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/post', [PostController::class, 'index']);
    Route::get('/event', [EventController::class, 'index']);
});
*/

/*
// Contoh Route Prefixes
Route::prefix('admin')->group(function () {
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/post', [PostController::class, 'index']);
    Route::get('/event', [EventController::class, 'index']);
});
*/

/*
//Contoh Redirect Routes
Route::redirect('/here', '/there');

// Contoh View Routes
Route::view('/welcome', 'welcome');
Route::view('/welcome', 'welcome', ['name' => 'Taylor']);
*/