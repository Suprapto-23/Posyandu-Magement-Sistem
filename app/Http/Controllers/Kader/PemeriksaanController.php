<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

// Memanggil Semua Model yang Terlibat
use App\Models\Pemeriksaan;
use App\Models\Kunjungan;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;

/**
 * =========================================================================
 * PEMERIKSAAN CONTROLLER (KADER WORKSPACE)
 * =========================================================================
 * Modul Inti Operasional Posyandu.
 * Menangani input pemeriksaan fisik, pencatatan kunjungan otomatis, 
 * dan integrasi riwayat medis 4 entitas pasien berbeda.
 */
class PemeriksaanController extends Controller
{
    /**
     * 1. INDEX: Direktori Hasil Pemeriksaan dengan Filter Canggih
     * Menggunakan Polymorphic Eager Loading untuk performa maksimal.
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'semua');
        $search   = $request->get('search', '');
        $status   = $request->get('status', '');

        // Eager Load 'pasien' (Polymorphic) & 'petugas' untuk mencegah N+1 Query Problem
        $query = Pemeriksaan::with(['kunjungan.pasien', 'kunjungan.petugas'])
                  ->latest('tanggal_periksa');

        // Filter Kategori Pasien
        if ($kategori !== 'semua') {
            $query->where('kategori_pasien', $kategori);
        }
        
        // Filter Status Verifikasi (Menunggu Bidan / Selesai)
        if ($status) {
            $query->where('status_verifikasi', $status);
        }

        // Pencarian Cerdas Lintas Tabel (Polymorphic Search)
        if ($search) {
            $query->whereHas('kunjungan', function($q) use ($search) {
                $q->whereHasMorph('pasien', [Balita::class, Remaja::class, Lansia::class, IbuHamil::class], function ($morphQ) use ($search) {
                    $morphQ->where('nama_lengkap', 'like', "%{$search}%")
                           ->orWhere('nik', 'like', "%{$search}%");
                });
            });
        }

        $pemeriksaans = $query->paginate(15)->withQueryString();

        // Render SPA untuk request AJAX (Transisi Tanpa Reload)
        if ($request->ajax() || $request->wantsJson()) {
            return view('kader.pemeriksaan.index', compact('pemeriksaans', 'kategori', 'search', 'status'))->render();
        }

        return view('kader.pemeriksaan.index', compact('pemeriksaans', 'kategori', 'search', 'status'));
    }

    /**
     * 2. CREATE: Form Input Medis (Universal)
     */
    public function create(Request $request)
    {
        // Jika Kader datang dari tombol "Periksa" di halaman profil pasien tertentu
        $kategori_awal = $request->get('kategori', 'balita');
        $pasien_id_awal = $request->get('pasien_id', null);

        return view('kader.pemeriksaan.create', compact('kategori_awal', 'pasien_id_awal'));
    }

    /**
     * 3. STORE: Logika Kompleks Penyimpanan Data (The Core Engine)
     * Menggunakan Database Transaction untuk mencegah data corrupt.
     */
    public function store(Request $request)
{
    // 1. Validasi Data
    $request->validate([
        'pasien_id'       => 'required',
        'kategori_pasien' => 'required',
        'tanggal_periksa' => 'required|date',
        'berat_badan'     => 'nullable|numeric',
        'tinggi_badan'    => 'nullable|numeric',
        'suhu_tubuh'      => 'nullable|numeric',
        // tambahkan field lainnya...
    ]);

    try {
        // 2. Buat record Kunjungan terlebih dahulu (Meja 1)
        // Jika sistem Anda otomatis membuat kunjungan, pastikan ID-nya didapat.
        $kunjungan = \App\Models\Kunjungan::create([
            'pasien_id'         => $request->pasien_id,
            'pasien_type'       => $this->mapPasienType($request->kategori_pasien),
            'tanggal_kunjungan' => $request->tanggal_periksa,
            'keluhan'           => $request->keluhan,
            'pemeriksa_id'      => auth()->id(),
        ]);

        // 3. Simpan ke tabel Pemeriksaans (INILAH PERBAIKANNYA)
        \App\Models\Pemeriksaan::create([
            'kunjungan_id'    => $kunjungan->id,
            'pasien_id'       => $request->pasien_id, // WAJIB ADA AGAR TIDAK ERROR 1364
            'kategori_pasien' => $request->kategori_pasien,
            'tanggal_periksa' => $request->tanggal_periksa,
            'berat_badan'     => $request->berat_badan,
            'tinggi_badan'    => $request->tinggi_badan,
            'suhu_tubuh'      => $request->suhu_tubuh,
            'imt'             => $request->imt,
            'lingkar_kepala'  => $request->lingkar_kepala,
            'lingkar_lengan'  => $request->lingkar_lengan,
            'tekanan_darah'   => $request->tekanan_darah,
            'gula_darah'      => $request->gula_darah,
            'kolesterol'      => $request->kolesterol,
            'asam_urat'       => $request->asam_urat,
            'hemoglobin'      => $request->hemoglobin,
            'lingkar_perut'   => $request->lingkar_perut,
            'usia_kehamilan'  => $request->usia_kehamilan,
            'keluhan'         => $request->keluhan,
            'catatan_kader'   => $request->catatan_kader,
            'status_verifikasi' => 'pending',
            'created_by'      => auth()->id(),
        ]);

        return redirect()->route('kader.pemeriksaan.index')
                         ->with('success', 'Data pemeriksaan berhasil diajukan ke Bidan.');

    } catch (\Exception $e) {
        return back()->with('error', 'Sistem Gagal: ' . $e->getMessage())->withInput();
    }
}

/** Helper untuk memetakan kategori ke Model */
private function mapPasienType($kategori) {
    return match($kategori) {
        'balita'    => 'App\Models\Balita',
        'ibu_hamil' => 'App\Models\IbuHamil',
        'remaja'    => 'App\Models\Remaja',
        'lansia'    => 'App\Models\Lansia',
        default     => 'App\Models\User',
    };
}

