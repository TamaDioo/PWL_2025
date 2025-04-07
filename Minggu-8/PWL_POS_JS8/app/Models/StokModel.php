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
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah'];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(KategoriModel::class, 'supplier_id', 'supplier_id');
    }
}
