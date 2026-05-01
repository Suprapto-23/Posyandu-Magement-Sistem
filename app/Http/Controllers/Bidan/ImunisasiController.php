<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Memanggil Seluruh Model Kategori Warga
use App\Models\Kunjungan;
use App\Models\Imunisasi;
use App\Models\Balita;
use App\Models\IbuHamil;
use App\Models\Lansia;
use App\Models\Remaja;

class ImunisasiController extends Controller
{
    /**
     * 1. INDEX: Menampilkan Buku Register Vaksinasi
     */
    public function index(Request $request)
    {
        try {
            $search = $request->get('search');
            
            $query = Imunisasi::with(['kunjungan.pasien', 'kunjungan.petugas'])
                              ->latest('tanggal_imunisasi');
            
            // Pencarian Cerdas Polimorfik
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('vaksin', 'like', "%{$search}%")
                      ->orWhereHas('kunjungan', function ($kunjunganQuery) use ($search) {
                          $kunjunganQuery->whereHasMorph('pasien', [Balita::class, IbuHamil::class, Lansia::class, Remaja::class], function ($pasienQuery) use ($search) {
                              $pasienQuery->where('nama_lengkap', 'like', "%{$search}%")
                                          ->orWhere('nik', 'like', "%{$search}%");
                          });
                      });
                });
            }
            
            $imunisasis = $query->paginate(15)->withQueryString();
            
            return view('bidan.imunisasi.index', compact('imunisasis', 'search'));

        } catch (\Exception $e) {
            Log::error('BIDAN_IMUNISASI_INDEX_ERROR: ' . $e->getMessage());
            abort(500, 'Gagal memuat Register Imunisasi.');
        }
    }

    /**
     * 2. CREATE: Form Input Log Imunisasi Baru
     */
    public function create()
    {
        // PERBAIKAN MUTLAK: Menarik seluruh entitas warga agar Stepper Form berfungsi sempurna
        $balitas   = Balita::select('id', 'nama_lengkap', 'nik')->orderBy('nama_lengkap')->get();
        $ibuHamils = IbuHamil::select('id', 'nama_lengkap', 'nik')->orderBy('nama_lengkap')->get();
        $lansias   = Lansia::select('id', 'nama_lengkap', 'nik')->orderBy('nama_lengkap')->get();
        $remajas   = Remaja::select('id', 'nama_lengkap', 'nik')->orderBy('nama_lengkap')->get();
            
        // Mengirimkan keempat variabel tersebut ke View
        return view('bidan.imunisasi.create', compact('balitas', 'ibuHamils', 'lansias', 'remajas'));
    }

    /**
     * 3. STORE: Eksekusi Simpan Data EMR Imunisasi
     */
    public function store(Request $request)
    {
        $request->validate([
            'pasien_id'         => 'required|integer',
            'pasien_type'       => 'required|string',
            'jenis_imunisasi'   => 'required|string|max:255',
            'vaksin'            => 'required|string|max:255',
            'tanggal_imunisasi' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Deteksi Kunjungan Otomatis
            $kunjungan = Kunjungan::firstOrCreate(
                [
                    'pasien_id'         => $request->pasien_id,
                    'pasien_type'       => $request->pasien_type,
                    'tanggal_kunjungan' => $request->tanggal_imunisasi,
                ],
                [
                    'petugas_id'      => Auth::id(),
                    'jenis_kunjungan' => 'Imunisasi',
                    'waktu_kedatangan'=> Carbon::now()->format('H:i:s'),
                    'status'          => 'selesai'
                ]
            );

            Imunisasi::create([
                'kunjungan_id'      => $kunjungan->id,
                'jenis_imunisasi'   => $request->jenis_imunisasi,
                'vaksin'            => $request->vaksin,
                'dosis'             => $request->dosis ?? 'Sesuai Standar', 
                'tanggal_imunisasi' => $request->tanggal_imunisasi,
                'keterangan'        => $request->keterangan ?? '-',
            ]);

            DB::commit();
            return redirect()->route('bidan.imunisasi.index')
                             ->with('success', 'Tindakan Vaksinasi berhasil dicatat permanen ke Buku Register dan EMR Warga.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BIDAN_IMUNISASI_STORE_ERROR: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan rekam medis imunisasi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * 4. SHOW: Sertifikat & Detail Injeksi (Read Only)
     */
    public function show($id)
    {
        try {
            $imunisasi = Imunisasi::with(['kunjungan.pasien', 'kunjungan.petugas'])->findOrFail($id);
            return view('bidan.imunisasi.show', compact('imunisasi'));
        } catch (\Exception $e) {
            return redirect()->route('bidan.imunisasi.index')->with('error', 'Data imunisasi tidak ditemukan.');
        }
    }

    /**
     * 5. DESTROY: Hapus Data Vaksinasi
     */
    public function destroy($id)
    {
        try {
            $imu = Imunisasi::findOrFail($id);
            $imu->delete();
            return redirect()->route('bidan.imunisasi.index')->with('success', 'Riwayat vaksinasi telah dicabut dari sistem EMR.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data imunisasi.');
        }
    }
}