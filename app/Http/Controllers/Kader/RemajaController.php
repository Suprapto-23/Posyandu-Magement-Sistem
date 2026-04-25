<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Remaja;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * =========================================================================
 * REMAJA CONTROLLER (KADER WORKSPACE)
 * =========================================================================
 * Menangani direktori kesehatan Remaja. 
 * Dilengkapi SQL Builder Query cerdas untuk sinkronisasi NIK tanpa membebani RAM.
 */
class RemajaController extends Controller
{
    /**
     * 1. INDEX: Menampilkan Direktori Remaja
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = Remaja::query()->latest('created_at');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('sekolah', 'LIKE', "%{$search}%")
                  ->orWhere('kode_remaja', 'LIKE', "%{$search}%");
            });
        }

        $remajas = $query->paginate(15)->withQueryString();

        // Statistik Instan untuk UI
        $stats = [
            'total'     => Remaja::count(),
            'laki_laki' => Remaja::where('jenis_kelamin', 'L')->count(),
            'perempuan' => Remaja::where('jenis_kelamin', 'P')->count(),
        ];

        // SPA Render (Tanpa Reload Putih)
        if ($request->ajax() || $request->wantsJson()) {
            return view('kader.data.remaja.index', compact('remajas', 'search', 'stats'))->render();
        }
            
        return view('kader.data.remaja.index', compact('remajas', 'search', 'stats'));
    }

    /**
     * 2. CREATE
     */
    public function create()
    {
        return view('kader.data.remaja.create');
    }

    /**
     * 3. STORE (Dengan DB Transaction)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik'           => 'nullable|digits:16|unique:remajas,nik',
            'nama_lengkap'  => 'required|string|max:191',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_wali'     => 'required|string|max:191',
            'telepon'       => 'nullable|string|max:20',
            'alamat'        => 'required|string',
            'sekolah'       => 'nullable|string|max:191',
            'golongan_darah'=> 'nullable|in:A,B,AB,O',
        ], [
            'nik.unique' => 'NIK Remaja ini sudah terdaftar di database.',
            'nik.digits' => 'NIK harus 16 digit angka.',
        ]);

        DB::beginTransaction();
        try {
            $kode_remaja = 'RMJ-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

            Remaja::create([
                'kode_remaja'   => $kode_remaja,
                'user_id'       => $linkedUser ? $linkedUser->id : null,
                'nik'           => $request->nik,
                'nama_lengkap'  => $request->nama_lengkap,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nama_wali'     => $request->nama_wali,
                'telepon'       => $request->telepon,
                'alamat'        => $request->alamat,
                'sekolah'       => $request->sekolah,
                'golongan_darah'=> $request->golongan_darah,
                'created_by'    => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('kader.data.remaja.index')->with('success', 'Data Remaja berhasil ditambahkan!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('KADER_REMAJA_STORE_ERROR: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data ke server.');
        }
    }

    /**
     * 4. SHOW
     */
    public function show($id)
    {
        $remaja = Remaja::with([
            'user', 
            'kunjungans' => function($q) { $q->latest('tanggal_kunjungan'); },
            'kunjungans.pemeriksaan'
        ])->findOrFail($id);

        return view('kader.data.remaja.show', compact('remaja'));
    }

    /**
     * 5. EDIT
     */
    public function edit($id)
    {
        $remaja = Remaja::findOrFail($id);
        return view('kader.data.remaja.edit', compact('remaja'));
    }

    /**
     * 6. UPDATE
     */
    public function update(Request $request, $id)
    {
        $remaja = Remaja::findOrFail($id);

        $request->validate([
            'nik'           => 'nullable|digits:16|unique:remajas,nik,' . $remaja->id,
            'nama_lengkap'  => 'required|string|max:191',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_wali'     => 'required|string|max:191',
            'telepon'       => 'nullable|string|max:20',
            'alamat'        => 'required|string',
            'sekolah'       => 'nullable|string|max:191',
            'golongan_darah'=> 'nullable|in:A,B,AB,O',
        ]);

        DB::beginTransaction();
        try {
            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

            $remaja->update([
                'user_id'       => $linkedUser ? $linkedUser->id : $remaja->user_id,
                'nik'           => $request->nik,
                'nama_lengkap'  => $request->nama_lengkap,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'nama_wali'     => $request->nama_wali,
                'telepon'       => $request->telepon,
                'alamat'        => $request->alamat,
                'sekolah'       => $request->sekolah,
                'golongan_darah'=> $request->golongan_darah,
            ]);

            DB::commit();
            return redirect()->route('kader.data.remaja.show', $remaja->id)->with('success', 'Data Remaja berhasil diperbarui!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('KADER_REMAJA_UPDATE_ERROR: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * 7. DESTROY
     */
    public function destroy($id)
    {
        try {
            $remaja = Remaja::findOrFail($id);
            $nama = $remaja->nama_lengkap;
            $remaja->delete();
            return redirect()->route('kader.data.remaja.index')->with('success', "Data remaja {$nama} berhasil dihapus.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus. Data terkunci oleh rekam medis.');
        }
    }

    /**
     * 8. BULK DELETE
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || count($ids) == 0) return redirect()->back()->with('error', 'Tidak ada data yang dipilih!');
        
        try {
            Remaja::whereIn('id', $ids)->delete();
            return redirect()->route('kader.data.remaja.index')->with('success', count($ids) . ' Data berhasil dibersihkan masal.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Sebagian data gagal dihapus karena relasi database.');
        }
    }

    /**
     * 9. SYNC USER MANUAL
     */
    public function syncUser($id)
    {
        $remaja = Remaja::findOrFail($id);
        $user = $this->findLinkedUser($remaja->nik, $remaja->nama_lengkap);
        
        if ($user) {
            $remaja->user_id = $user->id;
            $remaja->save();
            return redirect()->back()->with('success', 'Akun terhubung dengan pengguna: ' . $user->name);
        }
        return redirect()->back()->with('error', 'Gagal! Warga dengan NIK/Nama tersebut belum menginstal aplikasi.');
    }

    /**
     * 10. HELPER: Pencarian Lintas Tabel via SQL (Pengganti User::all)
     */
    private function findLinkedUser($nik, $nama_lengkap)
    {
        $cleanNik  = preg_replace('/[^0-9]/', '', (string)$nik);
        $cleanName = trim((string)$nama_lengkap);

        if (empty($cleanNik) && empty($cleanName)) return null;

        if (!empty($cleanNik)) {
            $user = User::where('nik', $cleanNik)->orWhere('username', $cleanNik)->first();
            if ($user) return $user;

            if (Schema::hasTable('profiles')) {
                $profile = Profile::where('nik', $cleanNik)->orWhere('no_ktp', $cleanNik)->first();
                if ($profile && $profile->user) return $profile->user;
            }
        }

        if (!empty($cleanName)) {
            $userByName = User::where('name', 'LIKE', "%{$cleanName}%")->first();
            if ($userByName) return $userByName;
        }

        return null;
    }
}