<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Remaja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RemajaController extends Controller
{
    /**
     * Menampilkan daftar data remaja
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $remajas = Remaja::query()
            ->when($search, function($query) use ($search) {
                return $query->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('sekolah', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);
            
        return view('kader.data.remaja.index', compact('remajas', 'search'));
    }

    /**
     * Form tambah data remaja
     */
    public function create()
    {
        return view('kader.data.remaja.create');
    }

    /**
     * Simpan data remaja baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'nik'           => 'required|numeric|digits:16|unique:remajas,nik', // Wajib & Unik
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'sekolah'       => 'nullable|string|max:255',
            'kelas'         => 'nullable|string|max:50',
            'nama_ortu'     => 'nullable|string|max:255',
            'telepon_ortu'  => 'nullable|string|max:20',
            'alamat'        => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Cek apakah Remaja sudah punya akun User berdasarkan NIK?
            $linkedUser = User::where('nik', $request->nik)->first();

            // 2. Generate Kode Unik Remaja
            $kode = 'RMJ-' . date('ym') . rand(1000, 9999);

            // 3. Simpan Data
            Remaja::create([
                'user_id'       => $linkedUser ? $linkedUser->id : null, // Auto-Link
                'kode_remaja'   => $kode,
                'nik'           => $request->nik,
                'nama_lengkap'  => $request->nama_lengkap,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'sekolah'       => $request->sekolah,
                'kelas'         => $request->kelas,
                'nama_ortu'     => $request->nama_ortu,
                'telepon_ortu'  => $request->telepon_ortu,
                'alamat'        => $request->alamat,
                'created_by'    => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('kader.data.remaja.index')
                ->with('success', 'Data remaja berhasil ditambahkan & terintegrasi (jika akun ada).');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Detail data remaja
     */
    public function show($id) 
    {
        $remaja = Remaja::with(['kunjungans' => function($q) {
            $q->latest()->take(10); // Ambil 10 kunjungan terakhir
        }])->findOrFail($id);
        
        $usia = \Carbon\Carbon::parse($remaja->tanggal_lahir)->age;

        return view('kader.data.remaja.show', compact('remaja', 'usia'));
    }

    /**
     * Form edit data remaja
     */
    public function edit($id)
    {
        $remaja = Remaja::findOrFail($id);
        return view('kader.data.remaja.edit', compact('remaja'));
    }

    /**
     * Update data remaja
     */
    public function update(Request $request, $id)
    {
        $remaja = Remaja::findOrFail($id);
            
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'nik'           => 'required|numeric|digits:16|unique:remajas,nik,' . $id,
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'alamat'        => 'required|string',
        ]);

        // Cek ulang link user jika NIK berubah
        $linkedUser = User::where('nik', $request->nik)->first();

        $remaja->update([
            'user_id'       => $linkedUser ? $linkedUser->id : null,
            'nik'           => $request->nik,
            'nama_lengkap'  => $request->nama_lengkap,
            'tempat_lahir'  => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'sekolah'       => $request->sekolah,
            'kelas'         => $request->kelas,
            'nama_ortu'     => $request->nama_ortu,
            'telepon_ortu'  => $request->telepon_ortu,
            'alamat'        => $request->alamat,
        ]);

        return redirect()->route('kader.data.remaja.show', $id)
            ->with('success', 'Data remaja berhasil diperbarui.');
    }

    /**
     * Hapus data remaja
     */
    public function destroy($id)
    {
        $remaja = Remaja::findOrFail($id);
        
        // Cek relasi kunjungan
        if ($remaja->kunjungans()->count() > 0) {
            // Opsi: Block hapus atau hapus cascade (tergantung kebijakan)
            // Disini kita block agar aman
            return back()->with('error', 'Gagal hapus: Remaja ini memiliki riwayat pemeriksaan. Hapus data kunjungan terlebih dahulu.');
        }
            
        $remaja->delete();
        return redirect()->route('kader.data.remaja.index')->with('success', 'Data remaja berhasil dihapus.');
    }
}