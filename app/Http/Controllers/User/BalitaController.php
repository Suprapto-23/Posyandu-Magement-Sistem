<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\Kunjungan;
use App\Models\Imunisasi;
use Illuminate\Http\Request;

class BalitaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $nikUser = $this->getNikUser($user);
        
        // Cari balita berdasarkan NIK Ibu/Ayah
        $anak = Balita::where(function($q) use ($nikUser) {
                $q->where('nik_ibu', $nikUser)
                  ->orWhere('nik_ayah', $nikUser);
            })
            ->with(['pemeriksaan_terakhir'])
            ->get();
            
        return view('user.balita.index', ['dataBalita' => $anak]);
    }

    public function show($id)
    {
        $user = auth()->user();
        $nikUser = $this->getNikUser($user);

        $balita = Balita::where(function($q) use ($nikUser) {
                $q->where('nik_ibu', $nikUser)
                  ->orWhere('nik_ayah', $nikUser);
            })
            ->findOrFail($id);
            
        // FIXED: Tambahkan 'kunjungans.' didepan pasien_id untuk mencegah Ambigu
        $riwayatPemeriksaan = Kunjungan::where('kunjungans.pasien_id', $id)
            ->where('kunjungans.pasien_type', 'App\Models\Balita')
            ->where('kunjungans.jenis_kunjungan', 'pemeriksaan')
            ->join('pemeriksaans', 'kunjungans.id', '=', 'pemeriksaans.kunjungan_id')
            ->select('kunjungans.*', 'pemeriksaans.*', 'kunjungans.created_at as tgl_kunjungan')
            ->orderBy('kunjungans.tanggal_kunjungan', 'desc')
            ->get();
            
        $riwayatImunisasi = Imunisasi::whereHas('kunjungan', function($query) use ($id) {
                $query->where('pasien_id', $id)
                    ->where('pasien_type', 'App\Models\Balita');
            })
            ->latest()
            ->get();
            
        return view('user.balita.show', compact('balita', 'riwayatPemeriksaan', 'riwayatImunisasi'));
    }

    // FIXED: Method Imunisasi
    public function imunisasi()
    {
        $user = auth()->user();
        $nikUser = $this->getNikUser($user);

        // Ambil semua ID anak milik user ini
        $balitaIds = Balita::where('nik_ibu', $nikUser)
            ->orWhere('nik_ayah', $nikUser)
            ->pluck('id');

        $riwayatImunisasi = Imunisasi::whereHas('kunjungan', function($query) use ($balitaIds) {
                $query->whereIn('pasien_id', $balitaIds)
                    ->where('pasien_type', 'App\Models\Balita');
            })
            ->with('kunjungan') // Load relasi kunjungan
            ->orderBy('tanggal_imunisasi', 'desc')
            ->get();

        // Pastikan view ini ada (Langkah 2)
        return view('user.balita.imunisasi', compact('riwayatImunisasi'));
    }

    // Helper NIK
    private function getNikUser($user) {
        if (!empty($user->nik)) return $user->nik;
        if ($user->profile && !empty($user->profile->nik)) return $user->profile->nik;
        if (!empty($user->username) && is_numeric($user->username)) return $user->username;
        return null;
    }
}