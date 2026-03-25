<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');
        $query = Notifikasi::where('user_id', Auth::id())->latest();

        if ($filter == 'belum_dibaca') {
            $query->where('is_read', false);
        }

        $notifikasis = $query->paginate(15)->withQueryString();
        $unreadCount = Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count();
                        
        return view('kader.notifikasi.index', compact('notifikasis', 'filter', 'unreadCount'));
    }

    public function markAllRead()
    {
        Notifikasi::where('user_id', Auth::id())->where('is_read', false)->update(['is_read' => true]);
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function markAsRead($id)
    {
        Notifikasi::where('user_id', Auth::id())->findOrFail($id)->update(['is_read' => true]);
        return back();
    }

    public function destroy($id)
    {
        Notifikasi::where('user_id', Auth::id())->findOrFail($id)->delete();
        return back()->with('success', 'Riwayat notifikasi berhasil dihapus.');
    }

    // FUNGSI AJAX UNTUK REAL-TIME POLLING
    public function fetchRecent()
    {
        $unreadCount = Notifikasi::where('user_id', Auth::id())->where('is_read', false)->count();
        $latestNotifs = Notifikasi::where('user_id', Auth::id())->latest()->take(5)->get();

        $html = '';
        if ($latestNotifs->isEmpty()) {
            $html .= '<div class="flex flex-col items-center justify-center py-8 text-slate-400">';
            $html .= '<i class="fas fa-bell-slash text-3xl mb-2 opacity-30"></i>';
            $html .= '<p class="text-[12px] font-medium">Belum ada notifikasi</p>';
            $html .= '</div>';
        } else {
            foreach ($latestNotifs as $n) {
                $bg = $n->is_read ? 'bg-white border-l-4 border-l-transparent' : 'bg-indigo-50/40 border-l-4 border-l-indigo-500';
                $iconBg = $n->is_read ? 'bg-slate-100 text-slate-500 border-transparent' : 'bg-indigo-100 text-indigo-600 border-indigo-200';
                $titleCol = $n->is_read ? 'text-slate-600' : 'text-slate-800';
                
                $icon = 'bell';
                $jdl = strtolower($n->judul);
                if (str_contains($jdl, 'jadwal')) $icon = 'calendar-alt';
                if (str_contains($jdl, 'import')) $icon = 'file-excel';
                
                $route = route('kader.notifikasi.index');
                
                $html .= "
                <a href=\"{$route}\" class=\"notif-item flex gap-4 px-5 py-4 hover:bg-slate-50 transition-colors border-b border-slate-100 {$bg}\">
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