<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Pemeriksaan;

class PemeriksaanController extends Controller
{
    /**
     * INDEX: Ruang Tunggu Validasi Bidan
     */
    public function index(Request $request)
    {
        try {
            $tab = $request->get('tab', 'pending');
            $search = $request->get('search');

            // Integrasi Akurat: Menarik data via pintu 'kunjungan.pasien'
            $query = Pemeriksaan::with(['kunjungan.pasien', 'pemeriksa'])->latest();

            if ($tab === 'verified') {
                $query->verified();
            } else {
                $query->pending();
                $tab = 'pending';
            }

            // Pencarian Cerdas (Nama Pasien atau NIK)
            if ($search) {
                $query->whereHas('kunjungan.pasien', function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nik', 'like', "%{$search}%");
                });
            }

            $pemeriksaans = $query->paginate(15)->withQueryString();
            $pendingCount = Pemeriksaan::pending()->count();

            return view('bidan.pemeriksaan.index', compact('pemeriksaans', 'tab', 'pendingCount'));

        } catch (\Exception $e) {
            Log::error('BIDAN_INDEX_ERROR: ' . $e->getMessage());
            abort(500, 'Gagal memuat data antrian.');
        }
    }

    /**
     * SHOW: Ruang Periksa (Meja 5)
     * Di sini Bidan melihat input Kader, melakukan koreksi, dan diagnosa.
     */
    public function show($id)
    {
        // Eager loading polimorfik agar lancar
        $pemeriksaan = Pemeriksaan::with(['kunjungan.pasien', 'pemeriksa'])->findOrFail($id);
        return view('bidan.pemeriksaan.show', compact('pemeriksaan'));
    }

    /**
     * UPDATE: Eksekusi Finalisasi Medis
     * Bidan mengunci data dan menyimpannya ke EMR permanen.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $pemeriksaan = Pemeriksaan::findOrFail($id);
            
            // Ambil semua data. Bidan bisa mengubah angka fisik jika Kader salah input.
            $data = $request->all();
            
            // Stempel Otoritas Bidan
            $data['status_verifikasi'] = 'verified'; 
            $data['verified_by']       = Auth::id();
            $data['verified_at']       = Carbon::now();

            $pemeriksaan->update($data);

            DB::commit();
            return redirect()->route('bidan.pemeriksaan.index', ['tab' => 'verified'])
                             ->with('success', 'Data medis warga berhasil divalidasi dan disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BIDAN_VALIDASI_ERROR: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses data klinis.');
        }
    }

    /**
     * DESTROY: Hapus Data (Emergency Only)
     */
    public function destroy($id)
    {
        $pem = Pemeriksaan::findOrFail($id);
        $pem->delete();
        return back()->with('success', 'Data dihapus.');
    }
}