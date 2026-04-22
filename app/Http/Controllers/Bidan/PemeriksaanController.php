<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pemeriksaan;

class PemeriksaanController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pending');
        $search = $request->get('search');

        $query = Pemeriksaan::with(['balita', 'remaja', 'lansia', 'ibuHamil', 'pemeriksa'])->latest('tanggal_periksa');
        $query->where('status_verifikasi', $tab);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('balita', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"))
                  ->orWhereHas('remaja', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"))
                  ->orWhereHas('lansia', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"))
                  ->orWhereHas('ibuHamil', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"));
            });
        }

        $pemeriksaans = $query->paginate(15)->withQueryString();
        $pendingCount = Pemeriksaan::where('status_verifikasi', 'pending')->count();

        return view('bidan.pemeriksaan.index', compact('pemeriksaans', 'tab', 'pendingCount', 'search'));
    }

    public function create()
    {
        $balitas = \App\Models\Balita::orderBy('nama_lengkap')->get();
        $remajas = \App\Models\Remaja::orderBy('nama_lengkap')->get();
        $lansias = \App\Models\Lansia::orderBy('nama_lengkap')->get();
        $ibuHamils = \App\Models\IbuHamil::orderBy('nama_lengkap')->get();

        return view('bidan.pemeriksaan.create', compact('balitas', 'remajas', 'lansias', 'ibuHamils'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $pemeriksaan = new Pemeriksaan($request->except(['_token']));
            $pemeriksaan->tanggal_periksa = now();
            $pemeriksaan->pemeriksa_id = Auth::id(); 
            $pemeriksaan->status_verifikasi = 'verified'; 
            $pemeriksaan->verified_by = Auth::id();
            $pemeriksaan->verified_at = now();
            
            $pemeriksaan->save();
            DB::commit();

            return redirect()->route('bidan.pemeriksaan.index', ['tab' => 'verified'])
                             ->with('success', 'Data Pemeriksaan Mandiri berhasil disimpan ke EMR!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function show($id) 
    {
        $pemeriksaan = Pemeriksaan::with(['balita', 'remaja', 'lansia', 'ibuHamil', 'pemeriksa', 'verifikator'])->findOrFail($id);
        return view('bidan.pemeriksaan.show', compact('pemeriksaan'));
    }

    public function verifikasi(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $pemeriksaan = Pemeriksaan::findOrFail($id);

            // Jika Bidan mereset validasi
            if ($request->status_verifikasi === 'pending') {
                $pemeriksaan->update([
                    'status_verifikasi' => 'pending',
                    'diagnosa'          => null,
                    'tindakan'          => null,
                    'catatan_bidan'     => null,
                    'status_gizi'       => null,
                    'indikasi_stunting' => null,
                    'verified_by'       => null,
                    'verified_at'       => null,
                ]);
                DB::commit();
                return redirect()->route('bidan.pemeriksaan.show', $id)
                    ->with('success', 'Validasi dibatalkan. Silakan berikan diagnosa ulang.');
            }

            $request->validate([
                'status_verifikasi' => 'required|in:verified,rejected',
                'diagnosa'          => 'required_if:status_verifikasi,verified',
                'tindakan'          => 'nullable|string',
                'status_gizi'       => 'nullable|string',
                'indikasi_stunting' => 'nullable|string',
                'catatan_bidan'     => 'nullable|string',
                'tfu'               => 'nullable|string',
                'djj'               => 'nullable|string',
                'posisi_janin'      => 'nullable|string',
            ]);

            $dataUpdate = $request->only([
                'diagnosa', 'tindakan', 'catatan_bidan', 
                'status_gizi', 'indikasi_stunting', 
                'tfu', 'djj', 'posisi_janin'
            ]);

            $dataUpdate['status_verifikasi'] = $request->status_verifikasi;

            if ($request->status_verifikasi === 'verified') {
                $dataUpdate['verified_by'] = Auth::id();
                $dataUpdate['verified_at'] = now();
                
                // KALKULATOR IMT DIGITAL (Murni Matematika, Bukan Sistem Pakar)
                if (in_array($pemeriksaan->kategori_pasien, ['remaja', 'lansia', 'ibu_hamil'])) {
                    if ($pemeriksaan->berat_badan > 0 && $pemeriksaan->tinggi_badan > 0) {
                        $tb_m = $pemeriksaan->tinggi_badan / 100;
                        $dataUpdate['imt'] = round($pemeriksaan->berat_badan / ($tb_m * $tb_m), 2);
                    }
                }

                $pesan = 'Validasi klinis dan diagnosa medis berhasil disimpan!';
            } else {
                $dataUpdate['verified_by'] = null;
                $dataUpdate['verified_at'] = null;
                $pesan = 'Data EMR dikembalikan ke Kader karena ditolak.';
            }

            $pemeriksaan->update($dataUpdate);
            DB::commit();

            return redirect()->route('bidan.pemeriksaan.index', ['tab' => 'pending'])->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }

    public function edit($id) { return redirect()->route('bidan.pemeriksaan.show', $id); }
    public function update(Request $request, $id) { return $this->verifikasi($request, $id); }
    public function destroy($id) {
        Pemeriksaan::findOrFail($id)->delete();
        return back()->with('success', 'Data pemeriksaan berhasil dihapus permanen.');
    }
}