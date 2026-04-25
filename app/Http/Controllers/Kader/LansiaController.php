<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Lansia;
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
 * LANSIA CONTROLLER (KADER WORKSPACE)
 * =========================================================================
 * Menangani direktori kesehatan Lanjut Usia (Lansia & Pra-Lansia).
 * Dilengkapi proteksi transaksi database dan pencarian NIK teroptimasi.
 */
class LansiaController extends Controller
{
    /**
     * 1. INDEX: Menampilkan Direktori Lansia
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = Lansia::query()->latest('created_at');

        // Pencarian Cerdas (Fuzzy Search)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('kode_lansia', 'LIKE', "%{$search}%");
            });
        }

        $lansias = $query->paginate(15)->withQueryString();

        // Statistik Instan untuk UI Dashboard Index
        $stats = [
            'total'     => Lansia::count(),
            'laki_laki' => Lansia::where('jenis_kelamin', 'L')->count(),
            'perempuan' => Lansia::where('jenis_kelamin', 'P')->count(),
        ];

        // Render SPA (Navigasi Halus Tanpa Reload Putih)
        if ($request->ajax() || $request->wantsJson()) {
            return view('kader.data.lansia.index', compact('lansias', 'search', 'stats'))->render();
        }
            
        return view('kader.data.lansia.index', compact('lansias', 'search', 'stats'));
    }

    /**
     * 2. CREATE: Form Pendaftaran Lansia
     */
    public function create()
    {
        return view('kader.data.lansia.create');
    }

    /**
     * 3. STORE: Simpan Data Baru (Dengan Failsafe Transaction)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik'           => 'nullable|digits:16|unique:lansias,nik',
            'nama_lengkap'  => 'required|string|max:191',
            'tempat_lahir'  => 'required|string|max:100',
            // Validasi khusus: Lansia/Pra-lansia idealnya minimal 45 tahun
            'tanggal_lahir' => 'required|date|before:-45 years', 
            'jenis_kelamin' => 'required|in:L,P',
            'telepon'       => 'nullable|string|max:20',
            'alamat'        => 'required|string',
            'golongan_darah'=> 'nullable|in:A,B,AB,O',
        ], [
            'nik.unique'           => 'Peringatan: NIK ini sudah terdaftar sebagai lansia.',
            'nik.digits'           => 'NIK harus terdiri dari 16 digit angka.',
            'tanggal_lahir.before' => 'Kategori Lansia/Pra-Lansia minimal harus berusia 45 tahun.',
        ]);

        DB::beginTransaction();
        try {
            // Generate Kode Unik: LNS-TAHUNBULANHARI-RANDOM
            $kode_lansia = 'LNS-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            
            // Pencarian Akun Tertaut (Lebih Ringan Tanpa Loop All)
            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

            Lansia::create([
                'kode_lansia'   => $kode_lansia,
                'user_id'       => $linkedUser ? $linkedUser->id : null,
                'nik'           => $request->nik,
                'nama_lengkap'  => $request->nama_lengkap,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'telepon'       => $request->telepon,
                'alamat'        => $request->alamat,
                'golongan_darah'=> $request->golongan_darah,
                'created_by'    => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('kader.data.lansia.index')->with('success', 'Registrasi Selesai! Data Lansia berhasil ditambahkan ke direktori.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('KADER_LANSIA_STORE_ERROR: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Kegagalan Sistem: Gagal menyimpan data ke server.');
        }
    }

    /**
     * 4. SHOW: Buku Pemantauan Lansia (Menarik Riwayat Kunjungan)
     */
    public function show($id)
    {
        // Eager load riwayat kunjungan dan pemeriksaan agar halaman detail terbuka instan
        $lansia = Lansia::with([
            'user', 
            'kunjungans' => function($q) { $q->latest('tanggal_kunjungan'); },
            'kunjungans.pemeriksaan'
        ])->findOrFail($id);

        return view('kader.data.lansia.show', compact('lansia'));
    }

    /**
     * 5. EDIT: Form Koreksi
     */
    public function edit($id)
    {
        $lansia = Lansia::findOrFail($id);
        return view('kader.data.lansia.edit', compact('lansia'));
    }

