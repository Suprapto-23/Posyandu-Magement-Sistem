<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    /**
     * Menampilkan Buku Kehadiran (Read-Only)
     */
    public function index(Request $request)
    {
        $search   = $request->get('search', '');
        $kategori = $request->get('kategori', 'semua');

        $query = Kunjungan::with(['pasien', 'petugas', 'pemeriksaan', 'imunisasis'])
                    ->latest('tanggal_kunjungan')
                    ->latest('created_at');

        // 1. Filter Berdasarkan Kategori Tab
        if ($kategori !== 'semua') {
            $pasienType = match($kategori) {
                'remaja'    => 'App\\Models\\Remaja',
                'lansia'    => 'App\\Models\\Lansia',
                'ibu_hamil' => 'App\\Models\\IbuHamil',
                default     => 'App\\Models\\Balita',
            };
            $query->where('pasien_type', $pasienType);
        }

        // 2. Filter Berdasarkan Pencarian Cerdas
        if ($search) {
            $balitaIds = Balita::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            $remajaIds = Remaja::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            $lansiaIds = Lansia::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            $bumilIds  = IbuHamil::where('nama_lengkap', 'like', "%$search%")->pluck('id');

            $query->where(function($q) use($balitaIds, $remajaIds, $lansiaIds, $bumilIds) {
                $q->where(fn($q2)=>$q2->whereIn('pasien_type', ['App\\Models\\Balita'])->whereIn('pasien_id', $balitaIds))
                  ->orWhere(fn($q2)=>$q2->where('pasien_type', 'App\\Models\\Remaja')->whereIn('pasien_id', $remajaIds))
                  ->orWhere(fn($q2)=>$q2->where('pasien_type', 'App\\Models\\Lansia')->whereIn('pasien_id', $lansiaIds))
                  ->orWhere(fn($q2)=>$q2->where('pasien_type', 'App\\Models\\IbuHamil')->whereIn('pasien_id', $bumilIds));
            });
        }

        $kunjungans = $query->paginate(15)->withQueryString();

        // Respon AJAX (Tanpa Refresh)
        if ($request->ajax()) {
            return view('kader.kunjungan.index', compact('kunjungans', 'search', 'kategori'))->render();
        }

        return view('kader.kunjungan.index', compact('kunjungans', 'search', 'kategori'));
    }

    /**
     * Menampilkan Detail Nota Kedatangan
     */
    public function show($id)
    {
        $kunjungan = Kunjungan::with(['pasien', 'petugas', 'pemeriksaan', 'imunisasis'])->findOrFail($id);
        return view('kader.kunjungan.show', compact('kunjungan'));
    }

    // ====================================================================
    // BLOKIR AKSES CRUD
    // Kader tidak boleh menginput kunjungan manual dari sini.
    // Kunjungan otomatis dibuat saat kader menginput data Pemeriksaan Fisik.
    // ====================================================================

    public function create()
    {
        return back()->with('error', 'Kunjungan baru akan otomatis tercatat saat Anda menginput Pengukuran Fisik.');
    }

    public function store(Request $request)
    {
        abort(403, 'Akses ditolak.');
    }

    public function edit($id)
    {
        return back()->with('error', 'Data kunjungan adalah log otomatis. Silakan edit melalui menu Pemeriksaan jika ada kesalahan.');
    }

    public function update(Request $request, $id)
    {
        abort(403, 'Akses ditolak.');
    }

    public function destroy($id)
    {
        return back()->with('error', 'Buku Tamu tidak boleh dihapus. Hapus data melalui menu Pemeriksaan.');
    }
}