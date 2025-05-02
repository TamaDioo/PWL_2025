<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BarangModel extends Model
{
    use HasFactory;

    protected $table = 'm_barang'; //Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'barang_id'; // Mendefinisikan primary key dari tabel yang digunakan
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'kategori_id',
        'barang_kode',
        'barang_nama',
        'harga_beli',
        'harga_jual',
        'image' //tambahkan image
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('storage/posts/' . $image),
        );
    }

    // Relasi dengan tabel m_kategori (many-to-one).
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }


    // Relasi dengan tabel t_stok (one-to-many).
    public function stok(): HasMany
    {
        return $this->hasMany(StokModel::class, 'barang_id');
    }

    // Relasi dengan tabel t_penjualan_detail (one-to-many).
    public function penjualanDetail(): HasMany
    {
        return $this->hasMany(PenjualanDetailModel::class, 'barang_id');
    }
}
