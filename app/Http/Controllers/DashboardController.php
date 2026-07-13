<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard home screen.
     */
    public function index()
    {
        $today = Carbon::today();

        // 1. Stat: Total Products count
        $totalProducts = Produk::count();

        // 2. Stat: Total Available Stock
        $totalMasuk = StokMasuk::sum('jumlah_masuk');
        $totalKeluar = StokKeluar::sum('jumlah_keluar');
        $totalAvailableStock = max(0, $totalMasuk - $totalKeluar);

        // 3. Stat: Batches nearing expiration (status: 'mendekati')
        // We only count batches that still have active stock left!
        $batchesMendekati = StokMasuk::where('status_kadaluarsa', 'mendekati')
            ->withSum('stokKeluars', 'jumlah_keluar')
            ->get()
            ->filter(function ($batch) {
                $used = $batch->stok_keluars_sum_jumlah_keluar ?? 0;
                return ($batch->jumlah_masuk - $used) > 0;
            })
            ->count();

        // 4. Stat: Batches expired (status: 'kadaluarsa')
        // We only count batches that still have active stock left!
        $batchesKadaluarsa = StokMasuk::where('status_kadaluarsa', 'kadaluarsa')
            ->withSum('stokKeluars', 'jumlah_keluar')
            ->get()
            ->filter(function ($batch) {
                $used = $batch->stok_keluars_sum_jumlah_keluar ?? 0;
                return ($batch->jumlah_masuk - $used) > 0;
            })
            ->count();

        // 5. Expiry Warnings: Get 5 batches expiring soonest (status mendekati or kadaluarsa with stock > 0)
        $expiringSoon = StokMasuk::with('produk')
            ->whereIn('status_kadaluarsa', ['mendekati', 'kadaluarsa'])
            ->withSum('stokKeluars', 'jumlah_keluar')
            ->get()
            ->map(function ($batch) use ($today) {
                $used = $batch->stok_keluars_sum_jumlah_keluar ?? 0;
                $batch->sisa_stok = $batch->jumlah_masuk - $used;

                $expDate = Carbon::parse($batch->tanggal_kadaluarsa);
                $batch->sisa_hari = $today->diffInDays($expDate, false);
                return $batch;
            })
            ->filter(function ($batch) {
                return $batch->sisa_stok > 0;
            })
            ->sortBy('tanggal_kadaluarsa')
            ->take(5)
            ->values();

        // 6. Recent system notifications (unread or read, newest first, max 5)
        $recentNotifications = Notifikasi::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 7. Chart Data: Stock distribution by category
        $categoriesChart = DB::table('kategori')
            ->leftJoin('produk', 'kategori.id', '=', 'produk.id_kategori')
            ->leftJoin('stok_masuk', 'produk.id_produk', '=', 'stok_masuk.id_produk')
            ->select('kategori.nama_kategori', DB::raw('COALESCE(SUM(stok_masuk.jumlah_masuk), 0) as total_masuk'))
            ->groupBy('kategori.id', 'kategori.nama_kategori')
            ->get();

        // Subtract outgoing stock from the categories totals
        foreach ($categoriesChart as $cat) {
            $outgoing = DB::table('stok_keluar')
                ->join('produk', 'stok_keluar.id_produk', '=', 'produk.id_produk')
                ->join('kategori', 'produk.id_kategori', '=', 'kategori.id')
                ->where('kategori.nama_kategori', $cat->nama_kategori)
                ->sum('stok_keluar.jumlah_keluar');
            $cat->stok_tersedia = max(0, $cat->total_masuk - $outgoing);
        }

        return view('dashboard', compact(
            'totalProducts',
            'totalAvailableStock',
            'batchesMendekati',
            'batchesKadaluarsa',
            'expiringSoon',
            'recentNotifications',
            'categoriesChart'
        ));
    }
}
