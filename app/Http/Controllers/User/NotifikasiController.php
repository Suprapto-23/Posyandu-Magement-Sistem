<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Notifikasi;

/**
 * NotifikasiController (User/Warga)
 *
 * PERBAIKAN [BUG-6]:
 * View notifikasi/index.blade.php membutuhkan variabel:
 *   - $notifikasis  (sudah ada)
 *   - $filter       (sudah ada)
 *   - $allCount     (BARU — jumlah total semua notif)
 *   - $unreadCount  (BARU — jumlah belum dibaca, untuk badge)
 * Controller lama tidak mengirim $allCount dan $unreadCount
 * sehingga badge "Belum Dibaca" selalu kosong.
 */
class NotifikasiController extends Controller
{
    /**
     * Halaman Kotak Masuk.
     * Route: GET /user/notifikasi → user.notifikasi.index
     */
    public function index(Request $request)
    {
        $user   = Auth::user();
        $filter = $request->get('filter', 'semua');

        try {
            $query = Notifikasi::where('user_id', $user->id)->latest();

            // Filter tab
            if ($filter === 'belum') {
                $query->where('is_read', false);
            } elseif ($filter === 'sudah') {
                $query->where('is_read', true);
            }

            $notifikasis = $query->paginate(15)->withQueryString();

            // [BUG-6 FIX] Tambahkan allCount dan unreadCount
            $allCount    = Notifikasi::where('user_id', $user->id)->count();
            $unreadCount = Notifikasi::where('user_id', $user->id)
                ->where('is_read', false)
                ->count();

            return view('user.notifikasi.index', compact(
                'notifikasis',
                'filter',
                'allCount',
                'unreadCount'
            ));

        } catch (\Throwable $e) {
            Log::warning('NotifikasiController::index error: ' . $e->getMessage());

            $notifikasis = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            $allCount    = 0;
            $unreadCount = 0;

            return view('user.notifikasi.index', compact('notifikasis', 'filter', 'allCount', 'unreadCount'))
                ->with('error', 'Gagal memuat pesan.');
        }
    }

    /**
     * AJAX polling — hanya return jumlah unread.
     * Route: GET /user/notifikasi/fetch → user.notifikasi.fetch
     */
    public function fetchRecent()
    {
        try {
            $unreadCount = Notifikasi::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return response()->json(['unreadCount' => $unreadCount]);
        } catch (\Throwable $e) {
            return response()->json(['unreadCount' => 0]);
        }
    }

    /**
     * Tandai satu notifikasi sudah dibaca.
     * Route: POST /user/notifikasi/{id}/read → user.notifikasi.read
     */
    public function markRead(Request $request, $id)
    {
        try {
            Notifikasi::where('user_id', Auth::id())
                ->where('id', $id)
                ->update(['is_read' => true]);
        } catch (\Throwable $e) {
            Log::warning('NotifikasiController::markRead error: ' . $e->getMessage());
        }
        return back();
    }

    /**
     * Tandai semua notifikasi sudah dibaca.
     * Route: POST /user/notifikasi/mark-all-read → user.notifikasi.markall
     */
    public function markAllRead()
    {
        try {
            Notifikasi::where('user_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } catch (\Throwable $e) {
            Log::warning('NotifikasiController::markAllRead error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses permintaan.');
        }
        return back()->with('success', 'Semua pesan telah ditandai dibaca.');
    }
}