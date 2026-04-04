<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;
use App\Models\Pemeriksaan;
use App\Models\JadwalPosyandu;

class DashboardController extends Controller
{
    /**
     * Menampilkan Halaman Command Center Klinis Bidan
     */
    public function index()
    {
        // 1. STATISTIK UTAMA (TRIASE & BEBAN KERJA HARI INI)
        $stats = [
            // Total Data Warga di Database
            'total_pasien' => Balita::count() + Remaja::count() + Lansia::count() + IbuHamil::count(),
            
            // Antrian Meja 5 (Menunggu Bidan memvalidasi dan memberi resep/diagnosa)
            'menunggu_validasi' => Pemeriksaan::where('status_verifikasi', 'pending')->count(),
            
            // Kinerja Bidan hari ini
            'selesai_divalidasi' => Pemeriksaan::where('status_verifikasi', 'verified')
                                        ->whereDate('updated_at', Carbon::today())
                                        ->count(),
            
            // Jadwal pelayanan aktif hari ini
            'jadwal_hari_ini' => JadwalPosyandu::whereDate('tanggal', Carbon::today())
                                        ->where('status', 'aktif')
                                        ->count(),
        ];

        // 2. ALERT RISIKO TINGGI KESEHATAN WARTA
        $alertRisiko = [
            'balita_stunting' => Pemeriksaan::where('kategori_pasien', 'balita')
                                    ->whereIn('status_gizi', ['Stunting', 'Buruk', 'Kurang'])
                                    ->count(),
            'lansia_hipertensi' => Pemeriksaan::where('kategori_pasien', 'lansia')
                                    ->where(function($q) {
                                        // Deteksi dari teks diagnosa kader atau tensi > 140
                                        $q->where('diagnosa', 'like', '%hipertensi%')
                                          ->orWhereRaw("CAST(SUBSTRING_INDEX(tekanan_darah, '/', 1) AS UNSIGNED) >= 140");
                                    })->count(),
        ];

        // 3. DATA ANTRIAN LIVE (5 Pasien Terakhir yang dikirim Kader ke Meja 5)
        $antrianLive = Pemeriksaan::with(['balita', 'remaja', 'lansia', 'pemeriksa'])
                            ->where('status_verifikasi', 'pending')
                            ->latest('created_at')
                            ->take(5)
                            ->get();

        // 4. DATA GRAFIK (Tren Pemeriksaan 7 Hari Terakhir)
        $trend = $this->getTrend7Hari();
        $chartLabels = $trend['labels'];
        $chartData = $trend['data'];

        return view('bidan.dashboard', compact(
            'stats', 
            'alertRisiko', 
            'antrianLive', 
            'chartLabels', 
            'chartData'
        ));
    }

    /**
     * FUNGSI BANTUAN: Generate Array 7 Hari untuk Chart.js
     */
    private function getTrend7Hari()
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->translatedFormat('d M'); 
            
            // Hitung pemeriksaan medis (verified) di hari tersebut
            $count = Pemeriksaan::where('status_verifikasi', 'verified')
                                ->whereDate('updated_at', $date->format('Y-m-d'))
                                ->count();
            $data[] = $count;
        }

        return ['labels' => $labels, 'data' => $data];
    }
}