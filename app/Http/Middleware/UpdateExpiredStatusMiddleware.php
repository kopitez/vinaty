<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\StokMasuk;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class UpdateExpiredStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run this logic on GET requests to keep POST/PUT fast
        if ($request->isMethod('GET')) {
            $this->updateExpiryStatuses();
        }

        return $next($request);
    }

    /**
     * Perform the expiration checking, status updates, and notifications generation.
     */
    public function updateExpiryStatuses(): void
    {
        $today = Carbon::today();
        
        // Fetch all incoming stock batches
        $batches = StokMasuk::with('produk')->get();

        foreach ($batches as $batch) {
            $expDate = Carbon::parse($batch->tanggal_kadaluarsa);
            $diffInDays = $today->diffInDays($expDate, false); // false maintains positive/negative relative value

            $oldStatus = $batch->status_kadaluarsa;
            $newStatus = 'aman';

            if ($diffInDays <= 0) {
                $newStatus = 'kadaluarsa';
            } elseif ($diffInDays <= 7) {
                $newStatus = 'mendekati';
            }

            // If the status changed, update it in the database
            if ($oldStatus !== $newStatus) {
                $batch->status_kadaluarsa = $newStatus;
                $batch->save();
            }

            // Create notification if status is 'kadaluarsa' or 'mendekati'
            if ($newStatus === 'kadaluarsa') {
                $exists = Notifikasi::where('id_masuk', $batch->id_masuk)
                    ->where('pesan', 'like', '%telah kadaluarsa%')
                    ->exists();

                if (!$exists) {
                    Notifikasi::create([
                        'id_masuk' => $batch->id_masuk,
                        'pesan' => "Produk " . ($batch->produk->nama_produk ?? 'Unknown') . " (Batch ID: #{$batch->id_masuk}) telah kadaluarsa pada {$batch->tanggal_kadaluarsa}.",
                        'status_baca' => false,
                    ]);
                }
            } elseif ($newStatus === 'mendekati') {
                $exists = Notifikasi::where('id_masuk', $batch->id_masuk)
                    ->where('pesan', 'like', '%mendekati masa kadaluarsa%')
                    ->exists();

                if (!$exists) {
                    $sisaHari = max(0, $diffInDays);
                    Notifikasi::create([
                        'id_masuk' => $batch->id_masuk,
                        'pesan' => "Produk " . ($batch->produk->nama_produk ?? 'Unknown') . " (Batch ID: #{$batch->id_masuk}) mendekati masa kadaluarsa ({$batch->tanggal_kadaluarsa}, sisa {$sisaHari} hari).",
                        'status_baca' => false,
                    ]);
                }
            }
        }
    }
}
