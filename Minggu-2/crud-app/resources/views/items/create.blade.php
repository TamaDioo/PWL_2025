<!DOCTYPE html>
<html>
<head>
    <title>Add Item</title>
</head>
<body>
    <h1>Tambah Buku</h1> {{-- Menampilkan heading halaman dengan h1. --}}
    <form action="{{ route('items.store') }}" method="POST"> {{-- Form akan diarahkan ke route  'items.store' untuk menyimpan data --}}
        @csrf {{-- Token CSRF untuk keamanan --}}
        <label for="judul">Judul:</label> {{-- Label untuk input judul buku --}}
        <input type="text" name="judul" required> {{-- Input untuk judul buku --}}
        <br> {{-- Baris baru --}}
        <label for="penulis">Penulis:</label> {{-- Label untuk input penulis buku --}}
        <input type="text" name="penulis" required> {{-- Input untuk penulis --}}
        <br> {{-- Baris baru --}}
        <label for="penerbit">Penerbit:</label> {{-- Label untuk input penerbit buku --}}
        <input type="text" name="penerbit" required> {{-- Input untuk penerbit --}}
        <br> {{-- Baris baru --}}
        <label for="tahun_terbit">Tahun Terbit:</label> {{-- Label untuk input tahun terbit buku --}}
        <input type="number" name="tahun_terbit" required> {{-- Input untuk tahun terbit buku --}}
        <br> {{-- Baris baru --}}
        <label for="isbn">ISBN:</label> {{-- Label untuk input isbn buku --}}
        <input type="text" name="isbn" required> {{-- Input untuk isbn buku --}}
        <br> {{-- Baris baru --}}
        <label for="kategori">Kategori:</label> {{-- Label untuk input kategori buku --}}
        <input type="text" name="kategori" required> {{-- Input untuk kategori buku --}}
        <br> {{-- Baris baru --}}
        <label for="deskripsi">Deskripsi:</label> {{-- Label untuk input deskripsi buku --}}
        <textarea name="deskripsi" required></textarea> {{-- Input untuk deskripsi --}}
        <br> {{-- Baris baru --}}
        <button type="submit">Tambah Buku</button> {{-- Tombol submit untuk mengirim form yang telah diisi --}}
    </form>
    <a href="{{ route('items.index') }}">Kembali ke List</a> {{-- Link kembali ke daftar buku (view index) --}}
</body>
</html>