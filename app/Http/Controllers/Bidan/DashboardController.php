<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// Import Model
use App\Models\Balita;
use App\Models\Lansia;
use App\Models\Pemeriksaan;
use App\Models\JadwalPosyandu;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. STATISTIK KARTU ATAS
        $totalBalita = Balita::count();
        $totalLansia = Lansia::count();

        // Hitung Balita Stunting
        $balitaStunting = Pemeriksaan::where('kategori_pasien', 'balita')
            ->whereIn('status_gizi', ['stunting', 'buruk'])
            ->count();

        // Hitung Lansia Hipertensi
        $lansiaHipertensi = Pemeriksaan::where('kategori_pasien', 'lansia')
            ->where(function($q) {
                $q->where('tekanan_darah', 'like', '14%')
                  ->orWhere('tekanan_darah', 'like', '15%')
                  ->orWhere('tekanan_darah', 'like', '16%')
                  ->orWhere('diagnosa', 'like', '%hipertensi%');
            })
            ->count();

        // 2. ANTRIAN VALIDASI (PERBAIKAN ERROR DI SINI)
        // Logika Baru: Cari yang diagnosanya NULL atau KOSONG saja.
        // Tidak lagi mencari kolom 'status_verifikasi' yang hilang.
        $antrianPemeriksaan = Pemeriksaan::with(['balita', 'remaja', 'lansia'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->where(function($q) {
                $q->whereNull('diagnosa')
                  ->orWhere('diagnosa', '');
            })
            ->latest()
            ->take(5)
            ->get();

        $jumlahBelumValidasi = Pemeriksaan::whereMonth('created_at', Carbon::now()->month)
            ->where(function($q) {
                $q->whereNull('diagnosa')
                  ->orWhere('diagnosa', '');
            })
            ->count();

        // 3. PASIEN BERISIKO
        $pasienBerisiko = Pemeriksaan::with(['balita', 'lansia', 'remaja'])
            ->where(function($q) {
                $q->whereIn('status_gizi', ['stunting', 'buruk', 'obesitas'])
                  ->orWhere('diagnosa', 'like', '%rujuk%')
                  ->orWhere('diagnosa', 'like', '%risiko%');
            })
            ->latest('tanggal_periksa')
            ->take(5)
            ->get();

        // 4. JADWAL BERIKUTNYA
        $jadwalBerikutnya = JadwalPosyandu::whereDate('tanggal', '>=', Carbon::today())
            ->where('status', 'aktif')
            ->orderBy('tanggal', 'asc')
            ->first();

        // 5. CHART GIZI
        $chartGizi = [
            'normal'   => Pemeriksaan::where('kategori_pasien', 'balita')->where('status_gizi', 'baik')->count(),
            'kurang'   => Pemeriksaan::where('kategori_pasien', 'balita')->where('status_gizi', 'kurang')->count(),
            'stunting' => Pemeriksaan::where('kategori_pasien', 'balita')->whereIn('status_gizi', ['stunting', 'buruk'])->count(),
            'lebih'    => Pemeriksaan::where('kategori_pasien', 'balita')->whereIn('status_gizi', ['lebih', 'obesitas'])->count(),
        ];

        // 6. CHART KUNJUNGAN
        $chartKunjungan = Pemeriksaan::select(
                DB::raw('MONTH(created_at) as bulan'), 
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->all();

        $dataKunjungan = [];
        $labelBulan = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $bulanAngka = $date->month;
            $labelBulan[] = $date->translatedFormat('F');
            $dataKunjungan[] = $chartKunjungan[$bulanAngka] ?? 0;
        }

        return view('bidan.dashboard', compact(
            'totalBalita', 'totalLansia', 'balitaStunting', 'lansiaHipertensi',
            'pasienBerisiko', 'jadwalBerikutnya', 'antrianPemeriksaan', 'jumlahBelumValidasi',
            'chartGizi', 'dataKunjungan', 'labelBulan'
        ));
    }
}