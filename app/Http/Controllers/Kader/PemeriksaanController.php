<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Memanggil Semua Model Entitas
use App\Models\Pemeriksaan;
use App\Models\Kunjungan;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;

/**
 * =========================================================================
 * PEMERIKSAAN CONTROLLER (ULTIMATE EDITION)
 * =========================================================================
 * Mengelola jantung rekam medis posyandu.
 * Dilengkapi dengan Dynamic Validation, Polymorphic Query, dan Database Transaction.
 */
class PemeriksaanController extends Controller
{
    /**
     * =========================================================================
     * 1. INDEX: TAMPILAN DASHBOARD E-REKAM MEDIS
     * =========================================================================
     */
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', '');
        $search   = $request->get('search', '');
        $status   = $request->get('status', '');

        // Eager Load Relasi untuk mencegah N+1 Query lambat (Performance Boost)
        $query = Pemeriksaan::with(['kunjungan.pasien', 'kunjungan.petugas'])
                  ->latest('tanggal_periksa');

        // 1. Filter Kategori (Menggunakan Scope dari Model)
        if (!empty($kategori)) {
            $query->kategori($kategori);
        }
        
        // 2. Filter Status Validasi
        if (!empty($status)) {
            if ($status === 'pending') {
                $query->pending(); // Menggunakan Scope Failsafe dari Model
            } else {
                $query->where('status_verifikasi', $status);
            }
        }

        // 3. Pencarian Lintas Tabel (Polymorphic Search)
        if (!empty($search)) {
            $query->whereHas('kunjungan', function($q) use ($search) {
                // Mencari nama/NIK ke 4 tabel master warga sekaligus secara efisien
                $q->whereHasMorph('pasien', [Balita::class, Remaja::class, Lansia::class, IbuHamil::class], function ($morphQ) use ($search) {
                    $morphQ->where('nama_lengkap', 'like', "%{$search}%")
                           ->orWhere('nik', 'like', "%{$search}%");
                });
            });
        }

        // Paginasi & Simpan parameter filter di URL agar tidak hilang saat pindah halaman
        $pemeriksaans = $query->paginate(15)->withQueryString();

