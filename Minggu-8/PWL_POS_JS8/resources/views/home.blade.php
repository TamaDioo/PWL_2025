<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home Page</title>
</head>
<body>
    <h1>Selamat datang di aplikasi Point of Sales</h1>
    Ini adalah halaman home
    <ul>
        <li><a href="{{ route('products.food-beverage') }}">Halaman Produk Food & Beverage</a></li>
        <li><a href="{{ route('products.beauty-health') }}">Halaman Produk Beauty & Health</a></li>
        <li><a href="{{ route('products.home-care') }}">Halaman Produk Home Care</a></li>
        <li><a href="{{ route('products.baby-kid') }}">Halaman Produk Baby & Kid</a></li>
        <li><a href="{{ route('user.profile', ['id' => 1, 'name' => 'Dio']) }}">Profil User</a></li>
        <li><a href="{{ route('sales') }}">Halaman Penjualan</a></li>
    </ul>
</body>
</html>