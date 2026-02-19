<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\Kunjungan;
use App\Models\Imunisasi;
use App\Models\JadwalPosyandu; // PASTIKAN INI DI-IMPORT
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Statistik Utama
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
        
        // Data untuk chart
        $kunjungan_7_hari = $this->getKunjungan7Hari();
        $pendaftaran_bulan_ini = $this->getPendaftaranBulanIni();
        
        // Data terbaru
        $balita_baru = Balita::latest()->take(5)->get();
        $kunjungan_baru = Kunjungan::with(['pasien'])->latest()->take(5)->get();
        $jadwal_mendatang = JadwalPosyandu::where('tanggal', '>=', today())
            ->where('status', 'aktif')
            ->orderBy('tanggal')
            ->take(5)
            ->get();
        
        return view('kader.dashboard', compact(
            'stats', 
            'kunjungan_7_hari',
            'pendaftaran_bulan_ini',
            'balita_baru',
            'kunjungan_baru',
            'jadwal_mendatang'
        ));
    }
    
    private function getKunjungan7Hari()
    {
        return Kunjungan::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('COUNT(*) as total')
        )
        ->whereDate('created_at', '>=', now()->subDays(7))
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->get();
    }
    
    private function getPendaftaranBulanIni()
    {
        $bulan_ini = now()->month;
        $tahun_ini = now()->year;
        
        $balita = Balita::whereMonth('created_at', $bulan_ini)
            ->whereYear('created_at', $tahun_ini)
            ->count();
        
        $remaja = Remaja::whereMonth('created_at', $bulan_ini)
            ->whereYear('created_at', $tahun_ini)
            ->count();
        
        $lansia = Lansia::whereMonth('created_at', $bulan_ini)
            ->whereYear('created_at', $tahun_ini)
            ->count();
        
        return [
            'balita' => $balita,
            'remaja' => $remaja,
            'lansia' => $lansia,
            'total' => $balita + $remaja + $lansia
        ];
    }
}