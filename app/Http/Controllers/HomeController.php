<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\DetectsUserPeran;

/**
 * HomeController
 *
 * Titik tengah setelah login. Controller ini HANYA bertugas redirect,
 * tidak merender view apapun.
 *
 * ALUR:
 *   Login berhasil → HomeController@index
 *     ↓
 *     Cek role dari users.role (admin/bidan/kader/user)
 *     ↓
 *     Admin  → /admin/dashboard
 *     Bidan  → /bidan/dashboard
 *     Kader  → /kader/dashboard
 *     User (warga) → DetectsUserPeran → redirect ke halaman spesifik:
 *       orang_tua → /user/balita   (KMS Anak)
 *       remaja    → /user/remaja
 *       lansia    → /user/lansia
 *       bumil     → /user/dashboard (ibu hamil sementara di dashboard)
 *       multi-peran → /user/dashboard
 *       umum      → /user/dashboard (dengan pesan minta isi NIK)
 *
 * CATATAN UNTUK SIDANG:
 * Ini adalah implementasi Role-Based Access Control (RBAC) sesuai
 * dengan metodologi RAD — role dideteksi otomatis dari data, bukan
 * dikonfigurasi manual oleh admin.
 */
class HomeController extends Controller
{
    use DetectsUserPeran;

    /**
     * Smart redirect setelah login berhasil.
     * TIDAK merender view — hanya redirect sesuai role.
     */
    public function index()
    {
        $user = Auth::user();

        // Redirect berdasarkan role sistem (admin/bidan/kader)
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'bidan':
                return redirect()->route('bidan.dashboard');

            case 'kader':
                return redirect()->route('kader.dashboard');

            case 'user':
            default:
                return $this->redirectWarga($user);
        }
    }

    /**
     * Redirect warga ke halaman yang sesuai berdasarkan data mereka.
     * Menggunakan DetectsUserPeran untuk deteksi otomatis.
     */
    private function redirectWarga($user)
    {
        // Dapatkan context user (NIK, peran, entitas terkait)
        $ctx = $this->getUserContext($user);

        // Jika user punya multi-peran → tampilkan dashboard umum
        // (Dashboard sudah menampilkan semua kartu sesuai peran)
        if ($ctx['is_multi_peran']) {
            return redirect()->route('user.dashboard');
        }

        // Redirect berdasarkan peran tunggal
        $peranUtama = $ctx['peran'][0] ?? 'umum';

        switch ($peranUtama) {
            case 'orang_tua':
                // Langsung ke KMS Balita
                return redirect()->route('user.balita.index');

            case 'remaja':
                // Langsung ke Ruang Remaja
                return redirect()->route('user.remaja.index');

            case 'lansia':
                // Langsung ke Portal Lansia
                return redirect()->route('user.lansia.index');

            case 'bumil':
                // Ibu hamil ke dashboard (belum ada halaman khusus bumil)
                // TODO: Buat route user.bumil.index jika perlu
                return redirect()->route('user.dashboard');

            case 'umum':
            default:
                // Warga belum terdaftar → dashboard dengan prompt isi NIK
                return redirect()->route('user.dashboard');
        }
    }
}