    /**
     * 4. SHOW: Detail Lengkap Rekam Medis (Read-Only)
     */
    public function show($id)
    {
        $pemeriksaan = Pemeriksaan::with(['kunjungan.pasien', 'kunjungan.petugas'])->findOrFail($id);
        return view('kader.pemeriksaan.show', compact('pemeriksaan'));
    }

    /**
     * 5. EDIT: Form Koreksi Data Pemeriksaan
     */
    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::with('kunjungan.pasien')->findOrFail($id);
        
        // Proteksi: Kader tidak boleh mengedit jika sudah diverifikasi Bidan
        if ($pemeriksaan->status_verifikasi === 'selesai' || $pemeriksaan->status_verifikasi === 'diverifikasi') {
            return back()->with('error', 'Akses Ditolak! Data ini telah diverifikasi oleh Bidan dan dikunci permanen.');
        }

        return view('kader.pemeriksaan.edit', compact('pemeriksaan'));
    }

    /**
     * 6. UPDATE: Pembaruan Data Pemeriksaan
     */
    public function update(Request $request, $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        // Proteksi Lapis Kedua
        if ($pemeriksaan->status_verifikasi === 'selesai' || $pemeriksaan->status_verifikasi === 'diverifikasi') {
            return back()->with('error', 'Akses Ditolak! Data telah dikunci.');
        }

        $rules = [
            'tanggal_periksa' => 'required|date|before_or_equal:today',
            'berat_badan'     => 'nullable|numeric|min:0|max:300',
            'tinggi_badan'    => 'nullable|numeric|min:0|max:250',
            'suhu_tubuh'      => 'nullable|numeric|min:30|max:45',
            'keluhan'         => 'nullable|string',
            'catatan_kader'   => 'nullable|string',
        ];

        // Validasi Spesifik Kategori menyesuaikan data awal
        if ($pemeriksaan->kategori_pasien === 'balita') {
            $rules['lingkar_kepala'] = 'nullable|numeric|min:0|max:100';
        } elseif ($pemeriksaan->kategori_pasien === 'ibu_hamil') {
            $rules['tensi_darah']    = 'nullable|string|max:10';
            $rules['lingkar_lengan'] = 'nullable|numeric|min:0|max:100';
            $rules['usia_kehamilan'] = 'nullable|integer|min:0|max:50';
        } elseif ($pemeriksaan->kategori_pasien === 'lansia') {
            $rules['tensi_darah'] = 'nullable|string|max:10';
            $rules['gula_darah']  = 'nullable|numeric|min:0|max:1000';
            $rules['kolesterol']  = 'nullable|numeric|min:0|max:1000';
            $rules['asam_urat']   = 'nullable|numeric|min:0|max:50';
        } elseif ($pemeriksaan->kategori_pasien === 'remaja') {
            $rules['tensi_darah'] = 'nullable|string|max:10';
            $rules['gula_darah']  = 'nullable|numeric|min:0|max:1000';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // Update Data Pemeriksaan
            $pemeriksaan->update([
                'tanggal_periksa'   => $request->tanggal_periksa,
                'berat_badan'       => $request->berat_badan,
                'tinggi_badan'      => $request->tinggi_badan,
                'suhu_tubuh'        => $request->suhu_tubuh,
                'lingkar_kepala'    => $request->lingkar_kepala ?? $pemeriksaan->lingkar_kepala,
                'lingkar_lengan'    => $request->lingkar_lengan ?? $pemeriksaan->lingkar_lengan,
                'tensi_darah'       => $request->tensi_darah ?? $pemeriksaan->tensi_darah,
                'gula_darah'        => $request->gula_darah ?? $pemeriksaan->gula_darah,
                'kolesterol'        => $request->kolesterol ?? $pemeriksaan->kolesterol,
                'asam_urat'         => $request->asam_urat ?? $pemeriksaan->asam_urat,
                'usia_kehamilan'    => $request->usia_kehamilan ?? $pemeriksaan->usia_kehamilan,
                'keluhan'           => $request->keluhan,
                'catatan_kader'     => $request->catatan_kader,
            ]);

            // Sync Tanggal Kunjungan jika Tanggal Periksa diubah
            if ($pemeriksaan->kunjungan) {
                $pemeriksaan->kunjungan->update([
                    'tanggal_kunjungan' => $request->tanggal_periksa
                ]);
            }

            DB::commit();
            return redirect()->route('kader.pemeriksaan.show', $pemeriksaan->id)
                ->with('success', 'Koreksi Berhasil! Data pemeriksaan telah diperbarui.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PEMERIKSAAN_UPDATE_ERROR: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Sistem Gagal memperbarui data.');
        }
    }

    /**
     * 7. DESTROY: Hapus Data Pemeriksaan (Beserta Kunjungannya)
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pemeriksaan = Pemeriksaan::findOrFail($id);

            // Proteksi Lapis Kedua
            if ($pemeriksaan->status_verifikasi === 'selesai' || $pemeriksaan->status_verifikasi === 'diverifikasi') {
                return back()->with('error', 'Akses Ditolak! Tidak dapat menghapus data yang telah diverifikasi Bidan.');
            }

            $kunjungan_id = $pemeriksaan->kunjungan_id;
            
            // Hapus Pemeriksaan
            $pemeriksaan->delete();

            // Opsional: Hapus rekor Kunjungan jika tidak ada tindakan lain (misal Imunisasi) di kunjungan tersebut
            if ($kunjungan_id) {
                $kunjungan = Kunjungan::find($kunjungan_id);
                // Jika tidak ada pemeriksaan lain dan tidak ada imunisasi di kunjungan ini, hapus kunjungannya
                if ($kunjungan && $kunjungan->pemeriksaan()->count() === 0 && $kunjungan->imunisasis()->count() === 0) {
                    $kunjungan->delete();
                }
            }

            DB::commit();
            return redirect()->route('kader.pemeriksaan.index')->with('success', 'Log pemeriksaan dan kunjungan berhasil dihapus secara permanen.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PEMERIKSAAN_DELETE_ERROR: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat menghapus data.');
        }
    }

    /**
     * =================================================================
     * PRIVATE HELPERS & API (Logic Engine)
     * =================================================================
     */

    /**
     * Helper: Menentukan Namespace Model berdasarkan kategori string
     */
    private function getModelNamespace(string $kategori): string
    {
        return match($kategori) {
            'balita'    => 'App\Models\Balita',
            'ibu_hamil' => 'App\Models\IbuHamil',
            'remaja'    => 'App\Models\Remaja',
            'lansia'    => 'App\Models\Lansia',
            default     => 'App\Models\Balita',
        };
    }

    /**
     * Helper: Generator Kode Kunjungan Unik
     */
    private function generateKodeKunjungan(): string
    {
        $prefix = 'KNJ-' . date('Ymd') . '-';
        $last = Kunjungan::where('kode_kunjungan', 'like', "$prefix%")->orderByDesc('id')->value('kode_kunjungan');
        $seq = $last ? (intval(substr($last, -4)) + 1) : 1;
        
        return $prefix . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * API Endpoint: Mengambil daftar Pasien untuk Dropdown di UI Create
     * (Optimasi: Select kolom spesifik untuk payload JSON yang sangat ringan)
     */
    public function getPasienApi(Request $request)
    {
        $kategori = $request->get('kategori');
        $data = [];

        try {
            if ($kategori === 'balita') {
                $data = Balita::select('id', 'nama_lengkap as nama', 'nik')->orderBy('nama_lengkap')->get();
            } elseif ($kategori === 'ibu_hamil') {
                $data = IbuHamil::where('status', 'aktif')->select('id', 'nama_lengkap as nama', 'nik')->orderBy('nama_lengkap')->get();
            } elseif ($kategori === 'remaja') {
                $data = Remaja::select('id', 'nama_lengkap as nama', 'nik')->orderBy('nama_lengkap')->get();
            } elseif ($kategori === 'lansia') {
                $data = Lansia::select('id', 'nama_lengkap as nama', 'nik')->orderBy('nama_lengkap')->get();
            }

            return response()->json([
                'status'  => 'success',
                'data'    => $data,
                'message' => 'Data berhasil dimuat.'
            ]);
        } catch (\Throwable $e) {
            Log::error('API_PASIEN_FETCH_ERROR: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'data'    => [],
                'message' => 'Terjadi kesalahan sistem saat mengambil data pasien.'
            ], 500);
        }
    }
}