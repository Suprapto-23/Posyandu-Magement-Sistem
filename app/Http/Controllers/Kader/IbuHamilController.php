<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\IbuHamil;
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
 * IBU HAMIL CONTROLLER (KADER WORKSPACE)
 * =========================================================================
 * Modul utama untuk manajemen data Ibu Hamil.
 * Menangani CRUD, Filter Trimester/Hampir Lahir, Bulk Delete, dan SPA Interactivity.
 */
class IbuHamilController extends Controller
{
    /**
     * 1. INDEX: Menampilkan Direktori Ibu Hamil
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $filter = $request->get('filter', 'semua'); // Tabs: 'semua', 'aktif', 'hampir_lahir'

        // Base Query dengan Eager Loading untuk mencegah N+1 Query Problem
        $query = IbuHamil::query()->latest('created_at');

        // Fitur Pencarian Lanjutan (Fuzzy Search)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhere('nama_suami', 'LIKE', "%{$search}%")
                  ->orWhere('kode_bumil', 'LIKE', "%{$search}%");
            });
        }

        // Filter Berdasarkan Status Kandungan
        if ($filter === 'aktif') {
            $query->where('status', 'aktif');
        } elseif ($filter === 'hampir_lahir') {
            // Memanggil local scope di model IbuHamil (asumsi: ada fungsi scopeHampirLahir)
            // Jika tidak ada scope, bisa pakai whereRaw HPL mendekati
            if (method_exists(IbuHamil::class, 'scopeHampirLahir')) {
                $query->hampirLahir(30); // Prediksi 30 hari menuju HPL
            }
        }

        $ibuHamils = $query->paginate(15)->withQueryString();

        // Kalkulasi Statistik Instan untuk UI Dashboard
        $stats = [
            'total'        => IbuHamil::count(),
            'aktif'        => IbuHamil::where('status', 'aktif')->count(),
            'hampir_lahir' => method_exists(IbuHamil::class, 'scopeHampirLahir') 
                                ? IbuHamil::hampirLahir(30)->count() 
                                : 0,
        ];

        // Render khusus untuk request AJAX (Navigasi SPA Tanpa Reload)
        if ($request->ajax() || $request->wantsJson()) {
            return view('kader.data.ibu-hamil.index', compact('ibuHamils', 'search', 'filter', 'stats'))->render();
        }

        return view('kader.data.ibu-hamil.index', compact('ibuHamils', 'search', 'filter', 'stats'));
    }

    /**
     * 2. CREATE: Menampilkan Form Pendaftaran
     */
    public function create()
    {
        return view('kader.data.ibu-hamil.create');
    }

    /**
     * 3. STORE: Menyimpan Data Ibu Hamil Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik'              => 'nullable|digits:16|unique:ibu_hamils,nik',
            'nama_lengkap'     => 'required|string|max:191',
            'tempat_lahir'     => 'required|string|max:100',
            'tanggal_lahir'    => 'required|date|before:today',
            'nama_suami'       => 'nullable|string|max:191',
            'telepon'          => 'nullable|string|max:20',
            'alamat'           => 'required|string',
            'hpht'             => 'nullable|date|before_or_equal:today', // Hari Pertama Haid Terakhir
            'hpl'              => 'nullable|date|after:today',           // Hari Perkiraan Lahir
            'status'           => 'required|in:aktif,melahirkan,keguguran',
        ], [
            'nik.unique'       => 'Peringatan: NIK ibu ini sudah terdaftar di dalam database Bumil.',
            'nik.digits'       => 'NIK harus terdiri dari 16 digit angka yang valid.',
            'tanggal_lahir.before' => 'Tanggal lahir tidak valid.',
            'hpl.after'        => 'Hari Perkiraan Lahir harus di masa depan.',
        ]);

        DB::beginTransaction();
        try {
            // Generate Kode Rekam Medis Khusus Bumil (BML-YYYYMMDD-XXXX)
            $kode_bumil = 'BML-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            // Engine Cerdas: Cari otomatis akun Warga (Ibu) yang memiliki NIK sama
            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

            IbuHamil::create([
                'kode_bumil'       => $kode_bumil,
                'user_id'          => $linkedUser ? $linkedUser->id : null,
                'nik'              => $request->nik,
                'nama_lengkap'     => $request->nama_lengkap,
                'tempat_lahir'     => $request->tempat_lahir,
                'tanggal_lahir'    => $request->tanggal_lahir,
                'nama_suami'       => $request->nama_suami,
                'telepon'          => $request->telepon,
                'alamat'           => $request->alamat,
                'hpht'             => $request->hpht,
                'hpl'              => $request->hpl,
                'status'           => $request->status,
                'created_by'       => Auth::id(), // ID Kader pencatat
            ]);

            DB::commit();
            return redirect()->route('kader.data.ibu-hamil.index')->with('success', 'Registrasi Selesai! Data Ibu Hamil berhasil ditambahkan ke Direktori.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('KADER_BUMIL_STORE_ERROR: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Kegagalan Sistem: Data gagal disimpan. Silakan coba kembali.');
        }
    }

   /**
     * 4. SHOW: Detail Buku KIA Bumil
     */
    public function show($id)
    {
        // ARSITEKTUR TERPADU: Load IbuHamil -> Kunjungans -> Pemeriksaan
        $ibuHamil = IbuHamil::with([
            'user', 
            'kunjungans' => function($q) { 
                $q->with(['pemeriksaan'])->latest('tanggal_kunjungan')->take(10); 
            }
        ])->findOrFail($id);

        return view('kader.data.ibu-hamil.show', compact('ibuHamil'));
    }

