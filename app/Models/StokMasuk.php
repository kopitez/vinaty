<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;

    protected $table = 'stok_masuk';
    protected $primaryKey = 'id_masuk';

    protected $fillable = [
        'id_produk',
        'jumlah_masuk',
        'tanggal_masuk',
        'tanggal_kadaluarsa',
        'status_kadaluarsa',
        'id_user',
        'keterangan'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function stokKeluars()
    {
        return $this->hasMany(StokKeluar::class, 'id_masuk', 'id_masuk');
    }

    // Helper accessor for dynamic remaining stock computation
    public function getSisaStokAttribute()
    {
        $released = $this->stokKeluars()->sum('jumlah_keluar');
        return $this->jumlah_masuk - $released;
    }
}
