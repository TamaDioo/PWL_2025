<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LevelModel extends Model
{
    protected $table = 'm_level'; //Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'level_id'; // Mendefinisikan primary key dari tabel yang digunakan

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = ['level_kode', 'level_nama'];

    // Relasi dengan tabel m_user (one-to-many).
    public function user(): HasMany
    {
        return $this->hasMany(UserModel::class, 'level_id');
    }
}
