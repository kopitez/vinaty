<?php

namespace App\Services;

use App\Models\StokMasuk;
use App\Models\StokKeluar;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FefoService
{
    /**
     * Get available batches for a product sorted by expiration date and intake date (FEFO).
     *
     * @param string $id_produk
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableBatches(string $id_produk)
    {
        return StokMasuk::where('id_produk', $id_produk)
            ->withSum('stokKeluars', 'jumlah_keluar')
            ->get()
            ->map(function ($batch) {
                $used = $batch->stok_keluars_sum_jumlah_keluar ?? 0;
                $batch->sisa_stok = $batch->jumlah_masuk - $used;
                return $batch;
            })
            ->filter(function ($batch) {
                return $batch->sisa_stok > 0;
            })
            ->sortBy(function ($batch) {
                // Sort by expiration date first, then by intake date (tanggal_masuk)
                return $batch->tanggal_kadaluarsa . '_' . $batch->tanggal_masuk;
            })
            ->values();
    }

    /**
     * Calculate total available stock for a product.
     *
     * @param string $id_produk
     * @return int
     */
    public function calculateAvailableStock(string $id_produk)
    {
        $batches = $this->getAvailableBatches($id_produk);
        return $batches->sum('sisa_stok');
    }

    /**
     * Release stock using FEFO strategy.
     *
     * @param string $id_produk
     * @param int $qtyToRelease
     * @param int $userId
     * @param string|null $keterangan
     * @param string $tanggalKeluar
     * @throws ValidationException
     * @return array Array of created StokKeluar records
     */
    public function releaseStock(string $id_produk, int $qtyToRelease, int $userId, ?string $keterangan, string $tanggalKeluar)
    {
        if ($qtyToRelease <= 0) {
            throw ValidationException::withMessages([
                'jumlah_keluar' => 'Jumlah keluar harus lebih dari 0.'
            ]);
        }

        return DB::transaction(function () use ($id_produk, $qtyToRelease, $userId, $keterangan, $tanggalKeluar) {
            $batches = $this->getAvailableBatches($id_produk);
            $totalAvailable = $batches->sum('sisa_stok');

            if ($totalAvailable < $qtyToRelease) {
                throw ValidationException::withMessages([
                    'jumlah_keluar' => "Stok tidak mencukupi. Stok tersedia: {$totalAvailable} unit, diminta: {$qtyToRelease} unit."
                ]);
            }

            $remainingQty = $qtyToRelease;
            $createdRecords = [];

            foreach ($batches as $batch) {
                if ($remainingQty <= 0) {
                    break;
                }

                $take = min($batch->sisa_stok, $remainingQty);

                $stokKeluar = StokKeluar::create([
                    'id_masuk' => $batch->id_masuk,
                    'id_produk' => $id_produk,
                    'jumlah_keluar' => $take,
                    'tanggal_keluar' => $tanggalKeluar,
                    'id_user' => $userId,
                    'keterangan' => $keterangan
                ]);

                $createdRecords[] = $stokKeluar;
                $remainingQty -= $take;
            }

            return $createdRecords;
        });
    }
}
