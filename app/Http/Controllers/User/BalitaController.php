<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\DetectsUserPeran;
use App\Models\Pemeriksaan;
use App\Models\Imunisasi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class BalitaController extends Controller
{
    use DetectsUserPeran;

    public function index()
    {
        $user = Auth::user();
        $ctx  = $this->getUserContext($user);

        if ($ctx['balitas']->isEmpty()) {
            return view('user.balita.index', [
                'dataBalita' => collect(),
                'pesan'      => $ctx['nik']
                    ? 'Belum ada data balita untuk NIK ' . $ctx['nik'] . '. Hubungi kader untuk pendaftaran.'
                    : 'NIK belum diisi. Silakan lengkapi profil Anda terlebih dahulu.',
            ]);
        }

        $dataBalita = $ctx['balitas']->map(function ($balita) {
            $diff = Carbon::parse($balita->tanggal_lahir)->diff(now());
            $balita->usia_tahun = $diff->y;
            $balita->usia_bulan = $diff->m;
            $balita->usia_hari  = $diff->d;

            // [BUG-9 FIX] kategori_medis berdasarkan usia total bulan
            $totalBulan = ($diff->y * 12) + $diff->m;
            $balita->kategori_medis = $totalBulan < 12 ? 'Bayi' : 'Balita';

            // Pemeriksaan terakhir yang SUDAH divalidasi bidan
            $balita->riwayatPemeriksaan = Pemeriksaan::where('pasien_id', $balita->id)
                ->whereIn('kategori_pasien', ['balita', 'bayi'])
                ->where('status_verifikasi', 'verified')
                ->orderBy('tanggal_periksa', 'desc')
                ->get();

            $balita->riwayatImunisasi = Imunisasi::whereHas('kunjungan', function ($q) use ($balita) {
                $q->where('pasien_id', $balita->id)
                  ->where('pasien_type', 'App\Models\Balita');
            })->orderBy('tanggal_imunisasi', 'desc')->get();

            return $balita;
        });

        return view('user.balita.index', compact('dataBalita'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $ctx  = $this->getUserContext($user);

        $balita = $ctx['balitas']->find($id);
        if (!$balita) {
            return redirect()->route('user.balita.index')
                ->with('error', 'Data tidak ditemukan atau bukan milik Anda.');
        }

        $diff       = Carbon::parse($balita->tanggal_lahir)->diff(now());
        $usia_tahun = $diff->y;
        $usia_bulan = $diff->m;
        $usia_hari  = $diff->d;

        $totalBulan = ($usia_tahun * 12) + $usia_bulan;
        $balita->kategori_medis = $totalBulan < 12 ? 'Bayi' : 'Balita';

        // ASC untuk grafik (urutan waktu maju)
        $riwayatPemeriksaan = Pemeriksaan::where('pasien_id', $balita->id)
            ->whereIn('kategori_pasien', ['balita', 'bayi'])
            ->where('status_verifikasi', 'verified')
            ->orderBy('tanggal_periksa', 'asc')
            ->get();

        // [BUG-10 FIX] grafikData untuk Chart.js
        $grafikData = [];
        if ($riwayatPemeriksaan->isNotEmpty()) {
            $grafikData = [
                'labels' => $riwayatPemeriksaan->map(
                    fn($p) => Carbon::parse($p->tanggal_periksa)->format('d M y')
                )->toArray(),
                'berat'  => $riwayatPemeriksaan->pluck('berat_badan')
                    ->map(fn($v) => $v ? (float)$v : null)->toArray(),
                'tinggi' => $riwayatPemeriksaan->pluck('tinggi_badan')
                    ->map(fn($v) => $v ? (float)$v : null)->toArray(),
                'lk'     => $riwayatPemeriksaan->pluck('lingkar_kepala')
                    ->map(fn($v) => $v ? (float)$v : null)->toArray(),
            ];
        }

        $riwayatImunisasi = Imunisasi::whereHas('kunjungan', function ($q) use ($balita) {
            $q->where('pasien_id', $balita->id)
              ->where('pasien_type', 'App\Models\Balita');
        })->orderBy('tanggal_imunisasi', 'desc')->get();

        return view('user.balita.show', compact(
            'balita', 'riwayatPemeriksaan', 'riwayatImunisasi',
            'grafikData', 'usia_tahun', 'usia_bulan', 'usia_hari'
        ));
    }

    public function imunisasi()
    {
        $user = Auth::user();
        $ctx  = $this->getUserContext($user);
        $balitaIds = $ctx['balitas']->pluck('id');

        $riwayatImunisasi = collect();
        if ($balitaIds->isNotEmpty()) {
            $riwayatImunisasi = Imunisasi::whereHas('kunjungan', function ($q) use ($balitaIds) {
                $q->whereIn('pasien_id', $balitaIds)
                  ->where('pasien_type', 'App\Models\Balita');
            })->with(['kunjungan.pasien'])->orderBy('tanggal_imunisasi', 'desc')->get();
        }

        return view('user.balita.imunisasi', compact('riwayatImunisasi'));
    }
}