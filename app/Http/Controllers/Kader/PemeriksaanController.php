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

        if ($request->ajax()) {
            return view('kader.pemeriksaan.index', compact('pemeriksaans', 'kategori', 'search', 'status'))->render();
        }

        return view('kader.pemeriksaan.index', compact('pemeriksaans', 'kategori', 'search', 'status'));
    }

    public function create(Request $request)
    {
        $balitas = Balita::select('id', 'nama_lengkap', 'nik', 'kode_balita', 'tanggal_lahir')->get()
            ->map(function($item) {
                $umurBulan = Carbon::parse($item->tanggal_lahir)->diffInMonths(now());
                return ['id' => $item->id, 'nama' => $item->nama_lengkap, 'nik' => $item->nik ?? $item->kode_balita, 'kategori' => $umurBulan < 12 ? 'bayi' : 'balita'];
            });

        $remajas = Remaja::select('id', 'nama_lengkap', 'nik')->get()
            ->map(fn($item) => ['id' => $item->id, 'nama' => $item->nama_lengkap, 'nik' => $item->nik, 'kategori' => 'remaja']);

        $lansias = Lansia::select('id', 'nama_lengkap', 'nik')->get()
            ->map(fn($item) => ['id' => $item->id, 'nama' => $item->nama_lengkap, 'nik' => $item->nik, 'kategori' => 'lansia']);
            
        $bumils = IbuHamil::select('id', 'nama_lengkap', 'nik')->get()
            ->map(fn($item) => ['id' => $item->id, 'nama' => $item->nama_lengkap, 'nik' => $item->nik, 'kategori' => 'ibu_hamil']);

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
            'tekanan_darah'   => 'nullable|string|max:20',
            'lingkar_lengan'  => 'nullable|numeric',
        ]);

        try {
            DB::beginTransaction();
            
            $pasienType = match($request->kategori_pasien){
                'remaja'    => 'App\\Models\\Remaja',
                'lansia'    => 'App\\Models\\Lansia',
                'ibu_hamil' => 'App\\Models\\IbuHamil',
                default     => 'App\\Models\\Balita',
            };

            $imt = ($request->berat_badan && $request->tinggi_badan) 
                ? round($request->berat_badan / pow($request->tinggi_badan / 100, 2), 2) 
                : null;

            // Deteksi KEK Ibu Hamil Otomatis
            $is_kek = 0;
            if ($request->kategori_pasien == 'ibu_hamil' && $request->lingkar_lengan && $request->lingkar_lengan < 23.5) {
                $is_kek = 1;
            }

            $pertemuanSblm = Kunjungan::where('pasien_id', $request->pasien_id)
                ->where('pasien_type', $pasienType)
                ->whereYear('tanggal_kunjungan', date('Y', strtotime($request->tanggal_periksa)))
                ->count();

            $kunjungan = Kunjungan::create([
                'kode_kunjungan'    => $this->generateKode(),
                'pasien_id'         => $request->pasien_id,
                'pasien_type'       => $pasienType,
                'tanggal_kunjungan' => $request->tanggal_periksa,
                'jenis_kunjungan'   => 'pemeriksaan',
                'pertemuan_ke'      => $pertemuanSblm + 1,
                'keluhan'           => $request->keluhan,
                'petugas_id'        => auth()->id(),
            ]);

            $pemeriksaan = Pemeriksaan::create([
                'kunjungan_id'      => $kunjungan->id,
                'pasien_id'         => $request->pasien_id,
                'kategori_pasien'   => $request->kategori_pasien,
                'pemeriksa_id'      => auth()->id(),
                'user_id'           => auth()->id(),
                'tanggal_periksa'   => $request->tanggal_periksa,
                'berat_badan'       => $request->berat_badan,
                'tinggi_badan'      => $request->tinggi_badan,
                'imt'               => $imt,
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
                'is_kek'            => $is_kek,
                'status_verifikasi' => 'pending',
            ]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Data fisik berhasil dikirim ke Bidan!', 'redirect' => route('kader.pemeriksaan.index')]);
            }
            return redirect()->route('kader.pemeriksaan.index')->with('success','Pemeriksaan berhasil disimpan.');

        } catch(\Exception $e){
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Gagal: ' . $e->getMessage()], 500);
            }
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
        ]);

        try {
            DB::beginTransaction();

            $imt = ($request->berat_badan && $request->tinggi_badan) 
                ? round($request->berat_badan / pow($request->tinggi_badan / 100, 2), 2) 
                : null;

            $is_kek = $pemeriksaan->is_kek;
            if ($pemeriksaan->kategori_pasien == 'ibu_hamil' && $request->lingkar_lengan) {
                $is_kek = $request->lingkar_lengan < 23.5 ? 1 : 0;
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
                'is_kek'          => $is_kek,
            ]);

            if ($pemeriksaan->kunjungan_id) {
                Kunjungan::find($pemeriksaan->kunjungan_id)?->update([
                    'tanggal_kunjungan' => $request->tanggal_periksa,
                    'keluhan' => $request->keluhan
                ]);
            }
            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Koreksi data berhasil disimpan!', 'redirect' => route('kader.pemeriksaan.index')]);
            }
            return redirect()->route('kader.pemeriksaan.index')->with('success','Data diperbarui.');
                
        } catch(\Exception $e){
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Gagal: ' . $e->getMessage()], 500);
            }
            return back()->withInput()->with('error','Gagal: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        try {
            DB::transaction(function() use($pemeriksaan){
                if ($pemeriksaan->kunjungan_id) Kunjungan::find($pemeriksaan->kunjungan_id)?->delete();
                $pemeriksaan->delete();
            });
            return redirect()->route('kader.pemeriksaan.index')->with('success','Data dihapus permanen.');
        } catch(\Throwable $e){
            return back()->with('error','Gagal menghapus: '.$e->getMessage());
        }
    }

    private function getNamaPasien($pasienId, $kategori): string
    {
        try {
            return match($kategori){
                'remaja'    => Remaja::find($pasienId)?->nama_lengkap ?? '-',
                'lansia'    => Lansia::find($pasienId)?->nama_lengkap ?? '-',
                'ibu_hamil' => IbuHamil::find($pasienId)?->nama_lengkap ?? '-',
                default     => Balita::find($pasienId)?->nama_lengkap ?? '-',
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
        $last   = Kunjungan::where('kode_kunjungan','like',"$prefix%")->orderByDesc('id')->value('kode_kunjungan');
        $seq    = $last ? (intval(substr($last,-4))+1) : 1;
        return $prefix.str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}