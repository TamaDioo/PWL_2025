<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postRegister']);

Route::middleware(['auth'])->group(function () { // artinya semua route di dalam group ini harus login dulu

    // masukkan semua route yang perlu autentikasi di sini
    Route::get('/', [WelcomeController::class, 'index']);
    // Menu Data User hanya bisa diakses oleh administrator (ADM)
    Route::middleware(['authorize:ADM'])->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', [UserController::class, 'index']);          // menampilkan halaman awal user
            Route::post('/list', [UserController::class, 'list']);      // menampilkan data user untuk datatables (JSON)
            Route::get('/create', [UserController::class, 'create']);   // menampilkan halaman form tambah user
            Route::post('/', [UserController::class, 'store']);         // menyimpan data user baru
            Route::get('/create_ajax', [UserController::class, 'create_ajax']);  // Manampilkan halaman form tambah user Ajax
            Route::post('/ajax', [UserController::class, 'store_ajax']);         // Menyimpan data user baru Ajax
            Route::get('/{id}', [UserController::class, 'show']);       // manampilkan detail user
            Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);       // Manampilkan detail user Ajax
            Route::get('/{id}/edit', [UserController::class, 'edit']);  // menampilkan halaman form edit user
            Route::put('/{id}', [UserController::class, 'update']);     // menyimpan perubahan data user
            Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);      // Menampilkan halaman form edit user Ajax
            Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);  // Menyimpan perubahan data user Ajax
            Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);  // Untuk tampilkan form confirm delete user Ajax
            Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);  // Untuk hapus data user Ajax
            Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
        });
    });


    // artinya semua route di dalam group ini harus punya role ADM (Administrator) 
    Route::middleware(['authorize:ADM'])->group(function () {
        Route::get('level', [LevelController::class, 'index']);          // menampilkan halaman awal level
        Route::post('level/list', [LevelController::class, 'list']);      // menampilkan data level untuk datatables (JSON)
        Route::get('level/create', [LevelController::class, 'create']);   // menampilkan halaman form tambah level
        Route::post('level/', [LevelController::class, 'store']);         // menyimpan data level baru
        Route::get('level/create_ajax', [LevelController::class, 'create_ajax']);  // Manampilkan halaman form tambah level Ajax
        Route::post('level/ajax', [LevelController::class, 'store_ajax']);         // Menyimpan data level baru Ajax
        Route::get('level/{id}', [LevelController::class, 'show']);       // manampilkan detail level
        Route::get('level/{id}/show_ajax', [LevelController::class, 'show_ajax']);       // Manampilkan detail level Ajax
        Route::get('level/{id}/edit', [LevelController::class, 'edit']);  // menampilkan halaman form edit level
        Route::put('level/{id}', [LevelController::class, 'update']);     // menyimpan perubahan data level
        Route::get('level/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);      // Menampilkan halaman form edit level Ajax
        Route::put('level/{id}/update_ajax', [LevelController::class, 'update_ajax']);  // Menyimpan perubahan data level Ajax
        Route::get('level/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);  // Untuk tampilkan form confirm delete level Ajax
        Route::delete('level/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);  // Untuk hapus data level Ajax
        Route::delete('level/{id}', [LevelController::class, 'destroy']); // menghapus data level
    });

    // Menu Kategori Barang hanya bisa diakses oleh administrator (ADM) dan Manager (MNG) saja
    Route::middleware(['authorize:ADM,MNG'])->group(function () {
        Route::group(['prefix' => 'kategori'], function () {
            Route::get('/', [KategoriController::class, 'index']);          // menampilkan halaman awal kategori
            Route::post('/list', [KategoriController::class, 'list']);      // menampilkan data kategori untuk datatables (JSON)
            Route::get('/create', [KategoriController::class, 'create']);   // menampilkan halaman form tambah kategori
            Route::post('/', [KategoriController::class, 'store']);         // menyimpan data kategori baru
            Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);  // Manampilkan halaman form tambah kategori Ajax
            Route::post('/ajax', [KategoriController::class, 'store_ajax']);         // Menyimpan data kategori baru Ajax
            Route::get('/{id}', [KategoriController::class, 'show']);       // manampilkan detail kategori
            Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']);       // Manampilkan detail level Ajax
            Route::get('/{id}/edit', [KategoriController::class, 'edit']);  // menampilkan halaman form edit kategori
            Route::put('/{id}', [KategoriController::class, 'update']);     // menyimpan perubahan data kategori
            Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);      // Menampilkan halaman form edit kategori Ajax
            Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);  // Menyimpan perubahan data kategori Ajax
            Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);  // Untuk tampilkan form confirm delete kategori Ajax
            Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);  // Untuk hapus data kategori Ajax
            Route::delete('/{id}', [KategoriController::class, 'destroy']); // menghapus data kategori
        });
    });

    // Menu Supplier Barang hanya bisa diakses oleh administrator (ADM) dan manager (MNG)
    Route::middleware(['authorize:ADM,MNG'])->group(function () {
        Route::group(['prefix' => 'supplier'], function () {
            Route::get('/', [SupplierController::class, 'index']);                          // menampilkan halaman awal supplier
            Route::post('/list', [SupplierController::class, 'list']);                      // menampilkan data supplier untuk datatables (JSON)
            Route::get('/create', [SupplierController::class, 'create']);                   // menampilkan halaman form tambah supplier
            Route::post('/', [SupplierController::class, 'store']);                         // menyimpan data supplier baru
            Route::get('/create_ajax', [SupplierController::class, 'create_ajax']);         // Manampilkan halaman form tambah supplier Ajax
            Route::post('/ajax', [SupplierController::class, 'store_ajax']);                // Menyimpan data supplier baru Ajax
            Route::get('/{id}', [SupplierController::class, 'show']);                       // manampilkan detail supplier
            Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']);        // Manampilkan detail supplier Ajax
            Route::get('/{id}/edit', [SupplierController::class, 'edit']);                  // menampilkan halaman form edit supplier
            Route::put('/{id}', [SupplierController::class, 'update']);                     // menyimpan perubahan data supplier
            Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']);        // Menampilkan halaman form edit supplier Ajax
            Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']);    // Menyimpan perubahan data supplier Ajax
            Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']);   // Untuk tampilkan form confirm delete supplier Ajax
            Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']);  // Untuk hapus data supplier Ajax
            Route::delete('/{id}', [SupplierController::class, 'destroy']); // menghapus data supplier
        });
    });

    // artinya semua route di dalam group ini harus punya role ADM (Administrator), STF (Staff), dan MNG (Manager)
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        Route::get('barang/', [BarangController::class, 'index']);                          // menampilkan halaman awal barang
        Route::post('barang/list', [BarangController::class, 'list']);                      // menampilkan data barang untuk datatables (JSON)
        Route::get('barang/create', [BarangController::class, 'create']);                   // menampilkan halaman form tambah barang
        Route::post('barang/', [BarangController::class, 'store']);                         // menyimpan data barang baru
        Route::get('barang/create_ajax', [BarangController::class, 'create_ajax']);         // Manampilkan halaman form tambah barang Ajax
        Route::post('barang/ajax', [BarangController::class, 'store_ajax']);                // Menyimpan data barang baru Ajax
        Route::get('barang/{id}', [BarangController::class, 'show']);                       // manampilkan detail barang
        Route::get('barang/{id}/show_ajax', [BarangController::class, 'show_ajax']);        // Manampilkan detail barang Ajax
        Route::get('barang/{id}/edit', [BarangController::class, 'edit']);                  // menampilkan halaman form edit barang
        Route::put('barang/{id}', [BarangController::class, 'update']);                     // menyimpan perubahan data barang
        Route::get('barang/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);        // Menampilkan halaman form edit barang Ajax
        Route::put('barang/{id}/update_ajax', [BarangController::class, 'update_ajax']);    // Menyimpan perubahan data barang Ajax
        Route::get('barang/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);   // Untuk tampilkan form confirm delete barang Ajax
        Route::delete('barang/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);  // Untuk hapus data supplier Ajax
        Route::delete('barang/{id}', [BarangController::class, 'destroy']); // menghapus data barang
    });

    // Route::get('/', [HomeController::class, 'index'])->name('home');

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

    // Route di bawah ini harus dinonaktifkan agar tidak konflik dengan route untuk halaman home
    // Route::get('/', function () {
    //     return view('welcome');
    // });

    // Route::get('/level', [LevelController::class, 'index']);
    // Route::get('/kategori', [KategoriController::class, 'index']);
    // Route::get('/user', [UserController::class, 'index']);
    // Route::get('/user/tambah', [UserController::class, 'tambah']);
    Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
    Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
    Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
    Route::get('/user/hapus/{id}', [UserController::class, 'hapus']);
});
