<?php

use Illuminate\Database\Migrations\Migration; //Class Migration untuk mengelola migrasi database.
use Illuminate\Database\Schema\Blueprint; //Class Blueprint untuk mendefinisikan struktur tabel.
use Illuminate\Support\Facades\Schema; //Class Schema untuk membuat, mengubah, atau menghapus tabel.

return new class extends Migration
{
    /**
     * Method up() digunakan untuk membuat tabel items.
     */
    public function up(): void //Method up() memiliki nilai kembalian void (tidak mengembalikan nilai).
    {
        Schema::create('items', function (Blueprint $table) { // Sintaks ini akan membuat tabel items di database crud_db.
            $table->id(); //Menambahkan kolom id 
            $table->string('judul'); //Menambahkan kolom judul bertipe string.
            $table->string('penulis'); //Menambahkan kolom penulis bertipe string.
            $table->string('penerbit'); //Menambahkan kolom penerbit bertipe string.
            $table->year('tahun_terbit'); //Menambahkan kolom tahun_terbit bertipe year.
            $table->string('isbn')->unique(); //Menambahkan kolom isbn bertipe string dan method unique() menandakan bahwa kolom isbn harus unik untuk menghindari duplikasi.
            $table->string('kategori'); //Menambahkan kolom kategori bertipe string.
            $table->text('deskripsi'); //Menambahkan kolom deskripsi bertipe text untuk menyimpan teks yang panjang.
            $table->timestamps(); //Membuat kolom created_at & updated_at secara otomatis.
        });
    }

    /**
     * Method down() digunakan menghapus tabel items.
     */
    public function down(): void //Method down() memiliki nilai kembalian void (tidak mengembalikan nilai).
    {
        Schema::dropIfExists('items'); //Menghapus tabel items jika tabel sudah ada.
    }
};
