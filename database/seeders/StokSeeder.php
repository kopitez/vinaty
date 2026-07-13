<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use App\Models\User;
use App\Models\Produk;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();

        // 1. Seed Incoming Stocks (Batches)
        
        // Product P001 (Nugget Ayam Premium) - Batch A (Nearing Expiry)
        $batch1 = StokMasuk::create([
            'id_produk' => 'P001',
            'jumlah_masuk' => 20,
            'tanggal_masuk' => '2026-06-15',
            'tanggal_kadaluarsa' => '2026-06-25', // 4 days remaining (from June 21, 2026)
            'status_kadaluarsa' => 'mendekati',
            'id_user' => $admin->id,
            'keterangan' => 'Batch Supplier Cirebon (Nearing Expiry)'
        ]);

        // Product P001 (Nugget Ayam Premium) - Batch B (Safe Stock)
        $batch2 = StokMasuk::create([
            'id_produk' => 'P001',
            'jumlah_masuk' => 50,
            'tanggal_masuk' => '2026-06-20',
            'tanggal_kadaluarsa' => '2026-07-20', // 29 days remaining (from June 21, 2026)
            'status_kadaluarsa' => 'aman',
            'id_user' => $admin->id,
            'keterangan' => 'Batch Supplier Bandung Utama'
        ]);

        // Product P002 (Sosis Sapi Bakar Jumbo) - Batch C (Safe Stock)
        $batch3 = StokMasuk::create([
            'id_produk' => 'P002',
            'jumlah_masuk' => 40,
            'tanggal_masuk' => '2026-06-19',
            'tanggal_kadaluarsa' => '2026-07-19', // 28 days remaining (from June 21, 2026)
            'status_kadaluarsa' => 'aman',
            'id_user' => $admin->id,
            'keterangan' => 'Batch Supplier Jakarta Barat'
        ]);

        // Product P003 (Bakso Sapi Premium SB10) - Batch D (Nearing Expiry)
        $batch4 = StokMasuk::create([
            'id_produk' => 'P003',
            'jumlah_masuk' => 30,
            'tanggal_masuk' => '2026-06-16',
            'tanggal_kadaluarsa' => '2026-06-24', // 3 days remaining (from June 21, 2026)
            'status_kadaluarsa' => 'mendekati',
            'id_user' => $admin->id,
            'keterangan' => 'Batch Supplier Tangerang'
        ]);

        // Product P004 (Kentang Goreng Shoestring) - Batch E (Expired Stock)
        $batch5 = StokMasuk::create([
            'id_produk' => 'P004',
            'jumlah_masuk' => 15,
            'tanggal_masuk' => '2026-05-15',
            'tanggal_kadaluarsa' => '2026-06-15', // Expired 6 days ago (from June 21, 2026)
            'status_kadaluarsa' => 'kadaluarsa',
            'id_user' => $admin->id,
            'keterangan' => 'Batch Import Belanda (Expired)'
        ]);

        // Product P005 (Daging Kebab Lembaran) - Batch F (Expired Stock)
        $batch6 = StokMasuk::create([
            'id_produk' => 'P005',
            'jumlah_masuk' => 10,
            'tanggal_masuk' => '2026-05-10',
            'tanggal_kadaluarsa' => '2026-06-10', // Expired 11 days ago (from June 21, 2026)
            'status_kadaluarsa' => 'kadaluarsa',
            'id_user' => $admin->id,
            'keterangan' => 'Batch Lokal Sahara (Expired)'
        ]);

        // 2. Seed Outgoing Stocks demonstrating FEFO
        // Release 25 units of Product P001 (Nugget Ayam Premium)
        // Under FEFO: 
        // - Batch 1 (expires June 25) is nearest, takes all 20 units.
        // - Batch 2 (expires July 20) is next, takes remaining 5 units.

        StokKeluar::create([
            'id_masuk' => $batch1->id_masuk,
            'id_produk' => 'P001',
            'jumlah_keluar' => 20,
            'tanggal_keluar' => '2026-06-21',
            'id_user' => $admin->id,
            'keterangan' => 'Penjualan FEFO - Konsumsi Batch #1 Terdekat Expired'
        ]);

        StokKeluar::create([
            'id_masuk' => $batch2->id_masuk,
            'id_produk' => 'P001',
            'jumlah_keluar' => 5,
            'tanggal_keluar' => '2026-06-21',
            'id_user' => $admin->id,
            'keterangan' => 'Penjualan FEFO - Konsumsi Batch #2 Sisa Permintaan'
        ]);
    }
}
