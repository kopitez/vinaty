<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StokMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $stokMasuk = StokMasuk::query()
            ->with(['produk', 'user'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('produk', function ($q) use ($search) {
                    $q->where('nama_produk', 'like', "%{$search}%")
                      ->orWhere('id_produk', 'like', "%{$search}%");
                });
            })
            ->orderBy('tanggal_masuk', 'desc')
            ->orderBy('id_masuk', 'desc')
            ->paginate(10)
            ->withQueryString();

        $produks = Produk::orderBy('nama_produk', 'asc')->get();

        return view('stok_masuk.index', compact('stokMasuk', 'produks', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_produk' => ['required', 'exists:produk,id_produk'],
            'jumlah_masuk' => ['required', 'integer', 'min:1'],
            'tanggal_masuk' => ['required', 'date'],
            'tanggal_kadaluarsa' => ['required', 'date', 'after_or_equal:tanggal_masuk'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'id_produk.required' => 'Produk wajib dipilih.',
            'id_produk.exists' => 'Produk yang dipilih tidak terdaftar.',
            'jumlah_masuk.required' => 'Jumlah masuk wajib diisi.',
            'jumlah_masuk.integer' => 'Jumlah masuk harus berupa angka bulat.',
            'jumlah_masuk.min' => 'Jumlah masuk minimal 1.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date' => 'Format tanggal masuk tidak valid.',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi.',
            'tanggal_kadaluarsa.date' => 'Format tanggal kadaluarsa tidak valid.',
            'tanggal_kadaluarsa.after_or_equal' => 'Tanggal kadaluarsa tidak boleh sebelum tanggal masuk.',
        ]);

        // Automatically determine initial expiration status based on today's date
        $today = Carbon::today();
        $expDate = Carbon::parse($data['tanggal_kadaluarsa']);
        $diffInDays = $today->diffInDays($expDate, false);

        // Prevent saving if the product is already expired
        if ($diffInDays <= 0) {
            return back()->withErrors([
                'tanggal_kadaluarsa' => 'Produk yang dimasukkan sudah kadaluarsa. Hanya produk dengan status aman atau mendekati kadaluarsa yang diperbolehkan.'
            ])->withInput();
        }

        $status = 'aman';
        if ($diffInDays <= 7) {
            $status = 'mendekati';
        }

        $data['status_kadaluarsa'] = $status;
        $data['id_user'] = Auth::id();

        StokMasuk::create($data);

        return redirect()->route('stok-masuk.index')
            ->with('success', 'Stok masuk baru berhasil dicatat.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);

        $data = $request->validate([
            'jumlah_masuk' => ['required', 'integer', 'min:1'],
            'tanggal_masuk' => ['required', 'date'],
            'tanggal_kadaluarsa' => ['required', 'date', 'after_or_equal:tanggal_masuk'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'jumlah_masuk.required' => 'Jumlah masuk wajib diisi.',
            'jumlah_masuk.integer' => 'Jumlah masuk harus berupa angka bulat.',
            'jumlah_masuk.min' => 'Jumlah masuk minimal 1.',
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi.',
            'tanggal_masuk.date' => 'Format tanggal masuk tidak valid.',
            'tanggal_kadaluarsa.required' => 'Tanggal kadaluarsa wajib diisi.',
            'tanggal_kadaluarsa.date' => 'Format tanggal kadaluarsa tidak valid.',
            'tanggal_kadaluarsa.after_or_equal' => 'Tanggal kadaluarsa tidak boleh sebelum tanggal masuk.',
        ]);

        // Calculate consumed stock
        $usedStock = $stokMasuk->stokKeluars()->sum('jumlah_keluar');

        if ($data['jumlah_masuk'] < $usedStock) {
            return back()->withErrors([
                'jumlah_masuk' => "Jumlah masuk tidak boleh kurang dari jumlah yang sudah dikeluarkan ({$usedStock} unit)."
            ])->withInput();
        }

        // Determine expiration status
        $today = Carbon::today();
        $expDate = Carbon::parse($data['tanggal_kadaluarsa']);
        $diffInDays = $today->diffInDays($expDate, false);

        // Prevent saving if the product is already expired
        if ($diffInDays <= 0) {
            return back()->withErrors([
                'tanggal_kadaluarsa' => 'Produk yang dimasukkan sudah kadaluarsa. Hanya produk dengan status aman atau mendekati kadaluarsa yang diperbolehkan.'
            ])->withInput();
        }

        $status = 'aman';
        if ($diffInDays <= 7) {
            $status = 'mendekati';
        }

        $data['status_kadaluarsa'] = $status;
        
        $stokMasuk->update($data);

        return redirect()->route('stok-masuk.index')
            ->with('success', 'Catatan stok masuk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);

        if ($stokMasuk->stokKeluars()->exists()) {
            return redirect()->route('stok-masuk.index')
                ->with('error', 'Stok masuk tidak dapat dihapus karena sebagian atau seluruhnya telah digunakan dalam transaksi stok keluar.');
        }

        $stokMasuk->delete();

        return redirect()->route('stok-masuk.index')
            ->with('success', 'Catatan stok masuk berhasil dihapus.');
    }
}
