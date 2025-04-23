<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierModel extends Model
{
    protected $table = 'm_supplier'; //Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'supplier_id'; // Mendefinisikan primary key dari tabel yang digunakan

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['supplier_kode', 'supplier_nama', 'supplier_alamat'];

    // Relasi dengan tabel t_stok (one-to-many).
    public function stok(): HasMany
    {
        return $this->hasMany(StokModel::class, 'supplier_id');
    }
}
