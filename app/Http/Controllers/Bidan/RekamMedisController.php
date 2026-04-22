<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use App\Models\Imunisasi;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'balita');
        // Keamanan: Pastikan search berupa string, bukan array dari injeksi URL
        $search = is_array($request->get('search')) ? '' : $request->get('search');

        $models = [
            'balita'    => \App\Models\Balita::class,
            'ibu_hamil' => \App\Models\IbuHamil::class,
            'lansia'    => \App\Models\Lansia::class,
            'remaja'    => \App\Models\Remaja::class,
        ];

        $modelClass = $models[$type] ?? $models['balita'];
        $query = $modelClass::query();

        // Logika Pencarian Maksimal & Tahan Banting
        if (!empty($search)) {
            $query->where(function($q) use ($search, $type) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
                
                if($type === 'balita') {
                    $q->orWhere('nama_ibu', 'like', "%{$search}%");
                }
            });
        }

        // Menggunakan id desc lebih aman dari latest() jika timestamp DB tidak konsisten
        $data = $query->orderBy('id', 'desc')->paginate(12)->withQueryString();

        return view('bidan.rekam-medis.index', compact('data', 'type', 'search'));
    }

    public function show($pasien_type, $pasien_id)
    {
        $models = [
            'balita'    => \App\Models\Balita::class,
            'ibu_hamil' => \App\Models\IbuHamil::class,
            'lansia'    => \App\Models\Lansia::class,
            'remaja'    => \App\Models\Remaja::class,
        ];
        
        $modelClass = $models[$pasien_type] ?? abort(404);
        $pasien = $modelClass::findOrFail($pasien_id);

        // PERBAIKAN 1: Tabel 'pemeriksaans' ternyata menggunakan kolom 'kategori_pasien' (bukan pasien_type)
        $riwayatMedis = Pemeriksaan::with(['verifikator', 'pemeriksa'])
            ->where('pasien_id', $pasien_id)
            ->where('kategori_pasien', $pasien_type) // <--- BUG FIXED DI SINI
            ->where('status_verifikasi', 'verified')
            ->latest('tanggal_periksa')
            ->get();

        // PERBAIKAN 2: Tabel 'kunjungans' (Imunisasi) menggunakan kolom 'pasien_type'
        $riwayatImunisasi = Imunisasi::whereHas('kunjungan', function($q) use ($pasien_id, $modelClass) {
                $q->where('pasien_id', $pasien_id)
                  ->where('pasien_type', $modelClass); // <--- Tetap pakai modelClass
            })
            ->latest('tanggal_imunisasi')
            ->get();

        // Data Grafik (Ambil 7 data terakhir untuk tren)
        $chartData = $riwayatMedis->take(7)->reverse()->values();

        return view('bidan.rekam-medis.show', compact('pasien', 'pasien_type', 'riwayatMedis', 'riwayatImunisasi', 'chartData'));
    }
}