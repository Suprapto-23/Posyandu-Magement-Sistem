<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\DetectsUserPeran;
use App\Models\Pemeriksaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * LansiaController (User/Warga)
 *
 * Menampilkan halaman kesehatan untuk warga kategori Lansia.
 *
 * PERBAIKAN dari versi sebelumnya:
 * 1. Hapus 4 method tanpa route: showKunjungan, editProfile, updateProfile, riwayatMedis
 *    (method-method ini dead code — route-nya tidak ada di web.php)
 * 2. Gunakan Trait DetectsUserPeran
 * 3. Query langsung ke tabel pemeriksaans (lebih sederhana dari via kunjungans)
 * 4. Field konsisten dengan tabel pemeriksaans:
 *    - gula_darah (bukan gula_darah_sewaktu)
 *    - tekanan_darah
 *    - tingkat_kemandirian
 */
class LansiaController extends Controller
{
    use DetectsUserPeran;

    /**
     * Halaman utama portal kesehatan lansia.
     */
    public function index()
    {
        $user = Auth::user();
        $ctx  = $this->getUserContext($user);

        // Jika tidak terdaftar sebagai lansia
        if (!$ctx['lansia']) {
            return view('user.lansia.empty', [
                'nik'   => $ctx['nik'],
                'pesan' => $ctx['nik']
                    ? 'NIK ' . $ctx['nik'] . ' belum terdaftar sebagai lansia di Posyandu. Hubungi kader.'
                    : 'NIK belum diisi. Silakan lengkapi profil Anda.',
            ]);
        }

        $lansia = $ctx['lansia'];

        // Hitung usia
        $usia = Carbon::parse($lansia->tanggal_lahir)->age;

        // Riwayat pemeriksaan terverifikasi
        $riwayatPemeriksaan = collect();
        try {
            $riwayatPemeriksaan = Pemeriksaan::where('pasien_id', $lansia->id)
                ->where('kategori_pasien', 'lansia')
                ->where('status_verifikasi', 'verified')
                ->orderBy('tanggal_periksa', 'desc')
                ->take(6)
                ->get();
        } catch (\Throwable $e) {
            Log::warning('LansiaController: pemeriksaan error - ' . $e->getMessage());
        }

        $pemeriksaanTerakhir = $riwayatPemeriksaan->first();

        // Flag kondisi kritis untuk tampilan (deteksi visual saja, bukan diagnosis)
        // Ini hanya tampilan warna UI, BUKAN diagnosis otomatis
        $alerts = [];
        if ($pemeriksaanTerakhir) {
            $sistolik = intval(explode('/', $pemeriksaanTerakhir->tekanan_darah ?? '0/0')[0]);
            if ($sistolik >= 140) {
                $alerts[] = ['tipe' => 'tensi', 'pesan' => 'Tekanan darah tercatat tinggi pada pemeriksaan terakhir. Konsultasikan ke bidan.'];
            }
            $gds = intval($pemeriksaanTerakhir->gula_darah ?? 0);
            if ($gds >= 200) {
                $alerts[] = ['tipe' => 'gula', 'pesan' => 'Gula darah tercatat tinggi. Konsultasikan ke bidan.'];
            }
        }

        $stats = [
            'usia'               => $usia,
            'total_kunjungan'    => $riwayatPemeriksaan->count(),
            'kunjungan_terakhir' => $pemeriksaanTerakhir,
        ];

        return view('user.lansia.index', compact(
            'lansia',
            'riwayatPemeriksaan',
            'pemeriksaanTerakhir',
            'stats',
            'alerts'
        ));
    }

    /**
     * Riwayat pemeriksaan lengkap dengan paginasi.
     * Route: GET /user/lansia/riwayat → user.lansia.riwayat
     */
    public function riwayat()
    {
        $user = Auth::user();
        $ctx  = $this->getUserContext($user);

        if (!$ctx['lansia']) {
            return redirect()->route('user.lansia.index');
        }

        $riwayatPemeriksaan = Pemeriksaan::where('pasien_id', $ctx['lansia']->id)
            ->where('kategori_pasien', 'lansia')
            ->where('status_verifikasi', 'verified')
            ->orderBy('tanggal_periksa', 'desc')
            ->paginate(10);

        return view('user.lansia.riwayat', [
            'lansia'             => $ctx['lansia'],
            'riwayatPemeriksaan' => $riwayatPemeriksaan,
        ]);
    }
}