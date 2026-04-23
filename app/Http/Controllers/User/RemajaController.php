<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\DetectsUserPeran;
use App\Models\Remaja;
use App\Models\Pemeriksaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * RemajaController (User/Warga)
 *
 * Menampilkan halaman kesehatan untuk warga kategori Remaja.
 * Data yang ditampilkan hanya untuk remaja yang NIK-nya cocok
 * dengan user yang sedang login — tidak bisa melihat data orang lain.
 *
 * PERBAIKAN dari versi sebelumnya:
 * 1. Hapus dependency ke model KonselingRemaja (tidak ada tabelnya)
 *    → Konseling sudah ditangani User\KonselingController (tabel: konselings)
 * 2. Fix field 'hemoglobin' → 'hb' (sesuai kolom di tabel pemeriksaans)
 * 3. Gunakan Trait DetectsUserPeran (bukan inline NIK detection)
 * 4. Tambah try-catch untuk keamanan
 */
class RemajaController extends Controller
{
    use DetectsUserPeran;

    /**
     * Halaman utama portal kesehatan remaja.
     * Menampilkan data fisik terakhir yang SUDAH DIVALIDASI bidan.
     */
    public function index()
    {
        $user = Auth::user();
        $ctx  = $this->getUserContext($user);

        // Jika tidak terdaftar sebagai remaja
        if (!$ctx['remaja']) {
            return view('user.remaja.empty', [
                'nik'     => $ctx['nik'],
                'pesan'   => $ctx['nik']
                    ? 'NIK ' . $ctx['nik'] . ' belum terdaftar sebagai remaja di Posyandu. Hubungi kader.'
                    : 'NIK belum diisi. Lengkapi profil Anda terlebih dahulu.',
            ]);
        }

        $remaja = $ctx['remaja'];

        // Pemeriksaan terakhir yang SUDAH DIVALIDASI bidan
        // status_verifikasi = 'verified' → data sudah dicek bidan, aman ditampilkan
        $pemeriksaanTerakhir = null;
        $riwayatPemeriksaan  = collect();

        try {
            $pemeriksaanTerakhir = Pemeriksaan::where('pasien_id', $remaja->id)
                ->where('kategori_pasien', 'remaja')
                ->where('status_verifikasi', 'verified')
                ->orderBy('tanggal_periksa', 'desc')
                ->first();

            $riwayatPemeriksaan = Pemeriksaan::where('pasien_id', $remaja->id)
                ->where('kategori_pasien', 'remaja')
                ->where('status_verifikasi', 'verified')
                ->orderBy('tanggal_periksa', 'desc')
                ->take(6)
                ->get();
        } catch (\Throwable $e) {
            Log::warning('RemajaController: pemeriksaan query error - ' . $e->getMessage());
        }

        return view('user.remaja.index', compact(
            'remaja',
            'pemeriksaanTerakhir',
            'riwayatPemeriksaan'
        ));
    }

    /**
     * Halaman riwayat pemeriksaan lengkap (semua, bukan hanya 6 terakhir).
     */
    public function riwayat()
    {
        $user = Auth::user();
        $ctx  = $this->getUserContext($user);

        if (!$ctx['remaja']) {
            return redirect()->route('user.remaja.index');
        }

        $riwayatPemeriksaan = Pemeriksaan::where('pasien_id', $ctx['remaja']->id)
            ->where('kategori_pasien', 'remaja')
            ->where('status_verifikasi', 'verified')
            ->orderBy('tanggal_periksa', 'desc')
            ->paginate(10);

        return view('user.remaja.riwayat', [
            'remaja'             => $ctx['remaja'],
            'riwayatPemeriksaan' => $riwayatPemeriksaan,
        ]);
    }
}