        return view('kader.pemeriksaan.index', compact('pemeriksaans', 'kategori', 'search', 'status'));
    }

    /**
     * =========================================================================
     * 2. CREATE: FORM INPUT FISIK DASAR
     * =========================================================================
     */
    public function create(Request $request)
    {
        $kategori_awal = $request->get('kategori', 'balita');
        $pasien_id_awal = $request->get('pasien_id', null);

        return view('kader.pemeriksaan.create', compact('kategori_awal', 'pasien_id_awal'));
    }

    /**
     * =========================================================================
     * 3. STORE: PENYIMPANAN LOGIKA KOMPLEKS (DYNAMIC VALIDATION)
     * =========================================================================
     */
    public function store(Request $request)
    {
        // Validasi Universal (Berlaku untuk semua warga)
        $rules = [
            'pasien_id'       => 'required',
            'kategori_pasien' => 'required|in:balita,ibu_hamil,remaja,lansia',
            'tanggal_periksa' => 'required|date|before_or_equal:today',
            'berat_badan'     => 'nullable|numeric|min:0.1|max:300',
            'tinggi_badan'    => 'nullable|numeric|min:10|max:250',
            'keluhan'         => 'nullable|string|max:1000',
            'catatan_kader'   => 'nullable|string|max:1000',
        ];

        // Validasi Spesifik (Dynamic Validation menyesuaikan Kategori)
        $kategori = $request->kategori_pasien;
        if ($kategori === 'balita') {
            $rules['lingkar_kepala'] = 'nullable|numeric|min:10|max:100';
            $rules['suhu_tubuh']     = 'nullable|numeric|min:30|max:45';
        } elseif ($kategori === 'ibu_hamil') {
            $rules['lingkar_lengan'] = 'nullable|numeric|min:10|max:100';
            $rules['tekanan_darah']  = 'nullable|string|max:15';
            $rules['usia_kehamilan'] = 'nullable|integer|min:1|max:45';
        } elseif ($kategori === 'lansia') {
            $rules['tekanan_darah']  = 'nullable|string|max:15';
            $rules['lingkar_perut']  = 'nullable|numeric|min:20|max:200';
            $rules['gula_darah']     = 'nullable|numeric|min:10|max:1000';
            $rules['kolesterol']     = 'nullable|integer|min:10|max:1000';
            $rules['asam_urat']      = 'nullable|numeric|min:1|max:30';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // 1. Cetak Tiket Kunjungan (Log Kehadiran Meja 1)
            $kunjungan = Kunjungan::create([
                'pasien_id'         => $request->pasien_id,
                'pasien_type'       => $this->mapPasienType($kategori),
                'tanggal_kunjungan' => $request->tanggal_periksa,
                'keluhan'           => $request->keluhan,
                'pemeriksa_id'      => Auth::id(),
            ]);

            // 2. Eksekusi Simpan Pengukuran Fisik (Meja 2/3)
            Pemeriksaan::create([
                'kunjungan_id'    => $kunjungan->id,
                'pasien_id'       => $request->pasien_id, // Redundansi pengaman jika tabel Kunjungan corrupt
                'kategori_pasien' => $kategori,
                'tanggal_periksa' => $request->tanggal_periksa,
                'berat_badan'     => $request->berat_badan,
                'tinggi_badan'    => $request->tinggi_badan,
                'suhu_tubuh'      => $request->suhu_tubuh,
                'lingkar_kepala'  => $request->lingkar_kepala,
                'lingkar_lengan'  => $request->lingkar_lengan,
                'lingkar_perut'   => $request->lingkar_perut,
                'tekanan_darah'   => $request->tekanan_darah,
                'gula_darah'      => $request->gula_darah,
                'kolesterol'      => $request->kolesterol,
                'asam_urat'       => $request->asam_urat,
                'hemoglobin'      => $request->hemoglobin,
                'usia_kehamilan'  => $request->usia_kehamilan,
                'keluhan'         => $request->keluhan,
                'catatan_kader'   => $request->catatan_kader,
                'status_verifikasi' => 'pending', // Menunggu acc Bidan
                'created_by'      => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('kader.pemeriksaan.index')
                             ->with('success', 'Rekam Medis Berhasil Disimpan & Menunggu Validasi Bidan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PEMERIKSAAN_STORE_CRITICAL_ERROR: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Kegagalan Server: ' . $e->getMessage());
        }
    }

    /**
     * =========================================================================
     * 4. SHOW: BACA DETAIL BUKU REKAM MEDIS
     * =========================================================================
     */
    public function show($id)
    {
        $pemeriksaan = Pemeriksaan::with(['kunjungan.pasien', 'kunjungan.petugas', 'verifikator'])->findOrFail($id);
        return view('kader.pemeriksaan.show', compact('pemeriksaan'));
    }

    /**
     * =========================================================================
     * 5. EDIT: FORM KOREKSI DATA
     * =========================================================================
     */
    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::with('kunjungan.pasien')->findOrFail($id);
        
        // PROTEKSI MUTLAK: Kader tidak boleh menyentuh data yang sudah disahkan Bidan
        if (in_array($pemeriksaan->status_verifikasi, ['tervalidasi', 'verified', 'approved'])) {
            return back()->with('error', 'Akses Terkunci! Anda tidak berhak mengubah data medis yang sudah divalidasi oleh Bidan.');
        }

        return view('kader.pemeriksaan.edit', compact('pemeriksaan'));
    }

    /**
     * =========================================================================
     * 6. UPDATE: SIMPAN KOREKSI DATA
     * =========================================================================
     */
    public function update(Request $request, $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        // Proteksi Lapis Kedua (Mencegah Bypass lewat Postman/API)
        if (in_array($pemeriksaan->status_verifikasi, ['tervalidasi', 'verified', 'approved'])) {
            return back()->with('error', 'Bypass Terdeteksi: Akses ditolak oleh sistem keamanan.');
        }

        // Validasi Universal
        $rules = [
            'tanggal_periksa' => 'required|date|before_or_equal:today',
            'berat_badan'     => 'nullable|numeric|min:0.1|max:300',
            'tinggi_badan'    => 'nullable|numeric|min:10|max:250',
            'keluhan'         => 'nullable|string|max:1000',
            'catatan_kader'   => 'nullable|string|max:1000',
        ];

        // Validasi Spesifik (Dynamic)
        $kategori = $pemeriksaan->kategori_pasien;
        if ($kategori === 'balita') {
            $rules['lingkar_kepala'] = 'nullable|numeric|min:10|max:100';
            $rules['suhu_tubuh']     = 'nullable|numeric|min:30|max:45';
        } elseif ($kategori === 'ibu_hamil') {
            $rules['lingkar_lengan'] = 'nullable|numeric|min:10|max:100';
            $rules['tekanan_darah']  = 'nullable|string|max:15';
            $rules['usia_kehamilan'] = 'nullable|integer|min:1|max:45';
        } elseif ($kategori === 'lansia') {
            $rules['tekanan_darah']  = 'nullable|string|max:15';
            $rules['lingkar_perut']  = 'nullable|numeric|min:20|max:200';
            $rules['gula_darah']     = 'nullable|numeric|min:10|max:1000';
            $rules['kolesterol']     = 'nullable|integer|min:10|max:1000';
            $rules['asam_urat']      = 'nullable|numeric|min:1|max:30';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // Update Data Medis
            $pemeriksaan->update([
                'tanggal_periksa'   => $request->tanggal_periksa,
                'berat_badan'       => $request->berat_badan,
                'tinggi_badan'      => $request->tinggi_badan,
                'suhu_tubuh'        => $request->suhu_tubuh,
                'lingkar_kepala'    => $request->lingkar_kepala ?? $pemeriksaan->lingkar_kepala,
                'lingkar_lengan'    => $request->lingkar_lengan ?? $pemeriksaan->lingkar_lengan,
                'lingkar_perut'     => $request->lingkar_perut ?? $pemeriksaan->lingkar_perut,
                'tekanan_darah'     => $request->tekanan_darah ?? $pemeriksaan->tekanan_darah,
                'gula_darah'        => $request->gula_darah ?? $pemeriksaan->gula_darah,
                'kolesterol'        => $request->kolesterol ?? $pemeriksaan->kolesterol,
                'asam_urat'         => $request->asam_urat ?? $pemeriksaan->asam_urat,
                'usia_kehamilan'    => $request->usia_kehamilan ?? $pemeriksaan->usia_kehamilan,
                'keluhan'           => $request->keluhan,
                'catatan_kader'     => $request->catatan_kader,
                // Jika statusnya Ditolak/Revisi, kita kembalikan ke Pending agar Bidan mengecek ulang
                'status_verifikasi' => $pemeriksaan->status_verifikasi === 'ditolak' ? 'pending' : $pemeriksaan->status_verifikasi,
            ]);

            // Sync Tanggal Kunjungan (Jika tanggal periksa diubah, tanggal tiket kunjungan ikut berubah)
            if ($pemeriksaan->kunjungan) {
                $pemeriksaan->kunjungan->update([
                    'tanggal_kunjungan' => $request->tanggal_periksa,
                    'keluhan'           => $request->keluhan,
                ]);
            }

            DB::commit();
            return redirect()->route('kader.pemeriksaan.index')
                ->with('success', 'Koreksi Berhasil! Log pemeriksaan telah diperbarui.');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PEMERIKSAAN_UPDATE_CRITICAL_ERROR: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Sistem Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * =========================================================================
     * 7. DESTROY: HAPUS PEMERIKSAAN (ORPHAN PREVENTION ENGINE)
     * =========================================================================
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pemeriksaan = Pemeriksaan::findOrFail($id);

            // PROTEKSI MUTLAK
            if (in_array($pemeriksaan->status_verifikasi, ['tervalidasi', 'verified', 'approved'])) {
                return back()->with('error', 'Akses Ditolak! Anda tidak diizinkan menghapus data yang telah menjadi Rekam Medis Sah Bidan.');
            }

            $kunjungan_id = $pemeriksaan->kunjungan_id;
            
            // Hapus log Pemeriksaan Fisik
            $pemeriksaan->delete();

            // ORPHAN PREVENTION (Pencegahan Tabel Hantu)
            // Cek apakah tiket Kunjungan ini masih punya data lain (misal: log Imunisasi)
            if ($kunjungan_id) {
                $kunjungan = Kunjungan::find($kunjungan_id);
                if ($kunjungan) {
                    // Jika di kunjungan ini tidak ada pemeriksaan lain dan tidak ada imunisasi, buang tiketnya!
                    $hasPemeriksaanLain = $kunjungan->pemeriksaan()->count() > 0;
                    $hasImunisasi       = $kunjungan->imunisasis()->count() > 0;

                    if (!$hasPemeriksaanLain && !$hasImunisasi) {
                        $kunjungan->delete();
                    }
                }
            }

            DB::commit();
            return redirect()->route('kader.pemeriksaan.index')->with('success', 'Log pemeriksaan (dan kunjungan terkait) berhasil dihanguskan dari sistem.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('PEMERIKSAAN_DELETE_ERROR: ' . $e->getMessage());
            return back()->with('error', 'Terjadi konflik internal saat menghapus data.');
        }
    }

    /**
     * =========================================================================
     * PRIVATE HELPERS & API (LOGIC ENGINE)
     * =========================================================================
     */

    /**
     * Mengkonversi string kategori menjadi rujukan namespace Model (Polymorphic Pattern)
     */
    private function mapPasienType(string $kategori): string
    {
        return match($kategori) {
            'balita'    => 'App\Models\Balita',
            'ibu_hamil' => 'App\Models\IbuHamil',
            'remaja'    => 'App\Models\Remaja',
            'lansia'    => 'App\Models\Lansia',
            default     => 'App\Models\User',
        };
    }

    /**
     * API ENDPOINT: Auto-Complete Dropdown Pasien di halaman Create
     * (Sangat Ringan, hanya menarik ID, NIK, dan Nama)
     */
    public function getPasienApi(Request $request)
    {
        $kategori = $request->get('kategori');
        $data = [];

        try {
            if ($kategori === 'balita') {
                $data = Balita::select('id', 'nama_lengkap as nama', 'nik')->orderBy('nama_lengkap')->get();
            } elseif ($kategori === 'ibu_hamil') {
                // Asumsi Ibu Hamil yang sudah melahirkan (status tidak aktif) disembunyikan
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
                'message' => 'Koneksi gagal. Sistem tidak dapat menarik data populasi.'
            ], 500);
        }
    }
}