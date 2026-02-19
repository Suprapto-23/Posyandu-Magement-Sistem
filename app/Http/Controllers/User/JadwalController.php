<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\JadwalPosyandu;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $nikUser = $user->nik ?? $user->username; 
        
        // 1. TENTUKAN HAK AKSES USER
        $hakAkses = ['semua']; 
        
        if ($nikUser) {
            $isOrtu = Balita::where('nik_ibu', $nikUser)->orWhere('nik_ayah', $nikUser)->exists();
            if ($isOrtu) $hakAkses[] = 'balita';

            if (Remaja::where('nik', $nikUser)->exists()) $hakAkses[] = 'remaja';

            if (Lansia::where('nik', $nikUser)->exists()) $hakAkses[] = 'lansia';
        }

        // 2. AMBIL DATA DARI DATABASE
        // PERBAIKAN: Hapus filter tanggal agar jadwal lampau tetap muncul selama statusnya 'aktif'
        $jadwalKegiatan = JadwalPosyandu::whereIn('target_peserta', $hakAkses)
            // Filter hanya status aktif (Jadi kalau Bidan set 'Selesai'/'Dibatalkan' baru hilang)
            ->where('status', 'aktif') 
            // Urutkan dari tanggal paling baru/jauh ke tanggal lama
            ->orderBy('tanggal', 'desc') 
            ->orderBy('waktu_mulai', 'asc')
            ->paginate(9);

        return view('user.jadwal.index', compact('jadwalKegiatan'));
    }
}