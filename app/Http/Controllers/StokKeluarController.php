<?php

namespace App\Http\Controllers;

use App\Models\StokKeluar;
use App\Models\Produk;
use App\Services\FefoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StokKeluarController extends Controller
{
    protected $fefoService;

    public function __construct(FefoService $fefoService)
    {
        $this->fefoService = $fefoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $stokKeluar = StokKeluar::query()
            ->with(['produk', 'user', 'stokMasuk'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('produk', function ($q) use ($search) {
                    $q->where('nama_produk', 'like', "%{$search}%")
                      ->orWhere('id_produk', 'like', "%{$search}%");
                });
            })
            ->orderBy('tanggal_keluar', 'desc')
            ->orderBy('id_keluar', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Get all products that have available stock to output
        $produks = Produk::orderBy('nama_produk', 'asc')->get()->map(function ($p) {
            $p->stok_tersedia = $this->fefoService->calculateAvailableStock($p->id_produk);
            return $p;
        })->filter(function ($p) {
            return $p->stok_tersedia > 0;
        })->values();

        return view('stok_keluar.index', compact('stokKeluar', 'produks', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_produk' => ['required', 'exists:produk,id_produk'],
            'jumlah_keluar' => ['required', 'integer', 'min:1'],
            'tanggal_keluar' => ['required', 'date'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'id_produk.required' => 'Produk wajib dipilih.',
            'id_produk.exists' => 'Produk yang dipilih tidak terdaftar.',
            'jumlah_keluar.required' => 'Jumlah keluar wajib diisi.',
            'jumlah_keluar.integer' => 'Jumlah keluar harus berupa angka bulat.',
            'jumlah_keluar.min' => 'Jumlah keluar minimal 1.',
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_keluar.date' => 'Format tanggal keluar tidak valid.',
        ]);

        try {
            $this->fefoService->releaseStock(
                $data['id_produk'],
                (int)$data['jumlah_keluar'],
                Auth::id(),
                $data['keterangan'] ?? null,
                $data['tanggal_keluar']
            );

            return redirect()->route('stok-keluar.index')
                ->with('success', 'Stok berhasil dikeluarkan berdasarkan urutan FEFO.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);

        $data = $request->validate([
            'tanggal_keluar' => ['required', 'date'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'tanggal_keluar.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_keluar.date' => 'Format tanggal keluar tidak valid.',
        ]);

        $stokKeluar->update($data);

        return redirect()->route('stok-keluar.index')
            ->with('success', 'Catatan stok keluar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stokKeluar = StokKeluar::findOrFail($id);
        $stokKeluar->delete();

        return redirect()->route('stok-keluar.index')
            ->with('success', 'Catatan stok keluar berhasil dihapus.');
    }
}
