<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\Kunjungan;
use App\Models\Imunisasi;
use App\Models\JadwalPosyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Statistik Utama (Kartu Atas)
        $stats = [
            'total_balita' => Balita::count(),
            'total_remaja' => Remaja::count(),
            'total_lansia' => Lansia::count(),
            'kunjungan_hari_ini' => Kunjungan::whereDate('created_at', today())->count(),
            'kunjungan_saya_hari_ini' => Kunjungan::where('petugas_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),
            'imunisasi_hari_ini' => Imunisasi::whereDate('tanggal_imunisasi', today())->count(),
            'jadwal_hari_ini' => JadwalPosyandu::whereDate('tanggal', today())
                ->where('status', 'aktif')
                ->count(),
        ];
        
        // 2. Data Grafik Kunjungan 7 Hari Terakhir (Dinamis, termasuk yang 0)
        $trendKunjungan = $this->getKunjungan7Hari();
        $chartLabels = $trendKunjungan['labels'];
        $chartData = $trendKunjungan['data'];

        $pendaftaran_bulan_ini = $this->getPendaftaranBulanIni();
        
        $balita_baru = Balita::latest()->take(5)->get();
        $kunjungan_baru = Kunjungan::with('pasien')->latest()->take(5)->get();
        
        $jadwal_mendatang = JadwalPosyandu::where('tanggal', '>=', today())
            ->where('status', 'aktif')
            ->orderBy('tanggal')
            ->take(5)
            ->get();
        
        return view('kader.dashboard', compact(
            'stats', 
            'chartLabels',
            'chartData',
            'pendaftaran_bulan_ini',
            'balita_baru',
            'kunjungan_baru',
            'jadwal_mendatang'
        ));
    }
    
    /**
     * FUNGSI DIPERBAIKI: Mengambil array 7 hari terakhir secara pasti (meski data kosong)
     */
    private function getKunjungan7Hari()
    {
        $labels = [];
        $data = [];

        // Looping 7 hari ke belakang (Dari H-6 sampai Hari Ini)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            
            // Format label X-Axis, contoh: "19 Mar"
            $labels[] = $date->translatedFormat('d M'); 
            
            // Hitung kunjungan di hari tersebut
            $count = Kunjungan::whereDate('created_at', $date->format('Y-m-d'))->count();
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    private function getPendaftaranBulanIni()
    {
        $bulan_ini = now()->month;
        $tahun_ini = now()->year;
        
        return [
            'balita' => Balita::whereMonth('created_at', $bulan_ini)->whereYear('created_at', $tahun_ini)->count(),
            'remaja' => Remaja::whereMonth('created_at', $bulan_ini)->whereYear('created_at', $tahun_ini)->count(),
            'lansia' => Lansia::whereMonth('created_at', $bulan_ini)->whereYear('created_at', $tahun_ini)->count()
        ];
    }
}