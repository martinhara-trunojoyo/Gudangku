<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';
    protected $fillable = [
        'nama_supplier',
        'alamat_supplier',
        'kontak_supplier',
        'umkm_id',
    ];

    /**
     * Relasi: Supplier milik satu UMKM
     */
    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id', 'umkm_id');
    }
}
