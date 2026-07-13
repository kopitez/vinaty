<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Nugget'],
            ['nama_kategori' => 'Sosis'],
            ['nama_kategori' => 'Bakso'],
            ['nama_kategori' => 'Kentang Goreng'],
            ['nama_kategori' => 'Kebab & Burger'],
        ];

        foreach ($categories as $cat) {
            Kategori::create($cat);
        }
    }
}
