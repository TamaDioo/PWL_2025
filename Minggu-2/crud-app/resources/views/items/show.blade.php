<!DOCTYPE html>
<html>
<head>
    <title>List Buku</title>
</head>
<body>
    <h1>List Buku</h1> {{-- Menampilkan heading halaman dengan h1. --}}
    {{-- Menampilkan pesan sukses. --}}
    @if (@session('success'))
        <p>{{ session('success') }}</p>
    @endif
    {{-- Membuat tombol tambah buku dan akan diarahkan ke route('items.create'). --}}
    <a href="{{ route('items.create') }}">Tambah Buku</a> 
    <ul>
        @foreach ($items as $item) {{-- Perulangan foreach untuk menampilkan daftar buku. --}}
            <li>
                Judul : {{ $item->judul }} <br> {{-- Menampilkan judul buku --}}
                Penulis : {{ $item->penulis }} <br> {{-- Menampilkan penulis buku --}}
                Penerbit : {{ $item->penerbit }} <br> {{-- Menampilkan penerbit buku --}}
                Tahun Terbit : {{ $item->tahun_terbit }} <br> {{-- Menampilkan tahun terbit buku --}}
                ISBN : {{ $item->isbn }} <br> {{-- Menampilkan ISBN buku --}}
                Kategori : {{ $item->kategori }} <br> {{-- Menampilkan kategori buku --}}
                Deskripsi : {{ $item->deskripsi }} <br> {{-- Menampilkan deskripsi --}}
                <a href="{{ route('items.edit', $item) }}">Edit</a> {{-- Link ke halaman edit --}}
                {{-- Form untuk menghapus buku. --}}
                <form action="{{ route('items.destroy', $item) }}" method="POST" style="display: inline">
                    @csrf {{-- Token CSRF untuk keamanan --}}
                    @method('DELETE') {{-- Method DELETE untuk menghapus data --}}
                    <button type="submit">Delete</button> {{-- Tombol hapus --}}
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>