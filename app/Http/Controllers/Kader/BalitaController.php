<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BalitaController extends Controller
{
    /**
     * Menampilkan daftar Bayi & Balita
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = \App\Models\Balita::with('pemeriksaan_terakhir')->latest('created_at');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nama_ibu', 'like', "%{$search}%");
            });
        }

        // PISAHKAN BERDASARKAN UMUR BULAN MENGGUNAKAN RAW SQL
        // Bayi: 0 - 11 Bulan
        $bayis = (clone $query)->whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) < 12')->get();
        
        // Balita: 12 Bulan ke atas
        $balitas = (clone $query)->whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) >= 12')->get();

        return view('kader.data.balita.index', compact('bayis', 'balitas', 'search'));
    }

    public function create()
    {
        return view('kader.data.balita.create');
    }

    /**
     * Menyimpan data pendaftaran baru (Terintegrasi Validasi Usia Maksimal 59 Bulan)
     */
    public function store(Request $request)
    {
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

        // 🔥 VALIDASI BACKEND STRICT: Usia Maksimal 59 Bulan (0 - 4 Tahun 11 Bulan)
        $tanggalLahir = Carbon::parse($request->tanggal_lahir);
        $usiaBulan = $tanggalLahir->diffInMonths(now());
        
        if ($usiaBulan >= 60) {
            $tahun = floor($usiaBulan / 12);
            $bulan = $usiaBulan % 12;
            $teksUsia = $bulan > 0 ? "{$tahun} Tahun {$bulan} Bulan" : "{$tahun} Tahun";
            
            return back()->withInput()->with('error', "Registrasi Ditolak! Usia anak terdeteksi {$teksUsia}. Sistem membatasi pendaftaran modul ini maksimal 59 Bulan. Silakan daftarkan di Modul Remaja/Umum.");
        }

        DB::beginTransaction();
        try {
            // Generate Kode Unik Otomatis
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

            // Sinkronisasi Otomatis menggunakan Radar Sapu Jagat
            $linkedUser = $this->findLinkedUser($request->nik_ibu, $request->nama_ibu);
            if ($linkedUser) {
                $balita->user_id = $linkedUser->id;
                $balita->save(); 
            }

            DB::commit();
            
            if ($linkedUser) {
                return redirect()->route('kader.data.balita.index')
                    ->with('success', 'Registrasi Sukses! Data balita tersimpan dan telah disinkronisasikan secara otomatis dengan aplikasi HP Ibu kandung.');
            } else {
                return redirect()->route('kader.data.balita.index')
                    ->with('warning', "Registrasi Tersimpan! Namun sistem tidak menemukan Akun Warga dengan NIK Ibu {$request->nik_ibu}. Anda dapat menyinkronkannya nanti setelah Ibu mendaftar aplikasi.");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Fatal Error Create Balita: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Kegagalan Server: Data gagal dienkripsi ke database. Periksa kembali format inputan Anda.');
        }
    }

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

    public function edit($id)
    {
        $balita = Balita::findOrFail($id);
        return view('kader.data.balita.edit', compact('balita'));
    }

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

        // 🔥 VALIDASI BACKEND STRICT: Usia Maksimal 59 Bulan (Saat diupdate)
        $tanggalLahir = Carbon::parse($request->tanggal_lahir);
        $usiaBulan = $tanggalLahir->diffInMonths(now());
        
        if ($usiaBulan >= 60) {
            return back()->withInput()->with('error', "Pembaruan Ditolak! Anda mencoba mengatur tanggal lahir menjadi di atas 5 Tahun (>= 60 Bulan). Anak ini harus dimutasikan ke database lain jika sudah melewati umur balita.");
        }

        try {
            $balita->update($request->except(['_token', '_method', 'user_id']));

            // Sinkronisasi ulang saat diupdate (Mungkin NIK Ibunya salah ketik dan baru diperbaiki)
            $linkedUser = $this->findLinkedUser($request->nik_ibu, $request->nama_ibu);
            $balita->user_id = $linkedUser ? $linkedUser->id : null;
            $balita->save(); // Force save bypass fillable

            if ($linkedUser) {
                return redirect()->route('kader.data.balita.index')
                    ->with('success', 'Pembaruan Berhasil! Data balita terkoreksi dan afiliasi akun warga tetap terhubung.');
            } else {
                return redirect()->route('kader.data.balita.index')
                    ->with('warning', 'Pembaruan Berhasil! Namun sinkronisasi akun orang tua terputus/tidak ditemukan dengan NIK baru yang dimasukkan.');
            }
                
        } catch (\Exception $e) {
            Log::error('Fatal Error Update Balita: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Kegagalan Server: Gagal memperbarui data.');
        }
    }

    public function destroy($id)
    {
        $balita = Balita::findOrFail($id);
        
        // Pengecekan Relasi: Cegah penghapusan jika anak sudah punya riwayat berobat/hadir
        if ($balita->kunjungans()->count() > 0) {
            return back()->with('error', 'Tindakan Dilarang! Balita ini memiliki jejak rekam medis atau kunjungan aktif di sistem. Penghapusan akan merusak integritas laporan.');
        }
        
        $balita->delete();
        return redirect()->route('kader.data.balita.index')->with('success', 'Aksi Final: Data pendaftaran balita berhasil dihapus secara permanen dari server.');
    }

    /**
     * 🚀 RADAR SAPU JAGAT (Otomatis Mencari Afiliasi Akun)
     */
    private function findLinkedUser($nik_ibu, $nama_ibu)
    {
        $cleanNik = preg_replace('/[^0-9]/', '', (string)$nik_ibu);
        $cleanName = trim((string)$nama_ibu);

        // 1. Geledah Tabel Users
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

        // 2. Geledah Tabel Profiles
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

    /**
     * Fungsi Tarik Akun Warga Manual dari Tombol di UI
     */
    public function syncUser($id)
    {
        $balita = Balita::findOrFail($id);
        
        $user = $this->findLinkedUser($balita->nik_ibu, $balita->nama_ibu);
        
        if ($user) {
            $balita->user_id = $user->id;
            $balita->save();
            return redirect()->back()->with('success', 'Integrasi Terkunci! Akun anak ini berhasil dihubungkan dengan perangkat Ibu (' . $user->name . ').');
        }

        return redirect()->back()->with('error', 'Sistem tidak dapat menemukan pengguna aplikasi dengan NIK Ibu tersebut. Pastikan Ibu telah membuat akun terlebih dahulu.');
    }

    /**
     * Fitur Hapus Banyak Data Sekaligus (Bulk Delete)
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || count($ids) == 0) {
            return redirect()->back()->with('error', 'Misi Dibatalkan: Tidak ada data balita yang dicentang untuk dihapus.');
        }

        Balita::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Pembersihan Sukses: ' . count($ids) . ' data registrasi balita berhasil dihapus massal.');
    }
}