<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\JadwalPosyandu;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;
use App\Traits\DetectsUserPeran;
use Carbon\Carbon;

/**
 * JadwalController (User/Warga)
 *
 * PERBAIKAN [BUG-8]:
 * View baru jadwal menggunakan URL param 'target' (?target=balita)
 * tapi controller membaca 'filter'. Semua distandarisasi ke 'filter'
 * agar konsisten. View harus pakai ?filter=balita.
 *
 * Juga ditambahkan:
 * - Deteksi ibu_hamil via Trait DetectsUserPeran
 * - hakAkses yang lebih akurat (include ibu_hamil jika terdaftar)
 */
class JadwalController extends Controller
{
    use DetectsUserPeran;

    /**
     * Daftar jadwal posyandu yang relevan untuk user yang login.
     * Route: GET /user/jadwal → user.jadwal.index
     *
     * Query param: filter (semua|balita|remaja|lansia|ibu_hamil)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $ctx  = $this->getUserContext($user);

        // Tentukan hak akses berdasarkan data user di database
        $hakAkses = ['semua'];
        if (in_array('orang_tua', $ctx['peran'])) $hakAkses[] = 'balita';
        if (in_array('remaja',    $ctx['peran'])) $hakAkses[] = 'remaja';
        if (in_array('lansia',    $ctx['peran'])) $hakAkses[] = 'lansia';
        if (in_array('bumil',     $ctx['peran'])) $hakAkses[] = 'ibu_hamil';

        // [BUG-8 FIX] Semua konsisten pakai param 'filter', bukan 'target'
        $filterTarget = $request->get('filter', 'semua');
        $validFilters = ['semua', 'balita', 'remaja', 'lansia', 'ibu_hamil'];
        if (!in_array($filterTarget, $validFilters)) {
            $filterTarget = 'semua';
        }

        // Bangun query dasar
        $query = JadwalPosyandu::where('status', 'aktif')
            ->whereIn('target_peserta', $hakAkses);

        // Terapkan filter tab
        if ($filterTarget !== 'semua') {
            $query->where('target_peserta', $filterTarget);
        }

        // Urutan: mendatang dulu (ASC), lalu yang sudah lewat (DESC)
        $query->orderByRaw("
            CASE WHEN tanggal >= CURDATE() THEN 0 ELSE 1 END ASC,
            CASE WHEN tanggal >= CURDATE() THEN tanggal ELSE NULL END ASC,
            CASE WHEN tanggal < CURDATE() THEN tanggal ELSE NULL END DESC,
            waktu_mulai ASC
        ");

        $jadwalKegiatan = $query->paginate(9)->withQueryString();

        // Hitung badge count per tab
        $base = JadwalPosyandu::where('status', 'aktif')
            ->whereIn('target_peserta', $hakAkses);

        $summary = [
            'semua'     => (clone $base)->count(),
            'balita'    => (clone $base)->where('target_peserta', 'balita')->count(),
            'remaja'    => (clone $base)->where('target_peserta', 'remaja')->count(),
            'lansia'    => (clone $base)->where('target_peserta', 'lansia')->count(),
            'ibu_hamil' => (clone $base)->where('target_peserta', 'ibu_hamil')->count(),
            'mendatang' => (clone $base)->whereDate('tanggal', '>=', Carbon::today())->count(),
        ];

        return view('user.jadwal.index', compact(
            'jadwalKegiatan',
            'hakAkses',
            'filterTarget',
            'summary'
        ));
    }
}