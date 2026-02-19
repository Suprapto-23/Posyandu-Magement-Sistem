<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pemeriksaan;
use App\Models\Kunjungan;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;

class PemeriksaanController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $search = $request->get('search');

        $pemeriksaans = Pemeriksaan::with(['kunjungan.pasien'])
            ->when($type != 'all', function($q) use ($type) {
                return $q->where('kategori_pasien', $type);
            })
            ->when($search, function($q) use ($search) {
                return $q->whereHas('kunjungan', function($k) use ($search) {
                    $k->where('kode_kunjungan', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('kader.pemeriksaan.index', compact('pemeriksaans', 'type', 'search'));
    }

    public function create()
    {
        $balitas = Balita::select('id', 'nama_lengkap', 'nik')->orderBy('nama_lengkap')->get();
        $remajas = Remaja::select('id', 'nama_lengkap', 'nik')->orderBy('nama_lengkap')->get();
        $lansias = Lansia::select('id', 'nama_lengkap', 'nik')->orderBy('nama_lengkap')->get();

        return view('kader.pemeriksaan.create', compact('balitas', 'remajas', 'lansias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pasien_type'       => 'required|in:balita,remaja,lansia',
            'pasien_id'         => 'required|numeric',
            'tanggal_kunjungan' => 'required|date',
            'berat_badan'       => 'required|numeric',
            'tinggi_badan'      => 'required|numeric',
            'hemoglobin'        => 'nullable|numeric',
        ]);

        DB::beginTransaction();
        try {
            $modelClass = match($request->pasien_type) {
                'balita' => Balita::class,
                'remaja' => Remaja::class,
                'lansia' => Lansia::class,
            };

            $kunjungan = Kunjungan::create([
                'kode_kunjungan'    => 'KNJ-' . date('Ymd') . rand(100, 999),
                'pasien_id'         => $request->pasien_id,
                'pasien_type'       => $modelClass,
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'jenis_kunjungan'   => $request->jenis_kunjungan ?? 'pemeriksaan',
                'keluhan'           => $request->keluhan,
                'petugas_id'        => Auth::id(),
            ]);

            // Hitung Status Gizi (Logic Backend)
            $statusGizi = $this->hitungStatusGizi($request->berat_badan, $request->tinggi_badan);

            Pemeriksaan::create([
                'kunjungan_id'    => $kunjungan->id,
                'pemeriksa_id'    => Auth::id(),
                'pasien_id'       => $request->pasien_id,
                'kategori_pasien' => $request->pasien_type,
                'tanggal_periksa' => $request->tanggal_kunjungan,
                'berat_badan'     => $request->berat_badan,
                'tinggi_badan'    => $request->tinggi_badan,
                'lingkar_kepala'  => $request->lingkar_kepala,
                'lingkar_lengan'  => $request->lingkar_lengan,
                'suhu_tubuh'      => $request->suhu_tubuh,
                'tekanan_darah'   => $request->tekanan_darah,
                'hemoglobin'      => $request->hemoglobin,
                'gula_darah'      => $request->gula_darah,
                'kolesterol'      => $request->kolesterol,
                'asam_urat'       => $request->asam_urat,
                'keluhan'         => $request->keluhan,
                'diagnosa'        => $request->diagnosa,
                'tindakan'        => $request->tindakan,
                'status_gizi'     => $statusGizi
            ]);

            DB::commit();
            return redirect()->route('kader.pemeriksaan.index')->with('success', 'Data berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pemeriksaan = Pemeriksaan::with(['kunjungan', 'pemeriksa.profile'])->findOrFail($id);
        return view('kader.pemeriksaan.show', compact('pemeriksaan'));
    }

    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::with('kunjungan')->findOrFail($id);
        $pasien_type = $pemeriksaan->kategori_pasien;
        return view('kader.pemeriksaan.edit', compact('pemeriksaan', 'pasien_type'));
    }

    public function update(Request $request, $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $kunjungan = Kunjungan::findOrFail($pemeriksaan->kunjungan_id);

        try {
            $kunjungan->update(['keluhan' => $request->keluhan ?? $kunjungan->keluhan]);

            // Hitung ulang status gizi
            $statusGizi = $this->hitungStatusGizi($request->berat_badan, $request->tinggi_badan);

            $pemeriksaan->update([
                'berat_badan'     => $request->berat_badan,
                'tinggi_badan'    => $request->tinggi_badan,
                'lingkar_kepala'  => $request->lingkar_kepala,
                'lingkar_lengan'  => $request->lingkar_lengan,
                'suhu_tubuh'      => $request->suhu_tubuh,
                'tekanan_darah'   => $request->tekanan_darah,
                'hemoglobin'      => $request->hemoglobin, // Update HB
                'gula_darah'      => $request->gula_darah,
                'kolesterol'      => $request->kolesterol,
                'asam_urat'       => $request->asam_urat,
                'diagnosa'        => $request->diagnosa,
                'tindakan'        => $request->tindakan,
                'status_gizi'     => $statusGizi,
            ]);

            return redirect()->route('kader.pemeriksaan.show', $id)->with('success', 'Data diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $kunjunganId = $pemeriksaan->kunjungan_id;
        $pemeriksaan->delete();
        if ($kunjunganId) Kunjungan::destroy($kunjunganId);
        return redirect()->route('kader.pemeriksaan.index')->with('success', 'Data dihapus.');
    }

    // Logika penentuan status gizi
    private function hitungStatusGizi($bb, $tb) {
        if (!$bb || !$tb) return null;
        $tb_m = $tb / 100;
        $imt = $bb / ($tb_m * $tb_m);

        if ($imt < 18.5) return 'kurang'; // Kurus
        if ($imt >= 18.5 && $imt <= 24.9) return 'baik'; // Normal
        if ($imt >= 25 && $imt <= 29.9) return 'lebih'; // Gemuk
        return 'obesitas';
    }
}