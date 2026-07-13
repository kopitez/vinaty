<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $today = Carbon::today();

        // Query all incoming stock, fetch related products, and sum outgoing stock
        $batches = StokMasuk::with('produk')
            ->withSum('stokKeluars', 'jumlah_keluar')
            ->when($status, function ($query, $status) {
                return $query->where('status_kadaluarsa', $status);
            })
            ->get()
            ->map(function ($batch) use ($today) {
                $used = $batch->stok_keluars_sum_jumlah_keluar ?? 0;
                $batch->sisa_stok = $batch->jumlah_masuk - $used;

                $expDate = Carbon::parse($batch->tanggal_kadaluarsa);
                $batch->sisa_hari = $today->diffInDays($expDate, false);
                return $batch;
            })
            // Only monitor active batches (batches that still have remaining stock)
            ->filter(function ($batch) {
                return $batch->sisa_stok > 0;
            })
            ->sortBy(function ($batch) {
                // Sort by expiration date
                return $batch->tanggal_kadaluarsa;
            })
            ->values();

        return view('monitoring.index', compact('batches', 'status'));
    }
}
