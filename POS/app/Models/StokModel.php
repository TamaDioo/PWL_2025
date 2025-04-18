<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokModel extends Model
{
    use HasFactory;

    protected $table = 't_stok'; //Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'stok_id'; // Mendefinisikan primary key dari tabel yang digunakan

    protected $casts = [
        'stok_tanggal' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah'];

    // Relasi dengan tabel m_supplier (many-to-one).
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(SupplierModel::class, 'supplier_id', 'supplier_id');
    }

    // Relasi dengan tabel m_user (many-to-one).
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    // Relasi dengan tabel m_barang (many-to-one).
    public function barang(): BelongsTo
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }
}
