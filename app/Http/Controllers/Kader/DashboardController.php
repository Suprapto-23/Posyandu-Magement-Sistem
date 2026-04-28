<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;
use App\Models\Imunisasi;
use App\Models\JadwalPosyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama (Kartu Atas)
        $stats = [
            'total_balita'     => Balita::count(),
            'total_remaja'     => Remaja::count(),
            'total_lansia'     => Lansia::count(),
            'total_ibu_hamil'  => IbuHamil::count(),
            'imunisasi_hari_ini' => Imunisasi::whereDate('tanggal_imunisasi', today())->count(),
            'jadwal_hari_ini'  => JadwalPosyandu::whereDate('tanggal', today())
                ->where('status', 'aktif')
                ->count(),
        ];

        // 2. Data Grafik Absensi 7 Hari Terakhir
        $trendAbsensi  = $this->getAbsensi7Hari();
        $chartLabels   = $trendAbsensi['labels'];
        $chartData     = $trendAbsensi['data'];

        // 3. Pendaftaran Bulan Ini (untuk donut chart)
        $pendaftaran_bulan_ini = $this->getPendaftaranBulanIni();

        // 4. Aktivitas Terkini
        $balita_baru = Balita::latest()->take(5)->get();

        // 5. Jadwal Mendatang
        $jadwal_mendatang = JadwalPosyandu::where('tanggal', '>=', today())
            ->where('status', 'aktif')
            ->orderBy('tanggal', 'asc')
            ->take(4)
            ->get();

        return view('kader.dashboard', compact(
            'stats',
            'chartLabels',
            'chartData',
            'pendaftaran_bulan_ini',
            'balita_baru',
            'jadwal_mendatang'
        ));
    }

    private function getAbsensi7Hari()
    {
        $labels = [];
        $data   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date     = Carbon::today()->subDays($i);
            $labels[] = $date->translatedFormat('d M');
            // Menghitung total semua warga baru yang terdaftar per hari
            // (bisa diganti model Absensi jika sudah ada)
            $count = Balita::whereDate('created_at', $date->format('Y-m-d'))->count()
                   + IbuHamil::whereDate('created_at', $date->format('Y-m-d'))->count()
                   + Remaja::whereDate('created_at', $date->format('Y-m-d'))->count()
                   + Lansia::whereDate('created_at', $date->format('Y-m-d'))->count();
            $data[] = $count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getPendaftaranBulanIni()
    {
        $bulan = now()->month;
        $tahun = now()->year;

        return [
            'balita'    => Balita::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->count(),
            'remaja'    => Remaja::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->count(),
            'lansia'    => Lansia::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->count(),
            'ibu_hamil' => IbuHamil::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->count(),
        ];
    }
}