<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriModel extends Model
{
    protected $table = 'm_kategori'; //Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'kategori_id'; // Mendefinisikan primary key dari tabel yang digunakan

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['kategori_kode', 'kategori_nama'];

    // Relasi dengan tabel m_barang (one-to-many).
    public function barang(): HasMany
    {
        return $this->hasMany(BarangModel::class, 'kategori_id');
    }
}