    /**
     * 6. UPDATE: Pembaruan Data
     */
    public function update(Request $request, $id)
    {
        $lansia = Lansia::findOrFail($id);

        $request->validate([
            'nik'           => 'nullable|digits:16|unique:lansias,nik,' . $lansia->id,
            'nama_lengkap'  => 'required|string|max:191',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:-45 years',
            'jenis_kelamin' => 'required|in:L,P',
            'telepon'       => 'nullable|string|max:20',
            'alamat'        => 'required|string',
            'golongan_darah'=> 'nullable|in:A,B,AB,O',
        ]);

        DB::beginTransaction();
        try {
            // Cek ulang jika NIK / Nama berubah
            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

            $lansia->update([
                'user_id'       => $linkedUser ? $linkedUser->id : $lansia->user_id,
                'nik'           => $request->nik,
                'nama_lengkap'  => $request->nama_lengkap,
                'tempat_lahir'  => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'telepon'       => $request->telepon,
                'alamat'        => $request->alamat,
                'golongan_darah'=> $request->golongan_darah,
            ]);

            DB::commit();
            return redirect()->route('kader.data.lansia.show', $lansia->id)->with('success', 'Koreksi Berhasil! Data Lansia telah diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('KADER_LANSIA_UPDATE_ERROR: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui data.');
        }
    }

    /**
     * 7. DESTROY: Hapus Permanen 1 Data
     */
    public function destroy($id)
    {
        try {
            $lansia = Lansia::findOrFail($id);
            $nama = $lansia->nama_lengkap;
            $lansia->delete();
            return redirect()->route('kader.data.lansia.index')->with('success', "Arsip dihapus. Data atas nama {$nama} berhasil dihilangkan.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus data. Pastikan data tidak sedang terkunci oleh rekam medis terkait.');
        }
    }

    /**
     * 8. BULK DELETE: Eksekusi Penghapusan Masal
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || count($ids) == 0) {
            return redirect()->back()->with('error', 'Tidak ada data yang dicentang untuk dihapus!');
        }
        
        try {
            Lansia::whereIn('id', $ids)->delete();
            return redirect()->route('kader.data.lansia.index')->with('success', count($ids) . ' Data lansia berhasil dibersihkan secara masal.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Sebagian data gagal dihapus karena melindungi integritas relasi database.');
        }
    }

    /**
     * 9. SYNC USER: Tarik Akun Warga Secara Manual via UI
     */
    public function syncUser($id)
    {
        $lansia = Lansia::findOrFail($id);
        $user = $this->findLinkedUser($lansia->nik, $lansia->nama_lengkap);
        
        if ($user) {
            $lansia->user_id = $user->id;
            $lansia->save();
            return redirect()->back()->with('success', 'Integrasi Terkunci! Akun Lansia ini berhasil dihubungkan dengan perangkat pengguna: ' . $user->name);
        }
        return redirect()->back()->with('error', 'Sinkronisasi Gagal! Tidak ditemukan pengguna aplikasi Warga dengan NIK tersebut.');
    }

    /**
     * 10. HELPER: Pencari Akun Taut (Murni SQL - Anti Memory Leak)
     */
    private function findLinkedUser($nik, $nama_lengkap)
    {
        $cleanNik  = preg_replace('/[^0-9]/', '', (string)$nik);
        $cleanName = trim((string)$nama_lengkap);

        if (empty($cleanNik) && empty($cleanName)) return null;

        // TAHAP 1: Cari berdasarkan NIK langsung menggunakan Query Database
        if (!empty($cleanNik)) {
            $user = User::where('nik', $cleanNik)
                        ->orWhere('username', $cleanNik)
                        ->orWhere('email', $cleanNik)
                        ->first();
            if ($user) return $user;

            if (Schema::hasTable('profiles')) {
                $profile = Profile::where('nik', $cleanNik)->orWhere('no_ktp', $cleanNik)->first();
                if ($profile && $profile->user) return $profile->user;
            }
        }

        // TAHAP 2: Jika NIK Kosong/Tidak Ditemukan, gunakan Fuzzy Search Nama
        if (!empty($cleanName)) {
            $userByName = User::where('name', 'LIKE', "%{$cleanName}%")->first();
            if ($userByName) return $userByName;

            if (Schema::hasTable('profiles')) {
                $profileByName = Profile::where('full_name', 'LIKE', "%{$cleanName}%")->first();
                if ($profileByName && $profileByName->user) return $profileByName->user;
            }
        }

        return null; // Tidak ada kecocokan di database
    }
}