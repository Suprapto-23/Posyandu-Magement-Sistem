<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Konseling;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class KonselingController extends Controller
{
    public function index() 
    { 
        return view('bidan.konseling.index'); 
    }

    /**
     * API: Mengambil daftar kontak Warga yang pernah chat
     */
    public function fetchList() 
    {
        $userIds = Konseling::select('user_id')->distinct()->pluck('user_id');
        $contacts = [];
        
        foreach($userIds as $uid) {
            $latest = Konseling::where('user_id', $uid)->orderBy('created_at', 'desc')->first();
            $user = User::with('profile')->find($uid);
            
            if($user && $latest) {
                $unread = Konseling::where('user_id', $uid)
                                 ->where('pengirim', 'warga')
                                 ->where('is_read', false)
                                 ->count();
                
                $contacts[] = [
                    'user_id'      => $user->id,
                    'name'         => $user->profile->full_name ?? $user->name ?? 'Warga',
                    'last_message' => $latest->pesan,
                    'time'         => $latest->created_at->format('H:i'),
                    'timestamp'    => $latest->created_at->timestamp,
                    'unread'       => $unread,
                    'avatar_text'  => strtoupper(substr($user->profile->full_name ?? $user->name ?? 'W', 0, 1))
                ];
            }
        }
        
        // Urutkan berdasarkan chat terbaru (teratas)
        usort($contacts, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        return response()->json($contacts);
    }

    /**
     * API: Mengambil isi chat dengan Warga tertentu
     */
    public function fetchChat($user_id) 
    {
        // Tandai semua pesan dari warga ini sebagai "Sudah Dibaca"
        Konseling::where('user_id', $user_id)->where('pengirim', 'warga')->update(['is_read' => true]);

        $chats = Konseling::where('user_id', $user_id)->orderBy('created_at', 'asc')->get()->map(function($chat) {
            return [
                'id'       => $chat->id,
                'pengirim' => $chat->pengirim, // 'warga' atau 'bidan'
                'topik'    => $chat->topik,
                'pesan'    => $chat->pesan,
                'time'     => $chat->created_at->format('H:i')
            ];
        });

        $user = User::with('profile')->find($user_id);
        $userName = $user->profile->full_name ?? $user->name ?? 'Warga';

        return response()->json([
            'chats' => $chats,
            'user'  => ['id' => $user_id, 'name' => $userName]
        ]);
    }

    /**
     * API: Mengirim balasan Bidan
     */
    public function reply(Request $request, $user_id) 
    {
        $request->validate(['pesan' => 'required|string']);

        Konseling::create([
            'user_id'  => $user_id,
            'bidan_id' => Auth::id(),
            'pengirim' => 'bidan',
            'pesan'    => $request->pesan,
            'is_read'  => false
        ]);

        return response()->json(['success' => true]);
    }
}