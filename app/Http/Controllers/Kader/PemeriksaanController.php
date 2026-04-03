<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Pemeriksaan;
use App\Models\Kunjungan;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;

class PemeriksaanController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'semua');
        $search   = $request->get('search', '');
        $status   = $request->get('status', '');

        $query = Pemeriksaan::latest('tanggal_periksa');

        if ($kategori !== 'semua') $query->where('kategori_pasien', $kategori);
        if ($status) $query->where('status_verifikasi', $status);

        if ($search) {
            $balitaIds = Balita::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            $remajaIds = Remaja::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            $lansiaIds = Lansia::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            $bumilIds  = IbuHamil::where('nama_lengkap', 'like', "%$search%")->pluck('id');
            
            $query->where(function($q) use($balitaIds, $remajaIds, $lansiaIds, $bumilIds){
                $q->where(fn($q2)=>$q2->whereIn('kategori_pasien', ['bayi','balita'])->whereIn('pasien_id', $balitaIds))
                  ->orWhere(fn($q2)=>$q2->where('kategori_pasien','remaja')->whereIn('pasien_id', $remajaIds))
                  ->orWhere(fn($q2)=>$q2->where('kategori_pasien','lansia')->whereIn('pasien_id', $lansiaIds))
                  ->orWhere(fn($q2)=>$q2->where('kategori_pasien','ibu_hamil')->whereIn('pasien_id', $bumilIds));
            });
        }

        $pemeriksaans = $query->paginate(15)->withQueryString();

        foreach ($pemeriksaans as $p) {
            $p->nama_pasien = $this->getNamaPasien($p->pasien_id, $p->kategori_pasien);
        }

        return view('kader.pemeriksaan.index', compact('pemeriksaans', 'kategori', 'search', 'status'));
    }

    public function create(Request $request)
    {
        // 1. Ambil & Filter Otomatis: Bayi (0-11 Bln) vs Balita (12-59 Bln)
        $balitas = Balita::select('id', 'nama_lengkap', 'nik', 'kode_balita', 'tanggal_lahir')->get()
            ->map(function($item) {
                $umurBulan = Carbon::parse($item->tanggal_lahir)->diffInMonths(now());
                $kat = $umurBulan < 12 ? 'bayi' : 'balita';
                return [
                    'id' => $item->id, 
                    'nama' => $item->nama_lengkap, 
                    'nik' => $item->nik ?? $item->kode_balita,
                    'kategori' => $kat
                ];
            });

        // 2. Data Remaja
        $remajas = Remaja::select('id', 'nama_lengkap', 'nik')->get()
            ->map(fn($item) => ['id' => $item->id, 'nama' => $item->nama_lengkap, 'nik' => $item->nik, 'kategori' => 'remaja']);

        // 3. Data Lansia
        $lansias = Lansia::select('id', 'nama_lengkap', 'nik')->get()
            ->map(fn($item) => ['id' => $item->id, 'nama' => $item->nama_lengkap, 'nik' => $item->nik, 'kategori' => 'lansia']);
            
        // 4. Data Ibu Hamil
        $bumils = IbuHamil::select('id', 'nama_lengkap', 'nik')->get()
            ->map(fn($item) => ['id' => $item->id, 'nama' => $item->nama_lengkap, 'nik' => $item->nik, 'kategori' => 'ibu_hamil']);

        // Gabungkan SEMUA data untuk Frontend JS
        $semuaPasien = collect()->concat($balitas)->concat($remajas)->concat($lansias)->concat($bumils)->toArray();

        return view('kader.pemeriksaan.create', compact('semuaPasien'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_pasien' => 'required|in:bayi,balita,remaja,lansia,ibu_hamil',
            'pasien_id'       => 'required|integer',
            'tanggal_periksa' => 'required|date',
            'berat_badan'     => 'required|numeric|min:0.1|max:300',
            'tinggi_badan'    => 'required|numeric|min:1|max:250',
            'kemandirian'     => 'nullable|in:A,B,C', // Khusus Lansia
            'tekanan_darah'   => 'nullable|string|max:20',
            'suhu_tubuh'      => 'nullable|numeric',
            'lingkar_kepala'  => 'nullable|numeric',
            'lingkar_lengan'  => 'nullable|numeric',
            'lingkar_perut'   => 'nullable|numeric',
            'hemoglobin'      => 'nullable|numeric',
            'gula_darah'      => 'nullable|string|max:50',
            'kolesterol'      => 'nullable|numeric',
            'asam_urat'       => 'nullable|numeric',
            'keluhan'         => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function() use($request){
                // Tentukan Model berdasarkan Kategori
                $pasienType = match($request->kategori_pasien){
                    'remaja'    => 'App\\Models\\Remaja',
                    'lansia'    => 'App\\Models\\Lansia',
                    'ibu_hamil' => 'App\\Models\\IbuHamil',
                    default     => 'App\\Models\\Balita', // Bayi & Balita masuk sini
                };

                // PENGHITUNG IMT OTOMATIS (Berat / Tinggi(m)^2)
                $imt = null;
                if ($request->berat_badan && $request->tinggi_badan) {
                    $tinggi_m = $request->tinggi_badan / 100;
                    $imt = round($request->berat_badan / ($tinggi_m * $tinggi_m), 2);
                }

                // SISTEM ABSENSI (Hitung Pertemuan Ke Berapa)
                $pertemuanSblm = Kunjungan::where('pasien_id', $request->pasien_id)
                    ->where('pasien_type', $pasienType)
                    ->whereYear('tanggal_kunjungan', date('Y', strtotime($request->tanggal_periksa)))
                    ->count();
                $pertemuanKe = $pertemuanSblm + 1;

                // 1. Simpan Riwayat Kunjungan / Buku Tamu Absensi
                $kunjungan = Kunjungan::create([
                    'kode_kunjungan'    => $this->generateKode(),
                    'pasien_id'         => $request->pasien_id,
                    'pasien_type'       => $pasienType,
                    'tanggal_kunjungan' => $request->tanggal_periksa,
                    'jenis_kunjungan'   => 'pemeriksaan',
                    'pertemuan_ke'      => $pertemuanKe, // Menyimpan Absensi Pertemuan
                    'keluhan'           => $request->keluhan,
                    'petugas_id'        => auth()->id(),
                ]);

                // 2. Simpan Detail Pemeriksaan
                Pemeriksaan::create([
                    'kunjungan_id'      => $kunjungan->id,
                    'pasien_id'         => $request->pasien_id,
                    'kategori_pasien'   => $request->kategori_pasien,
                    'pemeriksa_id'      => auth()->id(),
                    'user_id'           => auth()->id(),
                    'tanggal_periksa'   => $request->tanggal_periksa,
                    'berat_badan'       => $request->berat_badan,
                    'tinggi_badan'      => $request->tinggi_badan,
                    'imt'               => $imt, // IMT Disimpan
                    'kemandirian'       => $request->kategori_pasien == 'lansia' ? $request->kemandirian : null,
                    'lingkar_kepala'    => $request->lingkar_kepala,
                    'lingkar_lengan'    => $request->lingkar_lengan,
                    'lingkar_perut'     => $request->lingkar_perut,
                    'suhu_tubuh'        => $request->suhu_tubuh,
                    'tekanan_darah'     => $request->tekanan_darah,
                    'hemoglobin'        => $request->hemoglobin,
                    'gula_darah'        => $request->gula_darah,
                    'kolesterol'        => $request->kolesterol,
                    'asam_urat'         => $request->asam_urat,
                    'keluhan'           => $request->keluhan,
                    'status_verifikasi' => 'pending',
                ]);
            });

            return redirect()->route('kader.pemeriksaan.index')
                ->with('success','✅ Pemeriksaan berhasil disimpan. Menunggu verifikasi bidan.');

        } catch(\Throwable $e){
            Log::error('KaderPemeriksaan::store — '.$e->getMessage());
            return back()->withInput()->with('error','Gagal menyimpan: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        $p = Pemeriksaan::findOrFail($id);
        $p->nama_pasien = $this->getNamaPasien($p->pasien_id, $p->kategori_pasien);
        $p->data_pasien = $this->getDataPasien($p->pasien_id, $p->kategori_pasien);
        
        return view('kader.pemeriksaan.show', ['pemeriksaan' => $p]);
    }

    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->nama_pasien = $this->getNamaPasien($pemeriksaan->pasien_id, $pemeriksaan->kategori_pasien);
        return view('kader.pemeriksaan.edit', compact('pemeriksaan'));
    }

    public function update(Request $request, $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $request->validate([
            'tanggal_periksa' => 'required|date',
            'berat_badan'     => 'required|numeric|min:0.1|max:300',
            'tinggi_badan'    => 'required|numeric|min:1|max:250',
            'kemandirian'     => 'nullable|in:A,B,C',
        ]);

        try {
            DB::transaction(function() use ($request, $pemeriksaan) {
                // Hitung Ulang IMT
                $imt = null;
                if ($request->berat_badan && $request->tinggi_badan) {
                    $tinggi_m = $request->tinggi_badan / 100;
                    $imt = round($request->berat_badan / ($tinggi_m * $tinggi_m), 2);
                }

                $pemeriksaan->update([
                    'tanggal_periksa' => $request->tanggal_periksa,
                    'berat_badan'     => $request->berat_badan,
                    'tinggi_badan'    => $request->tinggi_badan,
                    'imt'             => $imt,
                    'kemandirian'     => $pemeriksaan->kategori_pasien == 'lansia' ? $request->kemandirian : null,
                    'lingkar_kepala'  => $request->lingkar_kepala,
                    'lingkar_lengan'  => $request->lingkar_lengan,
                    'lingkar_perut'   => $request->lingkar_perut,
                    'suhu_tubuh'      => $request->suhu_tubuh,
                    'tekanan_darah'   => $request->tekanan_darah,
                    'hemoglobin'      => $request->hemoglobin,
                    'gula_darah'      => $request->gula_darah,
                    'kolesterol'      => $request->kolesterol,
                    'asam_urat'       => $request->asam_urat,
                    'keluhan'         => $request->keluhan,
                ]);

                if ($pemeriksaan->kunjungan_id) {
                    Kunjungan::find($pemeriksaan->kunjungan_id)?->update([
                        'tanggal_kunjungan' => $request->tanggal_periksa,
                        'keluhan' => $request->keluhan
                    ]);
                }
            });

            return redirect()->route('kader.pemeriksaan.index')
                ->with('success','✅ Data pemeriksaan berhasil diperbarui.');
                
        } catch(\Throwable $e){
            return back()->withInput()->with('error','Gagal: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        
        try {
            DB::transaction(function() use($pemeriksaan){
                if ($pemeriksaan->kunjungan_id) {
                    Kunjungan::find($pemeriksaan->kunjungan_id)?->delete();
                }
                $pemeriksaan->delete();
            });
            return redirect()->route('kader.pemeriksaan.index')
                ->with('success','Data pemeriksaan berhasil dihapus permanen.');
                
        } catch(\Throwable $e){
            return back()->with('error','Gagal menghapus: '.$e->getMessage());
        }
    }

    // ================= HELPERS =================
    private function getNamaPasien($pasienId, $kategori): string
    {
        try {
            return match($kategori){
                'remaja'    => Remaja::find($pasienId)?->nama_lengkap ?? '-',
                'lansia'    => Lansia::find($pasienId)?->nama_lengkap ?? '-',
                'ibu_hamil' => IbuHamil::find($pasienId)?->nama_lengkap ?? '-',
                default     => Balita::find($pasienId)?->nama_lengkap ?? '-', // Bayi & Balita
            };
        } catch(\Throwable $e){ return '-'; }
    }

    private function getDataPasien($pasienId, $kategori)
    {
        try {
            return match($kategori){
                'remaja'    => Remaja::find($pasienId),
                'lansia'    => Lansia::find($pasienId),
                'ibu_hamil' => IbuHamil::find($pasienId),
                default     => Balita::find($pasienId),
            };
        } catch(\Throwable $e){ return null; }
    }

    private function generateKode(): string
    {
        $prefix = 'KNJ-'.date('Ymd');
        $last   = Kunjungan::where('kode_kunjungan','like',"$prefix%")
            ->orderByDesc('id')->value('kode_kunjungan');
        $seq    = $last ? (intval(substr($last,-4))+1) : 1;
        return $prefix.str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}