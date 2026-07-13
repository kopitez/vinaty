<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    use HasFactory;

    protected $table = 'stok_keluar';
    protected $primaryKey = 'id_keluar';

    protected $fillable = [
        'id_masuk',
        'id_produk',
        'jumlah_keluar',
        'tanggal_keluar',
        'id_user',
        'keterangan'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function stokMasuk()
    {
        return $this->belongsTo(StokMasuk::class, 'id_masuk', 'id_masuk');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
