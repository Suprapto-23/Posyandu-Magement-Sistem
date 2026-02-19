<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BalitaController extends Controller
{
    /**
     * Menampilkan daftar data balita
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $balitas = Balita::query()
            ->when($search, function($query) use ($search) {
                return $query->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('nama_ibu', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);
            
        return view('kader.data.balita.index', compact('balitas', 'search'));
    }

    /**
     * Form tambah data balita
     */
    public function create()
    {
        // Tidak perlu kirim data user/orangtua untuk dropdown
        // Kader cukup input NIK Ibu secara manual
        return view('kader.data.balita.create');
    }

    /**
     * Simpan data balita baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'nik'           => 'nullable|numeric|digits:16|unique:balitas,nik', // NIK Balita (Opsional jika belum punya)
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            
            // PENTING: Data Orang Tua untuk Integrasi
            'nik_ibu'       => 'required|numeric|digits:16', 
            'nama_ibu'      => 'required|string|max:255',
            'nik_ayah'      => 'nullable|numeric|digits:16',
            'nama_ayah'     => 'nullable|string|max:255',
            
            'alamat'        => 'required|string',
            'berat_lahir'   => 'nullable|numeric|min:0',
            'panjang_lahir' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Cek apakah Ibu sudah punya akun User berdasarkan NIK?
            // Ini untuk mengisi kolom user_id (relasi langsung), meski dashboard user pakai query NIK.
            $linkedUser = User::where('nik', $request->nik_ibu)
                              ->orWhere('nik', $request->nik_ayah)
                              ->first();

            // 2. Generate Kode Unik Balita (Opsional)
            $kode = 'BLT-' . date('ym') . rand(1000, 9999);

            // 3. Simpan Data
            Balita::create([
                'user_id'       => $linkedUser ? $linkedUser->id : null, // Auto-Link jika akun ada
                'kode_balita'   => $kode,
                'nik'           => $request->nik,
                'nama_lengkap'  => $request->nama_lengkap,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nik_ibu'       => $request->nik_ibu,
                'nama_ibu'      => $request->nama_ibu,
                'nik_ayah'      => $request->nik_ayah,
                'nama_ayah'     => $request->nama_ayah,
                'alamat'        => $request->alamat,
                'berat_lahir'   => $request->berat_lahir,
                'panjang_lahir' => $request->panjang_lahir,
                'created_by'    => Auth::id(), // Kader yang menginput
            ]);

            DB::commit();
            return redirect()->route('kader.data.balita.index')
                ->with('success', 'Data balita berhasil ditambahkan & terintegrasi dengan NIK Orang Tua.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Detail data balita
     */
    public function show($id) 
    {
        $balita = Balita::findOrFail($id);
        
        // Hitung usia detail
        $tgl_lahir = \Carbon\Carbon::parse($balita->tanggal_lahir);
        $diff = $tgl_lahir->diff(now());
        $usia_lengkap = $diff->y >= 1 
            ? "{$diff->y} Tahun {$diff->m} Bulan" 
            : "{$diff->m} Bulan {$diff->d} Hari";

        return view('kader.data.balita.show', compact('balita', 'usia_lengkap'));
    }

    /**
     * Form edit data balita
     */
    public function edit($id)
    {
        $balita = Balita::findOrFail($id);
        return view('kader.data.balita.edit', compact('balita'));
    }

    /**
     * Update data balita
     */
    public function update(Request $request, $id)
    {
        $balita = Balita::findOrFail($id);
            
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'nik'           => 'nullable|numeric|digits:16|unique:balitas,nik,' . $id,
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'nik_ibu'       => 'required|numeric|digits:16',
            'nama_ibu'      => 'required|string|max:255',
            'alamat'        => 'required|string',
        ]);

        // Cek ulang link user jika NIK Ibu berubah
        $linkedUser = User::where('nik', $request->nik_ibu)
                          ->orWhere('nik', $request->nik_ayah)
                          ->first();

        $balita->update([
            'user_id'       => $linkedUser ? $linkedUser->id : null,
            'nama_lengkap'  => $request->nama_lengkap,
            'nik'           => $request->nik,
            'tempat_lahir'  => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nik_ibu'       => $request->nik_ibu,
            'nama_ibu'      => $request->nama_ibu,
            'nik_ayah'      => $request->nik_ayah,
            'nama_ayah'     => $request->nama_ayah,
            'alamat'        => $request->alamat,
            'berat_lahir'   => $request->berat_lahir,
            'panjang_lahir' => $request->panjang_lahir,
        ]);

        return redirect()->route('kader.data.balita.show', $id)
            ->with('success', 'Data balita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $balita = Balita::findOrFail($id);
        
        // Cek apakah ada kunjungan terkait agar data history tidak hilang
        if ($balita->kunjungans()->count() > 0) {
            return back()->with('error', 'Gagal hapus: Balita ini memiliki riwayat pemeriksaan. Hapus data kunjungan terlebih dahulu jika ingin menghapus permanen.');
        }
            
        $balita->delete();
        return redirect()->route('kader.data.balita.index')->with('success', 'Data balita berhasil dihapus.');
    }
}