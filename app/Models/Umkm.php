<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    use HasFactory;

    protected $table = 'umkm';
    protected $primaryKey = 'umkm_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_umkm',
        'pemilik',
        'alamat',
        'kontak',
        'user_id',
    ];

    /**
     * Relasi: UMKM milik satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relasi: UMKM memiliki banyak supplier
     */
    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'umkm_id', 'umkm_id');
    }
    
    /**
     * Relasi: UMKM memiliki banyak kategori
     */
    public function kategori()
    {
        return $this->hasMany(Kategori::class, 'umkm_id', 'umkm_id');
    }
    
    /**
     * Relasi: UMKM memiliki banyak barang
     */
    public function barang()
    {
        return $this->hasMany(Barang::class, 'umkm_id', 'umkm_id');
    }
}
