<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang'; // optional, kalau nama tabel sama dengan nama model plural
    protected $primaryKey = 'barang_id'; // INI WAJIB ADA kalau bukan 'id'
    

    protected $fillable = [
        'nama_barang',
        'stok',
        'kategori_id',
        'satuan',
        'lokasi_gudang',
        'batas_minimum',
        'umkm_id',
    ];
}
