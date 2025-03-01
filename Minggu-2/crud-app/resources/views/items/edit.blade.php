<!DOCTYPE html>
<html>
<head>
    <title>Edit Item</title>
</head>
<body>
    <h1>Edit Buku</h1> {{-- Menampilkan heading halaman dengan h1. --}}
    <form action="{{ route('items.update', $item) }}" method="POST"> {{-- Form akan diarahkan ke route  'items.update' untuk mengupdate data buku --}}
        @csrf {{-- Token CSRF untuk keamanan --}}
        @method('PUT') {{-- Method PUT untuk update data --}}
        <label for="judul">Judul:</label> {{-- Label untuk input judul --}}
        <input type="text" name="judul" value="{{ $item->judul }}" required> {{-- Inputan judul dengan nilai awal dari $item->judul --}}
        <br> {{-- Baris baru --}}
        <label for="penulis">Penulis:</label> {{-- Label untuk input penulis --}}
        <input type="text" name="penulis" value="{{ $item->penulis }}" required> {{-- Inputan penulis dengan nilai awal dari $item->penulis --}}
        <br> {{-- Baris baru --}}
        <label for="penerbit">Penerbit:</label> {{-- Label untuk input penerbit --}}
        <input type="text" name="penerbit" value="{{ $item->penerbit }}" required> {{-- Inputan penerbit dengan nilai awal dari $item->penerbit --}}
        <br> {{-- Baris baru --}}
        <label for="tahun_terbit">Tahun Terbit:</label> {{-- Label untuk input tahun terbit --}}
        <input type="number" name="tahun_terbit" value="{{ $item->tahun_terbit }}" required> {{-- Inputan tahun terbit dengan nilai awal dari $item->tahun_terbit --}}
        <br> {{-- Baris baru --}}
        <label for="isbn">ISBN:</label> {{-- Label untuk input isbn --}}
        <input type="text" name="isbn" value="{{ $item->isbn }}" required> {{-- Inputan isbn dengan nilai awal dari $item->isbn --}}
        <br> {{-- Baris baru --}}
        <label for="kategori">Kategori:</label> {{-- Label untuk input kategori --}}
        <input type="text" name="kategori" value="{{ $item->kategori }}" required> {{-- Inputan kategori dengan nilai awal dari $item->kategori --}}
        <br>{{-- Baris baru --}}
        <label for="deskripsi">Deskripsi:</label> {{-- Label untuk input deskripsi --}}
        <textarea name="deskripsi" required>{{ $item->deskripsi }}</textarea> {{-- Inputan deskripsi dengan nilai awal dari $item->deskripsi --}}
        <br> {{-- Baris baru --}}
        <button type="submit">Update Buku</button> {{-- Tombol submit untuk mengupdate data buku --}}
    </form>
    <a href="{{ route('items.index') }}">Kembali ke List</a> {{-- Link untuk kembali ke list buku (view index)--}}
</body>
</html>