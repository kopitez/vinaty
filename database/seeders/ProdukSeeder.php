<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nugget = Kategori::where('nama_kategori', 'Nugget')->first();
        $sosis = Kategori::where('nama_kategori', 'Sosis')->first();
        $bakso = Kategori::where('nama_kategori', 'Bakso')->first();
        $kentang = Kategori::where('nama_kategori', 'Kentang Goreng')->first();
        $kebab = Kategori::where('nama_kategori', 'Kebab & Burger')->first();

        $products = [
            [
                'nama_produk' => 'Nugget Ayam Premium',
                'id_kategori' => $nugget->id,
                'merek' => 'Fiesta',
                'satuan' => 'Pcs',
                'keterangan' => 'Nugget ayam olahan berkualitas tinggi rasa premium.'
            ],
            [
                'nama_produk' => 'Sosis Sapi Bakar Jumbo',
                'id_kategori' => $sosis->id,
                'merek' => 'Kanzler',
                'satuan' => 'Pcs',
                'keterangan' => 'Sosis sapi tebal cocok untuk dibakar maupun digoreng.'
            ],
            [
                'nama_produk' => 'Bakso Sapi Premium SB10',
                'id_kategori' => $bakso->id,
                'merek' => 'Sumber Selera',
                'satuan' => 'Pack',
                'keterangan' => 'Bakso sapi legendaris isi 50 butir.'
            ],
            [
                'nama_produk' => 'Kentang Goreng Shoestring 1Kg',
                'id_kategori' => $kentang->id,
                'merek' => 'Aviko',
                'satuan' => 'Pack',
                'keterangan' => 'Kentang beku potongan lurus renyah luar lembut dalam.'
            ],
            [
                'nama_produk' => 'Daging Kebab Lembaran',
                'id_kategori' => $kebab->id,
                'merek' => 'Sahara',
                'satuan' => 'Pack',
                'keterangan' => 'Daging sapi olahan lembaran siap panggang untuk isian kebab.'
            ],
        ];

        foreach ($products as $prod) {
            // Note: the model's booted event will automatically generate id_produk format P001, P002...
            Produk::create($prod);
        }
    }
}
