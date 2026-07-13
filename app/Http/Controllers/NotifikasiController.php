<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index()
    {
        $notifications = Notifikasi::with('stokMasuk.produk')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notifikasi.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Notifikasi::findOrFail($id);
        $notification->update(['status_baca' => true]);

        return back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Notifikasi::where('status_baca', false)->update(['status_baca' => true]);

        return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
    }
}
