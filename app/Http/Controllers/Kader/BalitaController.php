<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BalitaController extends Controller
{
    /**
     * =========================================================================
     * 1. MENAMPILKAN DATABASE (INDEX) DENGAN FILTER UMUR CERDAS
     * =========================================================================
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Balita::with('pemeriksaan_terakhir')->latest('created_at');

        // Pencarian Instan Server-Side
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nama_ibu', 'like', "%{$search}%");
            });
        }

        // PISAHKAN BERDASARKAN UMUR BULAN MENGGUNAKAN RAW SQL
        // 1. Bayi: 0 sampai 11 Bulan
        $bayis = (clone $query)->whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) < 12')->get();
        
        // 2. Balita: 12 sampai 59 Bulan (Anak > 59 Bulan otomatis terfilter keluar)
        $balitas = (clone $query)->whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) BETWEEN 12 AND 59')->get();

        return view('kader.data.balita.index', compact('bayis', 'balitas', 'search'));
    }

    /**
     * =========================================================================
     * 2. HALAMAN FORM REGISTRASI
     * =========================================================================
     */
    public function create()
    {
        return view('kader.data.balita.create');
    }

    /**
     * =========================================================================
     * 3. SIMPAN DATA REGISTRASI (DENGAN PROTEKSI UMUR)
     * =========================================================================
     */
    public function store(Request $request)
    {
        // Validasi Aturan Form
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'nik'           => 'nullable|numeric|digits:16|unique:balitas,nik',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'nik_ibu'       => 'required|numeric|digits:16',
            'nama_ibu'      => 'required|string|max:255',
            'nik_ayah'      => 'nullable|numeric|digits:16',
            'nama_ayah'     => 'nullable|string|max:255',
            'alamat'        => 'required|string',
            'berat_lahir'   => 'nullable|numeric|min:0',
            'panjang_lahir' => 'nullable|numeric|min:0',
        ], [
            'nik.unique'          => 'NIK anak ini sudah terdaftar di sistem kami.',
            'nik_ibu.digits'      => 'Format NIK Ibu harus berisi tepat 16 digit angka.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini (masa depan).',
        ]);

        // 🔥 VALIDASI BACKEND STRICT: Blokir Anak Usia >= 60 Bulan
        $tanggalLahir = Carbon::parse($request->tanggal_lahir);
        $usiaBulan = $tanggalLahir->diffInMonths(now());
        
        if ($usiaBulan >= 60) {
            $tahun = floor($usiaBulan / 12);
            $bulan = $usiaBulan % 12;
            $teksUsia = $bulan > 0 ? "{$tahun} Tahun {$bulan} Bulan" : "{$tahun} Tahun";
            
            return back()->withInput()->with('error', "Registrasi Ditolak! Usia anak terdeteksi {$teksUsia}. Sistem membatasi pendaftaran modul ini maksimal 59 Bulan.");
        }

        DB::beginTransaction();
        try {
            // Generate Kode Unik
            $kode = 'BLT-' . date('ym') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $balita = Balita::create([
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
                'created_by'    => Auth::id(),
            ]);

            // Deteksi Akun Warga Otomatis
            $linkedUser = $this->findLinkedUser($request->nik_ibu, $request->nama_ibu);
            if ($linkedUser) {
                $balita->user_id = $linkedUser->id;
                $balita->save(); 
            }

            DB::commit();
            
            if ($linkedUser) {
                return redirect()->route('kader.data.balita.index')
                    ->with('success', 'Registrasi Sukses! Data anak tersimpan dan tersinkronisasi dengan akun Ibu di aplikasi Warga.');
            } else {
                return redirect()->route('kader.data.balita.index')
                    ->with('warning', "Registrasi Tersimpan! Namun belum ada akun Warga dengan NIK Ibu {$request->nik_ibu}. Anda dapat menyinkronkannya nanti jika Ibu sudah mendaftar.");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Fatal Error Create Balita: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Kegagalan Server: Gagal menyimpan ke pangkalan data.');
        }
    }

    /**
     * =========================================================================
     * 4. BUKA BUKU KIA (DETAIL)
     * =========================================================================
     */
    public function show($id) 
    {
        $balita = Balita::with(['kunjungans' => function($q) {
                $q->with(['petugas', 'pemeriksaan'])->latest()->take(10);
            }, 'user'])->findOrFail($id);
        
        $tgl_lahir = Carbon::parse($balita->tanggal_lahir);
        $diff = $tgl_lahir->diff(now());
        
        $userTerhubung = $balita->user;
        if (!$userTerhubung) {
            $userTerhubung = $this->findLinkedUser($balita->nik_ibu, $balita->nama_ibu);
        }

        return view('kader.data.balita.show', [
            'balita'        => $balita,
            'usia_tahun'    => $diff->y,
            'usia_bulan'    => $diff->m,
            'usia_hari'     => $diff->d,
            'sisa_bulan'    => $diff->m,
            'userTerhubung' => $userTerhubung
        ]);
    }

    /**
     * =========================================================================
     * 5. HALAMAN EDIT DATA
     * =========================================================================
     */
    public function edit($id)
    {
        $balita = Balita::findOrFail($id);
        return view('kader.data.balita.edit', compact('balita'));
    }

    /**
     * =========================================================================
     * 6. PROSES SIMPAN PERUBAHAN DATA
     * =========================================================================
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
            'nik_ayah'      => 'nullable|numeric|digits:16',
            'nama_ayah'     => 'nullable|string|max:255',
            'alamat'        => 'required|string',
            'berat_lahir'   => 'nullable|numeric|min:0',
            'panjang_lahir' => 'nullable|numeric|min:0',
        ]);

        // 🔥 VALIDASI UMUR SAAT UPDATE
        $tanggalLahir = Carbon::parse($request->tanggal_lahir);
        $usiaBulan = $tanggalLahir->diffInMonths(now());
        
        if ($usiaBulan >= 60) {
            return back()->withInput()->with('error', "Pembaruan Ditolak! Anda mengatur tanggal lahir menjadi di atas 5 Tahun. Silakan mutasikan anak ini ke modul lain.");
        }

        DB::beginTransaction();
        try {
            $balita->update($request->except(['_token', '_method', 'user_id']));

            // Cek ulang sinkronisasi akun, siapa tahu NIK Ibu baru saja dikoreksi
            $linkedUser = $this->findLinkedUser($request->nik_ibu, $request->nama_ibu);
            $balita->user_id = $linkedUser ? $linkedUser->id : null;
            $balita->save();

            DB::commit();

            if ($linkedUser) {
                return redirect()->route('kader.data.balita.index')->with('success', 'Pembaruan Berhasil! Data balita terkoreksi dan afirmasi akun warga terhubung.');
            } else {
                return redirect()->route('kader.data.balita.index')->with('warning', 'Pembaruan Berhasil! Namun sinkronisasi akun Ibu terputus (NIK tidak ditemukan di sistem Warga).');
            }
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Fatal Error Update Balita: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Kegagalan Server: Gagal memperbarui data.');
        }
    }

    /**
     * =========================================================================
     * 7. HAPUS SATU DATA (DENGAN PROTEKSI RELASI MEDIS)
     * =========================================================================
     */
    public function destroy($id)
    {
        $balita = Balita::findOrFail($id);
        
        // Proteksi Data Rekam Medis
        if ($balita->kunjungans()->count() > 0) {
            return back()->with('error', 'Tindakan Dilarang! Anak ini sudah memiliki rekam medis / riwayat kunjungan. Penghapusan akan merusak laporan Posyandu.');
        }
        
        $balita->delete();
        return redirect()->route('kader.data.balita.index')->with('success', 'Aksi Final: Data pendaftaran anak berhasil dihapus permanen.');
    }

    /**
     * =========================================================================
     * 8. HAPUS MASAL (BULK DELETE DENGAN PROTEKSI)
     * =========================================================================
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || count($ids) == 0) {
            return redirect()->back()->with('error', 'Misi Dibatalkan: Tidak ada data yang dicentang untuk dihapus.');
        }

        // Cari tahu apakah dari ID yang dicentang ada anak yang sudah berobat
        $anakAktif = Balita::whereIn('id', $ids)->has('kunjungans')->count();
        
        if ($anakAktif > 0) {
            return redirect()->back()->with('error', "Tindakan Ditolak! $anakAktif dari anak yang Anda centang sudah memiliki jejak rekam medis. Hapus centang pada anak tersebut untuk melanjutkan.");
        }

        DB::beginTransaction();
        try {
            Balita::whereIn('id', $ids)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Pembersihan Sukses: ' . count($ids) . ' data registrasi berhasil dihapus massal.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Fatal Error Bulk Delete Balita: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Kegagalan Server: Terjadi konflik saat menghapus data.');
        }
    }

    /**
     * =========================================================================
     * 9. FITUR TRIGGER MANUAL UNTUK SINKRONISASI AKUN
     * =========================================================================
     */
    public function syncUser($id)
    {
        $balita = Balita::findOrFail($id);
        $user = $this->findLinkedUser($balita->nik_ibu, $balita->nama_ibu);
        
        if ($user) {
            $balita->user_id = $user->id;
            $balita->save();
            return redirect()->back()->with('success', "Integrasi Terkunci! Akun anak berhasil dihubungkan dengan perangkat Warga ({$user->name}).");
        }

        return redirect()->back()->with('error', 'Pencarian Gagal. Sistem tidak menemukan pengguna aplikasi dengan NIK Ibu tersebut.');
    }

    /**
     * =========================================================================
     * 10. ENGINE "RADAR SAPU JAGAT" UNTUK DETEKSI AKUN
     * =========================================================================
     */
    private function findLinkedUser($nik_ibu, $nama_ibu)
    {
        $cleanNik = preg_replace('/[^0-9]/', '', (string)$nik_ibu);
        $cleanName = trim((string)$nama_ibu);

        // Geledah Tabel Users
        $users = User::all();
        foreach($users as $user) {
            if (!empty($cleanNik)) {
                if (($user->nik ?? '') === $cleanNik) return $user;
                if (($user->username ?? '') === $cleanNik) return $user;
                if (($user->email ?? '') === $cleanNik) return $user;
            }
            if (!empty($cleanName)) {
                if (stripos($user->name, $cleanName) !== false) return $user;
                if (stripos($user->username ?? '', $cleanName) !== false) return $user;
            }
        }

        // Geledah Tabel Profiles (Jika aplikasi Warga Anda memakai tabel profil terpisah)
        if (Schema::hasTable('profiles')) {
            $profiles = DB::table('profiles')->get();
            foreach($profiles as $p) {
                if (!empty($cleanNik)) {
                    if (($p->nik ?? '') === $cleanNik) return User::find($p->user_id);
                    if (($p->no_ktp ?? '') === $cleanNik) return User::find($p->user_id);
                }
                if (!empty($cleanName)) {
                    if (stripos($p->full_name ?? '', $cleanName) !== false) return User::find($p->user_id);
                }
            }
        }

        return null;
    }
}