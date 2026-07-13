<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'id_masuk',
        'pesan',
        'status_baca'
    ];

    protected $casts = [
        'status_baca' => 'boolean'
    ];

    public function stokMasuk()
    {
        return $this->belongsTo(StokMasuk::class, 'id_masuk', 'id_masuk');
    }
}
