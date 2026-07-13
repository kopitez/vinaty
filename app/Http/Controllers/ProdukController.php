<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Services\FefoService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProdukController extends Controller
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
        $kategoriId = $request->get('kategori_id');

        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->get();

        $produk = Produk::query()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama_produk', 'like', "%{$search}%")
                      ->orWhere('id_produk', 'like', "%{$search}%")
                      ->orWhere('merek', 'like', "%{$search}%");
                });
            })
            ->when($kategoriId, function ($query, $kategoriId) {
                return $query->where('id_kategori', $kategoriId);
            })
            ->with('kategori')
            ->withSum('stokMasuks as total_masuk', 'jumlah_masuk')
            ->withSum('stokKeluars as total_keluar', 'jumlah_keluar')
            ->orderBy('id_produk', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Calculate available stock for each product in the collection
        $produk->getCollection()->transform(function ($item) {
            $item->stok_tersedia = ($item->total_masuk ?? 0) - ($item->total_keluar ?? 0);
            return $item;
        });

        return view('produk.index', compact('produk', 'kategoris', 'search', 'kategoriId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255'],
            'id_kategori' => ['required', 'exists:kategori,id'],
            'merek' => ['required', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists' => 'Kategori yang dipilih tidak valid.',
            'merek.required' => 'Merek wajib diisi.',
            'satuan.required' => 'Satuan wajib diisi.',
        ]);

        Produk::create($data);

        return redirect()->route('produk.index')
            ->with('success', 'Produk baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::with('kategori')
            ->withSum('stokMasuks as total_masuk', 'jumlah_masuk')
            ->withSum('stokKeluars as total_keluar', 'jumlah_keluar')
            ->findOrFail($id);

        $produk->stok_tersedia = ($produk->total_masuk ?? 0) - ($produk->total_keluar ?? 0);

        // Fetch batches (incoming stock) with remaining quantities using FEFO service
        $batches = $this->fefoService->getAvailableBatches($id);

        // Parse remaining days and add formatting
        $today = Carbon::today();
        $batches->map(function ($batch) use ($today) {
            $expDate = Carbon::parse($batch->tanggal_kadaluarsa);
            $batch->sisa_hari = $today->diffInDays($expDate, false);
            return $batch;
        });

        // Also fetch historical incoming stock transactions
        $stokMasukHistories = $produk->stokMasuks()
            ->with('user')
            ->orderBy('tanggal_masuk', 'desc')
            ->take(5)
            ->get();

        // Also fetch historical outgoing stock transactions
        $stokKeluarHistories = $produk->stokKeluars()
            ->with(['user', 'stokMasuk'])
            ->orderBy('tanggal_keluar', 'desc')
            ->take(5)
            ->get();

        return view('produk.show', compact('produk', 'batches', 'stokMasukHistories', 'stokKeluarHistories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);

        $data = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255'],
            'id_kategori' => ['required', 'exists:kategori,id'],
            'merek' => ['required', 'string', 'max:255'],
            'satuan' => ['required', 'string', 'max:50'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'id_kategori.required' => 'Kategori wajib dipilih.',
            'id_kategori.exists' => 'Kategori yang dipilih tidak valid.',
            'merek.required' => 'Merek wajib diisi.',
            'satuan.required' => 'Satuan wajib diisi.',
        ]);

        $produk->update($data);

        return redirect()->route('produk.index')
            ->with('success', 'Data produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);

        // Check if there is stock history
        if ($produk->stokMasuks()->exists() || $produk->stokKeluars()->exists()) {
            return redirect()->route('produk.index')
                ->with('error', 'Produk tidak dapat dihapus karena memiliki riwayat stok masuk/keluar.');
        }

        $produk->delete();

        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
