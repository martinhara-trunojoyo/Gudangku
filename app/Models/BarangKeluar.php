<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';
    protected $primaryKey = 'barang_keluar_id';
    
    protected $fillable = [
        'jumlah_keluar',
        'tanggal_keluar',
        'tujuan',
        'barang_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_keluar' => 'datetime',
    ];

    /**
     * Get the barang that is associated with this outgoing stock.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    /**
     * Get the user who recorded this outgoing stock.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
