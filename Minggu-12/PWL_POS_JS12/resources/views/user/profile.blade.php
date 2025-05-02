<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>
</head>
<body>
    <h1>Profil User</h1>
    <p>ID: {{ $id }}</p>
    <p>Nama: {{ $name }}</p>
    <button><a href="{{ route('home') }}">Home</a></button>
</body>
</html>