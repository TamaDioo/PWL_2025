<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    //Perlu untuk mendeklarasikan atribut yang boleh diisi secara massal menggunakan $fillable dalam class Item.
    //$fillable berisi daftar atribut yang boleh diisi secara massal melalui mass assignment (Item::create($request->all()) atau update($request->all())).
    //Jika $fillable tidak diatur, Laravel akan mencegah mass assignment untuk mencegah perubahan data yang tidak diinginkan.
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'kategori',
        'deskripsi',
    ];
    
}
