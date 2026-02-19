<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Remaja;
use App\Models\Kunjungan;
use App\Models\KonselingRemaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RemajaController extends Controller
{
    // Helper: Ambil data remaja berdasarkan NIK User atau Profile
    private function getRemaja()
    {
        $user = Auth::user();
        
        // 1. Cek NIK di tabel users utama
        $nik = $user->nik;

        // 2. Jika kosong, cek di tabel profiles (RELASI PENTING)
        if (empty($nik) && $user->profile) {
            $nik = $user->profile->nik;
        }

        // 3. Jika masih kosong, coba cari berdasarkan nama (Opsional/Fallback)
        if (empty($nik)) {
            return Remaja::where('nama_lengkap', $user->name)->first();
        }

        return Remaja::where('nik', $nik)->first();
    }

    public function index()
    {
        $remaja = $this->getRemaja();
        
        // Jika data tidak ditemukan, tampilkan view kosong
        if (!$remaja) {
            return view('user.remaja.empty');
        }
        
        // PERBAIKAN DI SINI (Tambahkan 'kunjungans.' di depan nama kolom)
        $pemeriksaanTerakhir = Kunjungan::where('kunjungans.pasien_id', $remaja->id)
            ->where('kunjungans.pasien_type', 'App\Models\Remaja')
            ->join('pemeriksaans', 'kunjungans.id', '=', 'pemeriksaans.kunjungan_id')
            ->orderBy('kunjungans.tanggal_kunjungan', 'desc')
            ->select(
                'pemeriksaans.*', 
                'kunjungans.tanggal_kunjungan', 
                'kunjungans.keluhan as keluhan_kunjungan'
            )
            ->first();

        // Riwayat Pemeriksaan (5 Terakhir)
        $riwayatPemeriksaan = Kunjungan::where('pasien_id', $remaja->id)
            ->where('pasien_type', 'App\Models\Remaja')
            ->with(['pemeriksaan']) // Eager Load
            ->orderBy('tanggal_kunjungan', 'desc')
            ->take(5)
            ->get()
            ->map(function($kunjungan) {
                return (object) [
                    'tanggal_periksa' => $kunjungan->tanggal_kunjungan,
                    'keluhan' => $kunjungan->keluhan,
                    'diagnosa' => $kunjungan->pemeriksaan->diagnosa ?? '-',
                    'tindakan' => $kunjungan->pemeriksaan->tindakan ?? '-'
                ];
            });
            
        return view('user.remaja.index', compact('remaja', 'pemeriksaanTerakhir', 'riwayatPemeriksaan'));
    }

    // Method Konseling
    public function konseling()
    {
        $remaja = $this->getRemaja();

        if (!$remaja) {
            return view('user.remaja.empty');
        }

        // Pastikan tabel dan model KonselingRemaja sudah sesuai database
        $riwayatKonseling = KonselingRemaja::where('remaja_id', $remaja->id)
            ->orderBy('created_at', 'desc') 
            ->get();

        return view('user.remaja.konseling', compact('remaja', 'riwayatKonseling'));
    }
}