<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Imunisasi;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use Illuminate\Http\Request;

class ImunisasiController extends Controller
{
    /**
     * Menampilkan Buku Log Riwayat Imunisasi (Read-Only untuk Kader)
     */
    public function index(Request $request)
    {
        // Menangkap parameter untuk Tab dan Pencarian
        $kategori = $request->get('kategori', 'semua');
        $search   = $request->get('search', '');

        // 1. Tarik semua data imunisasi (Tanpa membatasi ID Kader, agar inputan Bidan masuk)
        $query = Imunisasi::with(['kunjungan.pasien', 'kunjungan.petugas'])
                    ->latest('tanggal_imunisasi');

        // 2. Filter Berdasarkan Kategori Tab (Balita / Remaja / Lansia)
        if ($kategori !== 'semua') {
            $pasienType = match($kategori) {
                'remaja' => 'App\Models\Remaja',
                'lansia' => 'App\Models\Lansia',
                default  => 'App\Models\Balita',
            };
            $query->whereHas('kunjungan', function($q) use ($pasienType) {
                $q->where('pasien_type', $pasienType);
            });
        }

        // 3. Filter Berdasarkan Pencarian Real-Time (Nama Pasien atau Vaksin)
        if ($search) {
            $balitaIds = Balita::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            $remajaIds = Remaja::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            $lansiaIds = Lansia::where('nama_lengkap', 'like', "%$search%")->pluck('id');

            $query->where(function($q) use($balitaIds, $remajaIds, $lansiaIds, $search) {
                // Cari dari nama atau jenis vaksin
                $q->where('vaksin', 'like', "%{$search}%")
                  ->orWhere('jenis_imunisasi', 'like', "%{$search}%")
                  // Atau cari dari relasi nama pasien
                  ->orWhereHas('kunjungan', function($q2) use ($balitaIds, $remajaIds, $lansiaIds) {
                      $q2->where(fn($q3) => $q3->where('pasien_type', 'App\Models\Balita')->whereIn('pasien_id', $balitaIds))
                         ->orWhere(fn($q3) => $q3->where('pasien_type', 'App\Models\Remaja')->whereIn('pasien_id', $remajaIds))
                         ->orWhere(fn($q3) => $q3->where('pasien_type', 'App\Models\Lansia')->whereIn('pasien_id', $lansiaIds));
                  });
            });
        }

        // 4. Lakukan Pagination (Simpan parameter URL agar page 2 tidak hilang filternya)
        $imunisasis = $query->paginate(10)->withQueryString();
            
        return view('kader.imunisasi.index', compact('imunisasis', 'kategori', 'search'));
    }

    /**
     * Menampilkan Detail Imunisasi
     */
    public function show($id)
    {
        $imunisasi = Imunisasi::with(['kunjungan.pasien', 'kunjungan.petugas'])
            ->findOrFail($id);
            
        return view('kader.imunisasi.show', compact('imunisasi'));
    }

    // =====================================================================
    // BLOKIR AKSES CRUD UNTUK KADER (SECURITY LEVEL)
    // Walaupun di UI tombolnya disembunyikan, kita wajib kunci di Controller
    // =====================================================================

    public function create($kunjungan_id = null)
    {
        return back()->with('error', 'Akses ditolak! Input data Imunisasi/Vaksin hanya dapat dilakukan oleh Tenaga Bidan.');
    }

    public function store(Request $request, $kunjungan_id = null)
    {
        abort(403, 'Akses Ditolak. Kewenangan Bidan.');
    }

    public function edit($id)
    {
        return back()->with('error', 'Akses ditolak! Hanya Bidan yang dapat mengubah data rekam medis Imunisasi.');
    }

    public function update(Request $request, $id)
    {
        abort(403, 'Akses Ditolak. Kewenangan Bidan.');
    }

    public function destroy($id)
    {
        return back()->with('error', 'Akses ditolak! Penghapusan riwayat Imunisasi hanya dapat dilakukan oleh Bidan.');
    }
}