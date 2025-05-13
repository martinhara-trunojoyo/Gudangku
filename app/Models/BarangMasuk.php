<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';
    protected $primaryKey = 'barang_masuk_id';
    
    protected $fillable = [
        'jumlah_masuk',
        'tanggal_masuk',
        'supplier_id',
        'barang_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_masuk' => 'datetime',
    ];

    /**
     * Get the barang that is associated with this incoming stock.
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    /**
     * Get the supplier that is associated with this incoming stock.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Get the user who recorded this incoming stock.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
