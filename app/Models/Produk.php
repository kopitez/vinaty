<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_produk',
        'nama_produk',
        'id_kategori',
        'merek',
        'satuan',
        'keterangan'
    ];

    protected static function booted()
    {
        static::creating(function ($produk) {
            if (empty($produk->id_produk)) {
                $lastProduk = self::orderBy('id_produk', 'desc')->first();
                if ($lastProduk) {
                    $lastNumber = intval(substr($lastProduk->id_produk, 1));
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }
                $produk->id_produk = 'P' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function stokMasuks()
    {
        return $this->hasMany(StokMasuk::class, 'id_produk', 'id_produk');
    }

    public function stokKeluars()
    {
        return $this->hasMany(StokKeluar::class, 'id_produk', 'id_produk');
    }
}
