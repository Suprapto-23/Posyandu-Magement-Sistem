<?php
namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Konseling;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class KonselingController extends Controller
{
    public function index() { return view('bidan.konseling.index'); }

    public function fetchList() {
        $userIds = Konseling::select('user_id')->distinct()->pluck('user_id');
        $latestChats = [];
        
        foreach($userIds as $uid) {
            $latest = Konseling::where('user_id', $uid)->orderBy('created_at', 'desc')->first();
            if($latest) $latestChats[] = $latest;
        }
        
        usort($latestChats, fn($a, $b) => $b->created_at <=> $a->created_at);

        $html = '';
        foreach($latestChats as $lc) {
            $user = User::find($lc->user_id);
            if(!$user) continue;

            $unread = Konseling::where('user_id', $user->id)->where('pengirim', 'warga')->where('is_read', false)->count();
            $unreadBadge = $unread > 0 ? "<span class='w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shrink-0'>{$unread}</span>" : '';
            $time = $lc->created_at->format('H:i');
            
            $bgClass = $unread > 0 ? 'bg-sky-50/70 border-l-4 border-l-sky-500' : 'bg-white border-l-4 border-l-transparent';
            $textClass = $unread > 0 ? 'font-black text-slate-900' : 'font-bold text-slate-700';

            $html .= "
            <div class='p-4 border-b border-slate-100 hover:bg-slate-50 cursor-pointer flex items-center gap-3 transition-colors chat-item {$bgClass}' data-id='{$user->id}' data-name='{$user->name}'>
                <div class='w-11 h-11 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center font-black text-lg shrink-0 border border-sky-200'>".substr($user->name, 0, 1)."</div>
                <div class='flex-1 min-w-0 pointer-events-none'>
                    <div class='flex justify-between items-center mb-0.5'>
                        <h4 class='text-[14px] {$textClass} truncate'>{$user->name}</h4>
                        <span class='text-[10px] font-bold text-slate-400'>{$time}</span>
                    </div>
                    <p class='text-[12px] text-slate-500 truncate'>{$lc->pesan}</p>
                </div>
                {$unreadBadge}
            </div>";
        }
        return response()->json(['html' => $html]);
    }

    public function fetchChat($user_id) {
        $chats = Konseling::where('user_id', $user_id)->orderBy('created_at', 'asc')->get();
        Konseling::where('user_id', $user_id)->where('pengirim', 'warga')->update(['is_read' => true]);

        $html = '';
        foreach($chats as $chat) {
            $time = $chat->created_at->format('H:i');
            if($chat->pengirim == 'warga') {
                $topik = $chat->topik ? "<p class='text-[10px] text-sky-600 font-black mb-1.5 uppercase tracking-wider border-b border-slate-100 pb-1.5'>{$chat->topik}</p>" : "";
                $html .= "<div class='flex justify-start mb-4'><div class='max-w-[75%] bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-bl-sm px-4 py-3 shadow-sm'>{$topik}<p class='text-[13px] leading-relaxed'>{$chat->pesan}</p><span class='text-[10px] text-slate-400 mt-1 block text-left'>{$time}</span></div></div>";
            } else {
                $html .= "<div class='flex justify-end mb-4'><div class='max-w-[75%] bg-sky-500 text-white rounded-2xl rounded-br-sm px-4 py-3 shadow-sm'><p class='text-[13px] leading-relaxed'>{$chat->pesan}</p><span class='text-[10px] text-sky-100 mt-1 block text-right'>{$time} <i class='fas fa-check-double ml-0.5'></i></span></div></div>";
            }
        }
        return response()->json(['html' => $html]);
    }

    public function reply(Request $request, $user_id) {
        Konseling::create([
            'user_id' => $user_id, 'bidan_id' => Auth::id(), 'pesan' => $request->pesan, 'pengirim' => 'bidan'
        ]);
        return response()->json(['success' => true]);
    }
}