<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenjualanModel extends Model
{
    use HasFactory;
    protected $table = 't_penjualan';
    protected $primaryKey = 'penjualan_id';

    protected $fillable = ['user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal'];

    protected $casts = [
        'penjualan_tanggal' => 'datetime',
    ];

    // Relasi dengan tabel m_user (many-to-one).
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    // Relasi dengan tabel t_penjualan_detail (one-to-many).
    public function penjualanDetail(): HasMany
    {
        return $this->hasMany(PenjualanDetailModel::class, 'penjualan_id');
    }
}
