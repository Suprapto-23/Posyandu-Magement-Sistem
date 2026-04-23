<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\DetectsUserPeran;
use App\Models\Pemeriksaan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * IbuHamilController (User/Warga)
 *
 * [BUG-12 FIX] Controller ini sebelumnya tidak ada.
 * View ibu_hamil/index.blade.php membutuhkan variabel:
 *   - $ibuHamil          → model IbuHamil
 *   - $usiaHamilMinggu   → usia kehamilan dalam minggu (dari HPHT)
 *   - $riwayatPemeriksaan → riwayat pemeriksaan yang sudah divalidasi bidan
 *   - $user              → user yang sedang login
 *
 * Route yang perlu ditambahkan di web.php:
 *   Route::get('ibu-hamil', [IbuHamilController::class, 'index'])->name('ibu-hamil.index');
 */
class IbuHamilController extends Controller
{
    use DetectsUserPeran;

    /**
     * Halaman utama portal ibu hamil.
     * Route: GET /user/ibu-hamil → user.ibu-hamil.index
     */
    public function index()
    {
        $user = Auth::user();
        $ctx  = $this->getUserContext($user);

        // Jika tidak terdaftar sebagai bumil
        if (!$ctx['bumil']) {
            return view('user.ibu_hamil.empty', [
                'message' => $ctx['nik']
                    ? 'Kami tidak menemukan data kehamilan aktif untuk NIK ' . $ctx['nik'] . '. Silakan lapor ke Kader untuk pendataan.'
                    : 'NIK belum diisi. Silakan lengkapi profil Anda terlebih dahulu.',
            ]);
        }

        $ibuHamil = $ctx['bumil'];

        // Hitung usia kehamilan dalam minggu dari HPHT
        $usiaHamilMinggu = null;
        if ($ibuHamil->hpht) {
            $usiaHamilMinggu = (int) Carbon::parse($ibuHamil->hpht)->diffInWeeks(now());
        }

        // Riwayat pemeriksaan kebidanan yang sudah divalidasi bidan
        $riwayatPemeriksaan = Pemeriksaan::where('pasien_id', $ibuHamil->id)
            ->where('kategori_pasien', 'ibu_hamil')
            ->where('status_verifikasi', 'verified')
            ->orderBy('tanggal_periksa', 'desc')
            ->get();

        return view('user.ibu_hamil.index', compact(
            'user',
            'ibuHamil',
            'usiaHamilMinggu',
            'riwayatPemeriksaan'
        ));
    }
}