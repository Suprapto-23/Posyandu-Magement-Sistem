<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan Halaman Utama Pusat Notifikasi
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');
        
        // Memanfaatkan query builder Laravel
        $query = Notifikasi::where('user_id', Auth::id())->latest();

        // Memanfaatkan Scope dari Model
        if ($filter == 'belum_dibaca') {
            $query->belumDibaca(); 
        }

        $notifikasis = $query->paginate(15)->withQueryString();
        $unreadCount = Notifikasi::where('user_id', Auth::id())->belumDibaca()->count();
                        
        return view('kader.notifikasi.index', compact('notifikasis', 'filter', 'unreadCount'));
    }

    /**
     * Eksekusi: Tandai Semua Dibaca
     */
    public function markAllRead()
    {
        Notifikasi::where('user_id', Auth::id())
                  ->belumDibaca()
                  ->update(['is_read' => true]);
                  
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    /**
     * Eksekusi: Tandai Satu Dibaca
     */
    public function markAsRead($id)
    {
        Notifikasi::where('user_id', Auth::id())
                  ->findOrFail($id)
                  ->update(['is_read' => true]);
                  
        return back();
    }

    /**
     * Eksekusi: Hapus Riwayat Permanen
     */
    public function destroy($id)
    {
        Notifikasi::where('user_id', Auth::id())
                  ->findOrFail($id)
                  ->delete();
                  
        return back()->with('success', 'Arsip notifikasi berhasil dihapus permanen.');
    }

    // =====================================================================
    // MESIN AJAX: REAL-TIME POLLING & PUSH NOTIFICATION (ANTI-RELOAD)
    // =====================================================================
    public function fetchRecent()
    {
        try {
            $userId = Auth::id();
            
            // Menggunakan scope dari model agar query sangat ringan
            $unreadCount  = Notifikasi::where('user_id', $userId)->belumDibaca()->count();
            $latestNotifs = Notifikasi::where('user_id', $userId)->terbaru()->get();
            $latestUnread = Notifikasi::where('user_id', $userId)->belumDibaca()->latest()->first();

            $html = '';
            
            if ($latestNotifs->isEmpty()) {
                $html .= '
                <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                    <div class="w-14 h-14 bg-slate-50 rounded-[16px] flex items-center justify-center mb-4 shadow-inner border border-slate-100 transform -rotate-3">
                        <i class="fas fa-bell-slash text-2xl opacity-40"></i>
                    </div>
                    <p class="text-[11px] font-black uppercase tracking-widest text-slate-500">Layar Bersih</p>
                    <p class="text-[12px] font-medium text-slate-400 mt-1">Tidak ada sinyal baru.</p>
                </div>';
            } else {
                foreach ($latestNotifs as $n) {
                    // MENGGUNAKAN NEXUS ENGINE DARI MODEL
                    $nexus = $n->toNexusFormat();
                    
                    // Logika UI adaptif berdasarkan status baca
                    $bgClass     = $nexus['is_read'] ? 'bg-white border-l-4 border-l-transparent' : 'bg-indigo-50/40 border-l-4 border-l-indigo-500';
                    $iconBgClass = $nexus['is_read'] ? 'bg-slate-50 text-slate-400 border-slate-100' : "bg-{$nexus['color']}-100 text-{$nexus['color']}-600 border-{$nexus['color']}-200 shadow-sm";
                    $titleClass  = $nexus['is_read'] ? 'text-slate-600' : 'text-slate-900';
                    
                    // Gunakan link bawaan notifikasi jika ada
                    $route = $nexus['link'] !== '#' ? $nexus['link'] : route('kader.notifikasi.index');
                    
                    $html .= "
                    <a href=\"{$route}\" class=\"flex gap-4 px-5 py-4 hover:bg-slate-50 transition-colors border-b border-slate-100/80 {$bgClass} group\">
                        <div class=\"w-11 h-11 rounded-[14px] flex items-center justify-center shrink-0 border {$iconBgClass} transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3\">
                            <i class=\"{$nexus['icon']} text-[14px]\"></i>
                        </div>
                        <div class=\"flex-1 min-w-0\">
                            <div class=\"flex justify-between items-start mb-1\">
                                <p class=\"text-[13px] font-black font-poppins {$titleClass} truncate pr-3\">{$nexus['judul']}</p>
                                <span class=\"text-[9px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap pt-0.5\">{$nexus['waktu']}</span>
                            </div>
                            <p class=\"text-[12px] font-medium text-slate-500 line-clamp-1 leading-relaxed\">{$nexus['pesan']}</p>
                        </div>
                    </a>";
                }
            }

            return response()->json([
                'unreadCount'  => $unreadCount,
                'html'         => $html,
                'latest_title' => $latestUnread ? $latestUnread->judul : null,
                'latest_body'  => $latestUnread ? $latestUnread->pesan : null,
            ]);

        } catch (\Exception $e) {
            // DETEKTOR ERROR: Memaksa error dicetak di kotak Notifikasi (Layar Kanan Atas)
            return response()->json([
                'unreadCount' => 0,
                'html' => '
                    <div class="p-6 text-center text-rose-600 bg-rose-50">
                        <i class="fas fa-bug text-3xl mb-3"></i>
                        <h4 class="font-black text-[13px] uppercase tracking-widest mb-2">Error Backend:</h4>
                        <p class="text-[11px] font-medium font-mono text-left bg-white p-3 rounded-lg border border-rose-200">
                            ' . $e->getMessage() . ' <br><br>Baris: ' . $e->getLine() . '
                        </p>
                    </div>'
            ]);
        }
    }
}