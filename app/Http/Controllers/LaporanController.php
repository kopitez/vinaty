<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\StokMasuk;
use App\Models\StokKeluar;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Display a listing of reports and filter options.
     */
    public function index(Request $request)
    {
        $hasFilters = $request->has('tipe_laporan');
        $data = [];

        if ($hasFilters) {
            $data = $this->getReportData($request);
        }

        return view('laporan.index', array_merge([
            'hasFilters' => $hasFilters,
            'tipeLaporan' => $request->get('tipe_laporan', 'stok_masuk'),
            'periode' => $request->get('periode', 'harian'),
            'tanggal' => $request->get('tanggal', date('Y-m-d')),
            'bulan' => $request->get('bulan', date('m')),
            'tahun' => $request->get('tahun', date('Y')),
            'startDate' => $request->get('start_date'),
            'endDate' => $request->get('end_date'),
        ], $data));
    }

    /**
     * Export report data as Excel.
     */
    public function exportExcel(Request $request, $filename = null)
    {
        $data = $this->getReportData($request);
        $html = view('laporan.excel', $data)->render();

        if (!$filename) {
            $filename = 'laporan_' . $data['tipeLaporan'] . '_' . date('Ymd_His') . '.xls';
        }

        return response()->streamDownload(function () use ($html) {
            echo $html;
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public',
        ]);
    }

    /**
     * Export report data as PDF.
     */
    public function exportPdf(Request $request, $filename = null)
    {
        $data = $this->getReportData($request);
        if (!$filename) {
            $filename = 'laporan_' . $data['tipeLaporan'] . '_' . date('Ymd_His') . '.pdf';
        }

        // Check if Dompdf class exists (loaded from composer package)
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', $data);
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $filename, [
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0',
                'Pragma' => 'public',
            ]);
        }

        // Fallback: render print-friendly page that triggers browser PDF print utility
        return view('laporan.pdf_print', $data);
    }

    /**
     * Fetch report records based on request filters.
     */
    private function getReportData(Request $request)
    {
        $tipeLaporan = $request->get('tipe_laporan', 'stok_masuk');
        $periode = $request->get('periode', 'harian');
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $periodeText = '';

        // Setup filter callback
        $filterCallback = function ($query, $dateField) use ($periode, $tanggal, $bulan, $tahun, $startDate, $endDate, &$periodeText) {
            if ($periode === 'harian') {
                $query->whereDate($dateField, $tanggal);
                $periodeText = 'Hari: ' . date('d M Y', strtotime($tanggal));
            } elseif ($periode === 'bulanan') {
                $query->whereMonth($dateField, $bulan)->whereYear($dateField, $tahun);
                $monthName = Carbon::create()->month((int)$bulan)->locale('id')->monthName;
                $periodeText = 'Bulan: ' . $monthName . ' ' . $tahun;
            } elseif ($periode === 'tahunan') {
                $query->whereYear($dateField, $tahun);
                $periodeText = 'Tahun: ' . $tahun;
            } elseif ($periode === 'custom' && $startDate && $endDate) {
                $query->whereBetween($dateField, [$startDate, $endDate]);
                $periodeText = 'Periode: ' . date('d/m/Y', strtotime($startDate)) . ' s/d ' . date('d/m/Y', strtotime($endDate));
            } else {
                $periodeText = 'Semua Periode';
            }
        };

        $results = collect();

        if ($tipeLaporan === 'stok_masuk') {
            $query = StokMasuk::with(['produk', 'user']);
            $filterCallback($query, 'tanggal_masuk');
            $results = $query->orderBy('tanggal_masuk', 'asc')->get();
        } elseif ($tipeLaporan === 'stok_keluar') {
            $query = StokKeluar::with(['produk', 'user', 'stokMasuk']);
            $filterCallback($query, 'tanggal_keluar');
            $results = $query->orderBy('tanggal_keluar', 'asc')->get();
        } elseif ($tipeLaporan === 'stok_tersedia') {
            $query = Produk::with('kategori');
            $produkList = $query->get();

            foreach ($produkList as $p) {
                $masukQuery = $p->stokMasuks();
                $keluarQuery = $p->stokKeluars();

                if ($periode !== 'semua') {
                    $filterCallback($masukQuery, 'tanggal_masuk');
                    $filterCallback($keluarQuery, 'tanggal_keluar');
                }

                $totalMasuk = $masukQuery->sum('jumlah_masuk');
                $totalKeluar = $keluarQuery->sum('jumlah_keluar');

                $p->total_masuk = $totalMasuk;
                $p->total_keluar = $totalKeluar;
                $p->stok_tersedia = $totalMasuk - $totalKeluar;
            }

            // Keep products that have transaction history or available stock
            $results = $produkList->filter(function ($p) {
                return $p->total_masuk > 0 || $p->total_keluar > 0 || $p->stok_tersedia > 0;
            })->values();
        } elseif ($tipeLaporan === 'mendekati_kadaluarsa') {
            $query = StokMasuk::with('produk')->where('status_kadaluarsa', 'mendekati');
            $filterCallback($query, 'tanggal_kadaluarsa');

            $today = Carbon::today();
            $results = $query->get()->map(function ($batch) use ($today) {
                $used = StokKeluar::where('id_masuk', $batch->id_masuk)->sum('jumlah_keluar');
                $batch->sisa_stok = $batch->jumlah_masuk - $used;

                $expDate = Carbon::parse($batch->tanggal_kadaluarsa);
                $batch->sisa_hari = $today->diffInDays($expDate, false);
                return $batch;
            })->filter(function ($batch) {
                return $batch->sisa_stok > 0;
            })->values();
        } elseif ($tipeLaporan === 'kadaluarsa') {
            $query = StokMasuk::with('produk')->where('status_kadaluarsa', 'kadaluarsa');
            $filterCallback($query, 'tanggal_kadaluarsa');

            $today = Carbon::today();
            $results = $query->get()->map(function ($batch) use ($today) {
                $used = StokKeluar::where('id_masuk', $batch->id_masuk)->sum('jumlah_keluar');
                $batch->sisa_stok = $batch->jumlah_masuk - $used;

                $expDate = Carbon::parse($batch->tanggal_kadaluarsa);
                $batch->sisa_hari = $today->diffInDays($expDate, false);
                return $batch;
            })->filter(function ($batch) {
                return $batch->sisa_stok > 0;
            })->values();
        }

        return compact('results', 'tipeLaporan', 'periode', 'tanggal', 'bulan', 'tahun', 'startDate', 'endDate', 'periodeText');
    }
}