    /**
     * 5. EDIT: Form Koreksi Data
     */
    public function edit($id)
    {
        $ibuHamil = IbuHamil::findOrFail($id);
        return view('kader.data.ibu-hamil.edit', compact('ibuHamil'));
    }

    /**
     * 6. UPDATE: Pembaruan Data
     */
    public function update(Request $request, $id)
    {
        $ibuHamil = IbuHamil::findOrFail($id);

        $request->validate([
            'nik'              => 'nullable|digits:16|unique:ibu_hamils,nik,' . $ibuHamil->id,
            'nama_lengkap'     => 'required|string|max:191',
            'tempat_lahir'     => 'required|string|max:100',
            'tanggal_lahir'    => 'required|date|before:today',
            'nama_suami'       => 'nullable|string|max:191',
            'telepon'          => 'nullable|string|max:20',
            'alamat'           => 'required|string',
            'hpht'             => 'nullable|date|before_or_equal:today',
            'hpl'              => 'nullable|date', // Bisa di masa lalu jika sudah melahirkan
            'status'           => 'required|in:aktif,melahirkan,keguguran',
        ]);

        DB::beginTransaction();
        try {
            // Cek ulang sinkronisasi jika NIK / Nama berubah
            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

            $ibuHamil->update([
                'user_id'          => $linkedUser ? $linkedUser->id : $ibuHamil->user_id,
                'nik'              => $request->nik,
                'nama_lengkap'     => $request->nama_lengkap,
                'tempat_lahir'     => $request->tempat_lahir,
                'tanggal_lahir'    => $request->tanggal_lahir,
                'nama_suami'       => $request->nama_suami,
                'telepon'          => $request->telepon,
                'alamat'           => $request->alamat,
                'hpht'             => $request->hpht,
                'hpl'              => $request->hpl,
                'status'           => $request->status,
            ]);

            DB::commit();
            return redirect()->route('kader.data.ibu-hamil.show', $ibuHamil->id)->with('success', 'Koreksi Disimpan! Data Ibu Hamil telah diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('KADER_BUMIL_UPDATE_ERROR: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Kegagalan Sistem saat memperbarui data.');
        }
    }

    /**
     * 7. DESTROY: Hapus Permanen 1 Data
     */
   public function destroy($id)
    {
        try {
            $ibuHamil = IbuHamil::findOrFail($id);
            $nama = $ibuHamil->nama_lengkap;
            
            // CEGAH HAPUS JIKA SUDAH ADA KUNJUNGAN
            if ($ibuHamil->kunjungans()->count() > 0) {
                return back()->with('error', 'Ditolak: Data ini sudah memiliki riwayat kunjungan/pemeriksaan.');
            }

            $ibuHamil->delete(); 
            return redirect()->route('kader.data.ibu-hamil.index')->with('success', "Arsip dihapus. Data atas nama Ibu {$nama} telah dihilangkan dari sistem.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus data.');
        }
    }

    /**
     * 8. BULK DELETE: Hapus Banyak Data Sekaligus
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || count($ids) == 0) {
            return redirect()->back()->with('error', 'Misi Dibatalkan: Tidak ada data Ibu Hamil yang dicentang.');
        }

        // Proteksi: Jangan hapus jika ada yang sudah punya Kunjungan
        $bumilAktif = IbuHamil::whereIn('id', $ids)->has('kunjungans')->count();
        if ($bumilAktif > 0) {
            return redirect()->back()->with('error', "Tindakan Ditolak! {$bumilAktif} dari data yang dicentang sudah memiliki jejak rekam medis.");
        }

        try {
            IbuHamil::whereIn('id', $ids)->delete();
            return redirect()->route('kader.data.ibu-hamil.index')->with('success', 'Operasi Berhasil: ' . count($ids) . ' data telah dibersihkan secara masal.');
        } catch (\Throwable $e) {
            Log::error('KADER_BUMIL_BULK_ERROR: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Sistem Gagal menghapus data.');
        }
    }

    /**
     * 9. SYNC USER: Tarik Akun HP Warga Secara Manual
     */
    public function syncUser($id)
    {
        $ibuHamil = IbuHamil::findOrFail($id);
        $user = $this->findLinkedUser($ibuHamil->nik, $ibuHamil->nama_lengkap);
        
        if ($user) {
            $ibuHamil->user_id = $user->id;
            $ibuHamil->save();
            return redirect()->back()->with('success', 'Berhasil ditarik! Akun rekam medis ini sudah terhubung dengan HP milik Ibu (' . $user->name . ').');
        }

        return redirect()->back()->with('error', 'Gagal! Tidak ditemukan akun Warga dengan NIK tersebut. Pastikan Ibu sudah melakukan registrasi di portal Warga PosyanduCare.');
    }

    /**
     * 10. HELPER: Pencari Akun Lintas Tabel (SQL Query - Anti Memory Leak)
     */
    private function findLinkedUser($nik, $nama_lengkap)
    {
        $cleanNik  = preg_replace('/[^0-9]/', '', (string)$nik);
        $cleanName = trim((string)$nama_lengkap);

        if (empty($cleanNik) && empty($cleanName)) return null;

        // TAHAP 1: Cari berdasarkan NIK yang akurat
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

        // TAHAP 2: Fallback dengan Fuzzy Search Nama
        if (!empty($cleanName)) {
            $userByName = User::where('name', 'LIKE', "%{$cleanName}%")->first();
            if ($userByName) return $userByName;

            if (Schema::hasTable('profiles')) {
                $profileByName = Profile::where('full_name', 'LIKE', "%{$cleanName}%")->first();
                if ($profileByName && $profileByName->user) return $profileByName->user;
            }
        }

        return null;
    }
}