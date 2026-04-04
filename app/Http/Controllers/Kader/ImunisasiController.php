<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Imunisasi;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;
use Illuminate\Http\Request;

class ImunisasiController extends Controller
{
    /**
     * Menampilkan Buku Log Riwayat Imunisasi (Read-Only untuk Kader)
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'semua');
        $search   = $request->get('search', '');

        $query = Imunisasi::with(['kunjungan.petugas'])->latest('tanggal_imunisasi');

        // 1. Filter Kategori Tab
        if ($kategori !== 'semua') {
            $pasienType = match($kategori) {
                'remaja'    => 'App\\Models\\Remaja',
                'lansia'    => 'App\\Models\\Lansia',
                'ibu_hamil' => 'App\\Models\\IbuHamil',
                default     => 'App\\Models\\Balita',
            };
            $query->whereHas('kunjungan', function($q) use ($pasienType) {
                $q->where('pasien_type', $pasienType);
            });
        }

        // 2. Pencarian Real-Time (Nama Pasien & Vaksin)
        if ($search) {
            $balitaIds = Balita::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            $remajaIds = Remaja::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            $lansiaIds = Lansia::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            $bumilIds  = IbuHamil::where('nama_lengkap', 'like', "%$search%")->pluck('id');

            $query->where(function($q) use($search, $balitaIds, $remajaIds, $lansiaIds, $bumilIds) {
                $q->where('vaksin', 'like', "%$search%") // Cari nama vaksin
                  ->orWhereHas('kunjungan', function($q2) use($balitaIds, $remajaIds, $lansiaIds, $bumilIds) {
                      $q2->where(fn($q3) => $q3->whereIn('pasien_type', ['App\\Models\\Balita'])->whereIn('pasien_id', $balitaIds))
                         ->orWhere(fn($q3) => $q3->where('pasien_type', 'App\\Models\\Remaja')->whereIn('pasien_id', $remajaIds))
                         ->orWhere(fn($q3) => $q3->where('pasien_type', 'App\\Models\\Lansia')->whereIn('pasien_id', $lansiaIds))
                         ->orWhere(fn($q3) => $q3->where('pasien_type', 'App\\Models\\IbuHamil')->whereIn('pasien_id', $bumilIds));
                  });
            });
        }

        $imunisasis = $query->paginate(15)->withQueryString();

        // Deteksi Fetch AJAX
        if ($request->ajax()) {
            return view('kader.imunisasi.index', compact('imunisasis', 'kategori', 'search'))->render();
        }
            
        return view('kader.imunisasi.index', compact('imunisasis', 'kategori', 'search'));
    }

    /**
     * Menampilkan Detail Imunisasi
     */
    public function show($id)
    {
        $imunisasi = Imunisasi::with(['kunjungan.petugas'])->findOrFail($id);
        
        // Cari data profil pasien secara manual berdasarkan polimorfik
        $imunisasi->profil_pasien = match($imunisasi->kunjungan->pasien_type) {
            'App\\Models\\Remaja'    => Remaja::find($imunisasi->kunjungan->pasien_id),
            'App\\Models\\Lansia'    => Lansia::find($imunisasi->kunjungan->pasien_id),
            'App\\Models\\IbuHamil'  => IbuHamil::find($imunisasi->kunjungan->pasien_id),
            default                  => Balita::find($imunisasi->kunjungan->pasien_id),
        };
            
        return view('kader.imunisasi.show', compact('imunisasi'));
    }

    // =====================================================================
    // BLOKIR AKSES CRUD UNTUK KADER (SECURITY LEVEL)
    // =====================================================================

    public function create($kunjungan_id = null)
    {
        return back()->with('error', 'Akses ditolak! Input data Vaksin hanya dapat dilakukan oleh Tenaga Bidan.');
    }

    public function store(Request $request, $kunjungan_id = null)
    {
        abort(403, 'Akses Ditolak. Kewenangan Bidan.');
    }

    public function edit($id)
    {
        return back()->with('error', 'Akses ditolak! Hanya Bidan yang dapat mengubah rekam medis.');
    }

    public function update(Request $request, $id)
    {
        abort(403, 'Akses Ditolak. Kewenangan Bidan.');
    }

    public function destroy($id)
    {
        abort(403, 'Akses Ditolak. Kewenangan Bidan.');
    }
}