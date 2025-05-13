<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';
    
    protected $fillable = [
        'nama_kategori',
        'umkm_id'
    ];

    /**
     * Relasi: Kategori milik satu UMKM
     */
    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id', 'umkm_id');
    }

    /**
     * Relasi: Kategori memiliki banyak barang
     */
    public function barang()
    {
        return $this->hasMany(Barang::class, 'kategori_id', 'kategori_id');
    }
}
