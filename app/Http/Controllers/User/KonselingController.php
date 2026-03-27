<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Konseling;
use Illuminate\Support\Facades\Auth;

class KonselingController extends Controller
{
    public function index() { return view('user.konseling.index'); }

    public function fetchChat() {
        $chats = Konseling::where('user_id', Auth::id())->orderBy('created_at', 'asc')->get();
        Konseling::where('user_id', Auth::id())->where('pengirim', 'bidan')->update(['is_read' => true]);

        $html = '';
        foreach($chats as $chat) {
            $time = $chat->created_at->format('H:i');
            if($chat->pengirim == 'bidan') {
                $html .= "<div class='flex justify-start mb-4'><div class='max-w-[80%] bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm'><p class='text-[10px] text-sky-500 font-bold mb-1'><i class='fas fa-user-md mr-1'></i> Bidan Posyandu</p><p class='text-[13px] leading-relaxed'>{$chat->pesan}</p><span class='text-[10px] text-slate-400 mt-1 block text-left'>{$time}</span></div></div>";
            } else {
                $html .= "<div class='flex justify-end mb-4'><div class='max-w-[80%] bg-teal-600 text-white rounded-2xl rounded-br-sm px-4 py-3 shadow-sm'><p class='text-[10px] text-teal-200 font-bold mb-1'>{$chat->topik}</p><p class='text-[13px] leading-relaxed'>{$chat->pesan}</p><span class='text-[10px] text-teal-200 mt-1 block text-right'>{$time}</span></div></div>";
            }
        }
        return response()->json(['html' => $html]);
    }

    public function store(Request $request) {
        // Penting: Simpan ke tabel Konseling yang baru dibuat
        Konseling::create([
            'user_id' => Auth::id(), 
            'topik' => $request->topik ?? 'Konsultasi Umum', 
            'pesan' => $request->pesan, 
            'pengirim' => 'warga'
        ]);
        
        // Penting: Kembalikan respon berupa JSON, jangan redirect/back()!
        return response()->json(['success' => true]);
    }
}