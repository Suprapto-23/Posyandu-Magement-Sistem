<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Remaja;
use App\Models\KonselingRemaja;

class KonselingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $nik = $user->nik ?? ($user->profile->nik ?? null);

        // Koleksi semua riwayat konseling (bisa digabung dari berbagai tabel nanti)
        $riwayatKonseling = collect();
        $profil = null;
        $kategori = '';

        // 1. Cek apakah User adalah REMAJA
        $remaja = Remaja::where('nik', $nik)->first();
        if ($remaja) {
            $profil = $remaja;
            $kategori = 'Remaja';
            // Ambil data dari tabel konseling_remajas
            $data = KonselingRemaja::where('remaja_id', $remaja->id)
                ->orderBy('tanggal_konseling', 'desc')
                ->get();
            $riwayatKonseling = $riwayatKonseling->merge($data);
        }

        // 2. Nanti bisa tambahkan cek LANSIA atau IBU HAMIL di sini (if $lansia ...)
        
        // Return ke View Terpusat
        return view('user.konseling.index', compact('riwayatKonseling', 'profil', 'kategori'));
    }
}