<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Imunisasi;
use App\Models\Balita;
use App\Models\IbuHamil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImunisasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Imunisasi::with(['kunjungan.pasien', 'kunjungan.petugas'])->latest('tanggal_imunisasi');
        
        if ($search) {
            $query->where('vaksin', 'like', "%{$search}%")
                  ->orWhereHas('kunjungan.pasien', function($q) use ($search) {
                      $q->where('nama_lengkap', 'like', "%{$search}%");
                  });
        }
        
        $imunisasis = $query->paginate(10)->withQueryString();
        return view('bidan.imunisasi.index', compact('imunisasis', 'search'));
    }

    public function create()
    {
        // UPGRADE: Menarik seluruh Buku Induk Balita dan Ibu Hamil 
        // agar Bidan bebas mencari pasien tanpa harus lewat Kader dulu.
        $balitas = Balita::select('id', 'nama_lengkap', 'nik')->orderBy('nama_lengkap')->get();
        $ibuHamils = IbuHamil::select('id', 'nama_lengkap', 'nik')->orderBy('nama_lengkap')->get();
            
        return view('bidan.imunisasi.create', compact('balitas', 'ibuHamils'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pasien_id'         => 'required',
            'pasien_type'       => 'required',
            'jenis_imunisasi'   => 'required|string|max:255',
            'vaksin'            => 'required|string|max:255',
            'dosis'             => 'required|string|max:50',
            'tanggal_imunisasi' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // AUTO-DETECT: Sistem mengecek apakah pasien sudah mendaftar hari ini.
            // Jika belum ada, sistem akan otomatis membuatkan riwayat kunjungan.
            $kunjungan = Kunjungan::firstOrCreate(
                [
                    'pasien_id'         => $request->pasien_id,
                    'pasien_type'       => $request->pasien_type,
                    'tanggal_kunjungan' => $request->tanggal_imunisasi,
                ],
                [
                    'petugas_id'      => auth()->id(), // ID Bidan
                    'jenis_kunjungan' => 'Imunisasi',
                    'status'          => 'selesai'
                ]
            );

            // Simpan data imunisasi dengan ID Kunjungan yang valid
            Imunisasi::create([
                'kunjungan_id'      => $kunjungan->id,
                'jenis_imunisasi'   => $request->jenis_imunisasi,
                'vaksin'            => $request->vaksin,
                'dosis'             => $request->dosis,
                'tanggal_imunisasi' => $request->tanggal_imunisasi,
                'keterangan'        => $request->keterangan,
            ]);

            DB::commit();
            return redirect()->route('bidan.imunisasi.index')
                ->with('success', 'Data Vaksinasi berhasil dicatat dan disinkronkan ke rekam medis pasien!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $imunisasi = Imunisasi::with(['kunjungan.pasien', 'kunjungan.petugas'])->findOrFail($id);
        return view('bidan.imunisasi.show', compact('imunisasi'));
    }

    // Untuk fungsi Edit/Update/Destroy bisa disesuaikan sama seperti aslinya
    public function destroy($id)
    {
        Imunisasi::findOrFail($id)->delete();
        return redirect()->route('bidan.imunisasi.index')->with('success', 'Riwayat vaksinasi telah dihapus permanen dari sistem.');
    }
}