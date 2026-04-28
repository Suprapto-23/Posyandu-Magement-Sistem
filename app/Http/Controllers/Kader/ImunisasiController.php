<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Hanya memanggil entitas yang relevan untuk Imunisasi Posyandu
use App\Models\Imunisasi;
use App\Models\Kunjungan;
use App\Models\Balita;
use App\Models\IbuHamil;

/**
 * =========================================================================
 * IMUNISASI CONTROLLER (NEXUS EDITION - KADER WORKSPACE)
 * =========================================================================
 * FOKUS MEDIS: Imunisasi Dasar Balita & Tetanus Toxoid (TT) Ibu Hamil.
 * Modul ini bersifat Read-Only (Hanya Baca) untuk Kader.
 */
class ImunisasiController extends Controller
{
    /**
     * 1. INDEX: DASHBOARD LOG IMUNISASI
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', '');
        $search   = $request->get('search', '');

        // Eager Load untuk mencegah lambatnya database
        $query = Imunisasi::with(['kunjungan.petugas', 'kunjungan.pasien'])
                          ->latest('tanggal_imunisasi');

        // Filter Berdasarkan Kategori Target Vaksinasi
        if (!empty($kategori) && $kategori !== 'semua') {
            $pasienType = $kategori === 'ibu_hamil' ? 'App\Models\IbuHamil' : 'App\Models\Balita';
            $query->whereHas('kunjungan', function($q) use ($pasienType) {
                $q->where('pasien_type', $pasienType);
            });
        }

        // Pencarian Real-Time Khusus Balita & Ibu Hamil
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('vaksin', 'like', "%{$search}%")
                  ->orWhere('jenis_imunisasi', 'like', "%{$search}%")
                  ->orWhereHas('kunjungan', function($q2) use ($search) {
                      $q2->whereHasMorph('pasien', [Balita::class, IbuHamil::class], function($morphQ) use ($search) {
                          $morphQ->where('nama_lengkap', 'like', "%{$search}%")
                                 ->orWhere('nik', 'like', "%{$search}%");
                      });
                  });
            });
        }

        $imunisasis = $query->paginate(15)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return view('kader.imunisasi.index', compact('imunisasis', 'kategori', 'search'))->render();
        }
            
        return view('kader.imunisasi.index', compact('imunisasis', 'kategori', 'search'));
    }

    /**
     * 2. SHOW: DETAIL SERTIFIKAT IMUNISASI
     */
    public function show($id)
    {
        $imunisasi = Imunisasi::with(['kunjungan.petugas', 'kunjungan.pasien'])->findOrFail($id);
        return view('kader.imunisasi.show', compact('imunisasi'));
    }

    /**
     * =========================================================================
     * SECURITY FIREWALL: BLOKIR AKSES CRUD UNTUK KADER
     * =========================================================================
     */
    public function create($kunjungan_id = null) {
        return back()->with('error', 'Akses Terkunci! Input data Vaksin hanya wewenang Tenaga Bidan (Meja 5).');
    }

    public function store(Request $request, $kunjungan_id = null) {
        abort(403, 'Akses Ditolak.');
    }

    public function edit($id) {
        return back()->with('error', 'Akses Terkunci! Hanya Bidan yang berhak mengoreksi rekam medis Imunisasi.');
    }

    public function update(Request $request, $id) {
        abort(403, 'Akses Ditolak.');
    }

    public function destroy($id) {
        return back()->with('error', 'Akses Terkunci! Penghapusan riwayat vaksinasi dilarang untuk tingkat Kader.');
    }
}