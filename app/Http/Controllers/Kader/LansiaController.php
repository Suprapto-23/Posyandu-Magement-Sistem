<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Lansia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LansiaController extends Controller
{
    /**
     * Menampilkan daftar lansia
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $lansias = Lansia::query()
            ->when($search, function($query) use ($search) {
                return $query->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('kode_lansia', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);
            
        return view('kader.data.lansia.index', compact('lansias', 'search'));
    }

    /**
     * Form tambah lansia
     */
    public function create()
    {
        return view('kader.data.lansia.create');
    }

    /**
     * Simpan data lansia
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nik'             => 'required|numeric|digits:16|unique:lansias,nik', // Wajib Unik
            'jenis_kelamin'   => 'required|in:L,P',
            'tempat_lahir'    => 'required|string|max:100',
            'tanggal_lahir'   => 'required|date|before:today',
            'alamat'          => 'required|string',
            'penyakit_bawaan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Cek apakah Lansia sudah punya akun User (berdasarkan NIK)?
            $linkedUser = User::where('nik', $request->nik)->first();

            // 2. Generate Kode Lansia
            $kode = 'LNS-' . date('ym') . rand(1000, 9999);

            // 3. Simpan Data
            Lansia::create([
                'user_id'         => $linkedUser ? $linkedUser->id : null, // Auto-Link
                'kode_lansia'     => $kode,
                'nik'             => $request->nik,
                'nama_lengkap'    => $request->nama_lengkap,
                'tempat_lahir'    => $request->tempat_lahir,
                'tanggal_lahir'   => $request->tanggal_lahir,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'alamat'          => $request->alamat,
                'penyakit_bawaan' => $request->penyakit_bawaan,
                'created_by'      => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('kader.data.lansia.index')
                ->with('success', 'Data Lansia berhasil disimpan & terintegrasi (jika akun ada).');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Detail lansia
     */
    public function show($id) 
    {
        // Load relasi kunjungans untuk riwayat
        $lansia = Lansia::with(['kunjungans' => function($q) {
            $q->latest()->take(10);
        }])->findOrFail($id);
        
        // Hitung usia bulat menggunakan Carbon age
        $usia = \Carbon\Carbon::parse($lansia->tanggal_lahir)->age;

        return view('kader.data.lansia.show', compact('lansia', 'usia'));
    }

    /**
     * Form edit lansia
     */
    public function edit($id)
    {
        $lansia = Lansia::findOrFail($id);
        return view('kader.data.lansia.edit', compact('lansia'));
    }

    /**
     * Update data lansia
     */
    public function update(Request $request, $id)
    {
        $lansia = Lansia::findOrFail($id);
            
        $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nik'             => 'required|numeric|digits:16|unique:lansias,nik,' . $id,
            'jenis_kelamin'   => 'required|in:L,P',
            'tempat_lahir'    => 'required|string|max:100',
            'tanggal_lahir'   => 'required|date|before:today',
            'alamat'          => 'required|string',
            'penyakit_bawaan' => 'nullable|string',
        ]);

        // Cek ulang link user jika NIK berubah
        $linkedUser = User::where('nik', $request->nik)->first();

        $lansia->update([
            'user_id'         => $linkedUser ? $linkedUser->id : null,
            'nik'             => $request->nik,
            'nama_lengkap'    => $request->nama_lengkap,
            'tempat_lahir'    => $request->tempat_lahir,
            'tanggal_lahir'   => $request->tanggal_lahir,
            'jenis_kelamin'   => $request->jenis_kelamin,
            'alamat'          => $request->alamat,
            'penyakit_bawaan' => $request->penyakit_bawaan,
        ]);

        return redirect()->route('kader.data.lansia.show', $id)
            ->with('success', 'Data Lansia berhasil diperbarui.');
    }

    /**
     * Hapus data lansia
     */
    public function destroy($id)
    {
        $lansia = Lansia::findOrFail($id);
        
        // Cek apakah ada riwayat kunjungan agar data aman
        if ($lansia->kunjungans()->count() > 0) {
            return back()->with('error', 'Gagal hapus: Lansia ini memiliki riwayat pemeriksaan. Hapus data kunjungan terlebih dahulu.');
        }
            
        $lansia->delete();
        return redirect()->route('kader.data.lansia.index')->with('success', 'Data Lansia berhasil dihapus.');
    }
}