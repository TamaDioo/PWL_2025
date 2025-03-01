<?php

namespace App\Http\Controllers;

use App\Models\Item; // Class Item digunakan untuk mengakses tabel items di database crud_db.
use Illuminate\Http\Request; // Class Request untuk menangani request HTTP.

class ItemController extends Controller // Keyword 'extends' menunjukkan bahwa class ItemController merupakan turunan dari Controller
{
    /**
     * Menampilkan daftar semua item (buku).
     */
    public function index()
    {
        $items = Item::all(); //Mengambil semua data dari tabel items.
        return view('items.index', compact('items')); // Lalu mengirimkannya ke view 'items.index' dengan variabel 'items'.
    }

    /**
     * Method create() akan menampilkan form untuk menambahkan buku baru.
     */
    public function create()
    {
        return view('items.create'); // Menampilkan form penambahan buku baru.
    }

    public function store(Request $request) //Method store() akan menyimpan buku baru ke dalam database crud_db.
    {
        // Melakukan validasi terhadap inputan
        $request->validate([
            'judul' => 'required', //Judul wajib diisi.
            'penulis' => 'required', //Penulis wajib diisi.
            'penerbit' => 'required', //Penerbit wajib diisi.
            'tahun_terbit' => 'required', //Tahun terbit wajib diisi.
            'isbn' => 'required', //ISBN wajib diisi.
            'kategori' => 'required', //Kategori wajib diisi.
            'deskripsi' => 'required', //Deskripsi wajib diisi.
        ]);

        // Item::create($request->all());
        // return redirect()->route('items.index');

        //Hanya masukkan atribut yang diizinkan
        // Menyimpan data buku yang baru dimasukkan ke dalam database (hanya atribut yang diizinkan saja).
        Item::create($request->only(['judul', 'penulis', 'penerbit', 'tahun_terbit', 'isbn', 'kategori', 'deskripsi']));
        //Mengarahkan ke halaman index (list buku) dengan menampilkan pesan sukses.
        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item) //Method untuk menampilkan detail buku yang dikirim melalui parameter 'Item $item'.
    {
        return view('items.show', compact('item')); // Menampilkan buku yang dipilih.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item) //Method untuk menampilkan form untuk mengedit buku yang dikirim melalui parameter 'Item $item'.
    {
        return view('items.edit', compact('item')); // Menampilkan tampilan edit buku dengan data buku yang dipilih.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item) //Method untuk memperbarui data buku di dalam database.
    {
        $request->validate([  // Melakukan validasi terhadap inputan user.
            'judul' => 'required', //Judul tidak boleh kosong.
            'penulis' => 'required', //Penulis wajib diisi.
            'penerbit' => 'required', //Penerbit tidak boleh kosong.
            'tahun_terbit' => 'required', //Tahun terbit wajib diisi.
            'isbn' => 'required', //ISBN tidak boleh kosong.
            'kategori' => 'required', //Kategori wajib diisi.
            'deskripsi' => 'required', //Penulis tidak boleh kosong.
        ]);

        // $item->update($request->all());
        // return redirect()->route('items.index');

        //Hanya masukkan atribut yang diizinkan.
        // Memperbarui data buku hanya dengan atribut yang diizinkan saja.
        $item->update($request->only(['judul', 'penulis', 'penerbit', 'tahun_terbit', 'isbn', 'kategori', 'deskripsi']));
        //Mengarahkan ke halaman index (list buku) dengan menampilkan pesan sukses.
        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item) //Method untuk menghapus buku dari database crud_db.
    {
        // return redirect()->route('items.index');
        $item->delete(); // Menghapus buku yang dikirim melalui parameter 'Item $item' dari database.
        
        //Mengarahkan ke halaman index (list buku) dengan menampilkan pesan sukses.
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.'); 
    }
}
