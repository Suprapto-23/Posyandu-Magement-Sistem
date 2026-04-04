<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use App\Models\IbuHamil;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IbuHamilController extends Controller
{
    /**
     * Tampilkan daftar antrean Ibu Hamil
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // Tarik data ibu hamil beserta pemeriksaan terakhirnya
        $ibuHamils = IbuHamil::with(['pemeriksaans' => function($q) {
                $q->latest('tanggal_periksa');
            }])
            ->when($search, function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('bidan.data.ibu_hamil.index', compact('ibuHamils', 'search'));
    }

    /**
     * Tampilkan Detail Profil & Riwayat Klinis
     */
    public function show($id)
    {
        $ibuHamil = IbuHamil::with(['pemeriksaans' => function($q) {
            $q->orderBy('tanggal_periksa', 'desc');
        }, 'pemeriksaans.pemeriksa', 'pemeriksaans.verifikator'])->findOrFail($id);
        
        return view('bidan.data.ibu_hamil.show', compact('ibuHamil'));
    }

    /**
     * Form Edit Biodata Pasien
     */
    public function edit($id)
    {
        $ibuHamil = IbuHamil::findOrFail($id);
        return view('bidan.data.ibu_hamil.edit', compact('ibuHamil'));
    }

    /**
     * Update Biodata Pasien
     */
    public function update(Request $request, $id)
    {
        $ibu = IbuHamil::findOrFail($id);
        
        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nik'            => 'nullable|numeric|digits:16|unique:ibu_hamils,nik,'.$id,
            'tempat_lahir'   => 'nullable|string|max:100',
            'tanggal_lahir'  => 'nullable|date',
            'nama_suami'     => 'nullable|string|max:255',
            'telepon_ortu'   => 'nullable|string|max:20',
            'golongan_darah' => 'nullable|in:A,B,AB,O',
            'hpht'           => 'nullable|date',
            'hpl'            => 'nullable|date',
            'alamat'         => 'required|string',
        ]);

        $ibu->update($request->all());

        return redirect()->route('bidan.data.ibu-hamil.show', $ibu->id)
            ->with('success', 'Biodata Ibu Hamil berhasil diperbarui.');
    }

    /**
     * Form Validasi Medis Bidan (Meja 5)
     */
    public function createPemeriksaan($id)
    {
        $ibuHamil = IbuHamil::findOrFail($id);
        
        // Cari draf fisik dari kader (status = pending) hari ini atau terbaru
        $pemeriksaan = Pemeriksaan::where('pasien_id', $id)
            ->where('kategori_pasien', 'ibu_hamil')
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->first();

        // Jika tidak ada draf dari kader, buatkan objek kosong agar form tidak error
        if (!$pemeriksaan) {
            $pemeriksaan = new Pemeriksaan();
        }

        return view('bidan.data.ibu_hamil.create_pemeriksaan', compact('ibuHamil', 'pemeriksaan'));
    }

    /**
     * Simpan Hasil Validasi Medis
     */
    public function storePemeriksaan(Request $request, $id)
    {
        $request->validate([
            'tfu'          => 'nullable|numeric',
            'djj'          => 'nullable|numeric',
            'posisi_janin' => 'nullable|string|max:50',
            'hemoglobin'   => 'nullable|numeric',
            'diagnosa'     => 'required|string',
            'tindakan'     => 'nullable|string',
            'catatan_bidan'=> 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Cari draf pemeriksaan yang dikirim kader
            $pemeriksaan = Pemeriksaan::where('pasien_id', $id)
                ->where('kategori_pasien', 'ibu_hamil')
                ->where('status_verifikasi', 'pending')
                ->latest()
                ->first();

            // Jika bidan memeriksa tanpa ada draf kader, buat baru (Bidan merangkap kader)
            if (!$pemeriksaan) {
                $pemeriksaan = new Pemeriksaan();
                $pemeriksaan->pasien_id = $id;
                $pemeriksaan->kategori_pasien = 'ibu_hamil';
                $pemeriksaan->tanggal_periksa = Carbon::today();
                $pemeriksaan->pemeriksa_id = auth()->id();
            }

            // Hitung usia kehamilan saat periksa
            $ibuHamil = IbuHamil::find($id);
            if ($ibuHamil && $ibuHamil->hpht) {
                $usiaMinggu = (int) floor(Carbon::parse($pemeriksaan->tanggal_periksa)->diffInDays($ibuHamil->hpht) / 7);
                $pemeriksaan->usia_kehamilan_periksa = $usiaMinggu;
            }

            // Isi data klinis dari form Bidan
            $pemeriksaan->tfu = $request->tfu;
            $pemeriksaan->djj = $request->djj;
            $pemeriksaan->posisi_janin = $request->posisi_janin;
            $pemeriksaan->hemoglobin = $request->hemoglobin;
            $pemeriksaan->diagnosa = $request->diagnosa;
            $pemeriksaan->tindakan = $request->tindakan;
            $pemeriksaan->catatan_bidan = $request->catatan_bidan;
            
            // Tandai sebagai Tervalidasi Bidan
            $pemeriksaan->status_verifikasi = 'verified';
            $pemeriksaan->verified_by = auth()->id();
            $pemeriksaan->verified_at = now();
            
            $pemeriksaan->save();

            DB::commit();
            return redirect()->route('bidan.data.ibu-hamil.show', $id)
                ->with('success', 'Pemeriksaan Medis berhasil disimpan dan divalidasi!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pemeriksaan: ' . $e->getMessage());
        }
    }
}