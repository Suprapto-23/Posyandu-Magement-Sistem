<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\NotifikasiUser;

class NotifikasiController extends Controller
{
    /**
     * Halaman daftar notifikasi — filter semua/belum/sudah
     * Simpan ke: app/Http/Controllers/User/NotifikasiController.php
     */
    public function index(Request $request)
    {
        $user   = Auth::user();
        $filter = $request->get('filter', 'semua');

        try {
            $query = NotifikasiUser::where('user_id', $user->id)->latest();

            if ($filter === 'belum') {
                $query->where('dibaca', false);
            } elseif ($filter === 'sudah') {
                $query->where('dibaca', true);
            }

            $notifikasi = $query->paginate(15)->withQueryString();
            $totalBelum = NotifikasiUser::where('user_id', $user->id)->where('dibaca', false)->count();
            $totalSemua = NotifikasiUser::where('user_id', $user->id)->count();

        } catch (\Throwable $e) {
            Log::warning('NotifikasiController::index error: ' . $e->getMessage());
            $notifikasi = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            $totalBelum = 0;
            $totalSemua = 0;
        }

        return view('user.notifikasi.index', compact(
            'notifikasi', 'filter', 'totalBelum', 'totalSemua'
        ));
    }

    /**
     * Tandai satu notifikasi sudah dibaca, lalu redirect ke link-nya
     * Route: GET /user/notifikasi/{id}/read?redirect=...
     */
    public function markRead(Request $request, $id)
    {
        try {
            $notif = NotifikasiUser::where('user_id', Auth::id())->findOrFail($id);
            $notif->update(['dibaca' => true]);

            // Redirect ke link jika ada dan valid
            $link = $notif->link;
            if ($link && filter_var($link, FILTER_VALIDATE_URL)) {
                return redirect($link);
            }
        } catch (\Throwable $e) {
            Log::warning('NotifikasiController::markRead error: ' . $e->getMessage());
        }

        return back();
    }

    /**
     * Tandai SEMUA notifikasi sudah dibaca
     * Route: POST /user/notifikasi/mark-all-read
     */
    public function markAllRead()
    {
        try {
            NotifikasiUser::where('user_id', Auth::id())
                ->where('dibaca', false)
                ->update(['dibaca' => true]);
        } catch (\Throwable $e) {
            Log::warning('NotifikasiController::markAllRead error: ' . $e->getMessage());
        }

        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    /**
     * AJAX endpoint — polling setiap N detik dari JS
     * WAJIB selalu return JSON (tidak boleh redirect)
     * Route: GET /user/notifikasi/latest
     */
    public function latest()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['status' => 'ok', 'notifikasi' => [], 'unread_count' => 0]);
            }

            $items = NotifikasiUser::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get()
                ->map(fn($n) => [
                    'id'      => $n->id,
                    'judul'   => $n->judul,
                    'pesan'   => Str::limit($n->pesan, 90),
                    'waktu'   => $n->created_at->diffForHumans(),
                    'is_read' => (bool) $n->dibaca,
                    'tipe'    => $n->tipe ?? 'info',
                    'icon'    => $n->tipe_icon,
                    'color'   => $n->tipe_color,
                    'link'    => $n->link ?? null,
                ]);

            $unread = NotifikasiUser::where('user_id', $user->id)->where('dibaca', false)->count();

            return response()->json([
                'status'       => 'ok',
                'notifikasi'   => $items,
                'unread_count' => $unread,
            ]);

        } catch (\Throwable $e) {
            Log::warning('NotifikasiController::latest error: ' . $e->getMessage());
            return response()->json(['status' => 'ok', 'notifikasi' => [], 'unread_count' => 0]);
        }
    }
    /**
     * FUNGSI AJAX REAL-TIME UNTUK DROPDOWN USER (WARGA)
     */
    public function fetchRecent()
    {
        $unreadCount = Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count();
        $latestNotifs = Notifikasi::where('user_id', Auth::id())->latest()->take(5)->get();

        $html = '';
        if ($latestNotifs->isEmpty()) {
            $html .= '<div class="flex flex-col items-center justify-center py-8 text-slate-400">';
            $html .= '<i class="fas fa-bell-slash text-3xl mb-2 opacity-30"></i>';
            $html .= '<p class="text-[12px] font-medium">Belum ada pesan</p>';
            $html .= '</div>';
        } else {
            foreach ($latestNotifs as $n) {
                $bg = $n->is_read ? 'bg-white border-l-4 border-l-transparent' : 'bg-teal-50/40 border-l-4 border-l-teal-500';
                $iconBg = $n->is_read ? 'bg-slate-100 text-slate-500 border-transparent' : 'bg-teal-100 text-teal-600 border-teal-200';
                $titleCol = $n->is_read ? 'text-slate-600' : 'text-slate-800';
                
                $icon = str_contains(strtolower($n->judul), 'jadwal') ? 'calendar-alt' : 'envelope';
                $route = route('user.notifikasi.index');
                
                $html .= "
                <a href=\"{$route}\" class=\"flex gap-4 px-5 py-4 hover:bg-slate-50 transition-colors border-b border-slate-100 {$bg}\">
                    <div class=\"w-9 h-9 rounded-full flex items-center justify-center shrink-0 border {$iconBg}\">
                        <i class=\"fas fa-{$icon} text-sm\"></i>
                    </div>
                    <div>
                        <p class=\"text-[13px] font-bold {$titleCol} leading-tight\">{$n->judul}</p>
                        <p class=\"text-[12px] text-slate-500 mt-0.5 line-clamp-1\">{$n->pesan}</p>
                    </div>
                </a>";
            }
        }

        return response()->json([
            'unreadCount' => $unreadCount,
            'html' => $html
        ]);
    }
    
}