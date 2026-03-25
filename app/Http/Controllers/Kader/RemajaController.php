<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Remaja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class RemajaController extends Controller
{
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
            
        // Deteksi jika request dari Live Search (AJAX)
        if ($request->ajax()) {
            return view('kader.data.remaja.index', compact('remajas', 'search'))->render();
        }
            
        return view('kader.data.remaja.index', compact('remajas', 'search'));
    }

    public function create()
    {
        return view('kader.data.remaja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'nik'           => 'required|numeric|digits:16|unique:remajas,nik',
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
            // 1. Radar Sapu Jagat (Cari Akun Warga)
            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

            // 2. Generate Kode Unik Remaja
            $kode = 'RMJ-' . date('ym') . rand(1000, 9999);

            // 3. Simpan Data
            Remaja::create([
                'user_id'       => $linkedUser ? $linkedUser->id : null,
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

            if ($linkedUser) {
                return redirect()->route('kader.data.remaja.index')
                    ->with('success', 'Data Remaja berhasil disimpan dan tersinkronisasi otomatis dengan akun Warga.');
            } else {
                return redirect()->route('kader.data.remaja.index')
                    ->with('warning', "Data berhasil disimpan. Sistem tidak dapat menemukan profil Warga dengan NIK {$request->nik}. Silakan hubungi Administrator untuk mendaftarkan akun warga tersebut.");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan remaja: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memproses data: ' . $e->getMessage());
        }
    }

    public function show($id) 
    {
        $remaja = Remaja::with(['kunjungans' => function($q) {
            $q->latest()->take(10);
        }])->findOrFail($id);
        
        $usia = \Carbon\Carbon::parse($remaja->tanggal_lahir)->age;

        return view('kader.data.remaja.show', compact('remaja', 'usia'));
    }

    public function edit($id)
    {
        $remaja = Remaja::findOrFail($id);
        return view('kader.data.remaja.edit', compact('remaja'));
    }

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

        try {
            // Radar Sapu Jagat (Cari Ulang jika NIK/Nama Berubah)
            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

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

            if ($linkedUser) {
                return redirect()->route('kader.data.remaja.index')
                    ->with('success', 'Data Remaja berhasil diperbarui dan tersinkronisasi.');
            } else {
                return redirect()->route('kader.data.remaja.index')
                    ->with('warning', "Data diperbarui. Sistem tidak dapat menemukan profil Warga dengan NIK {$request->nik}. Silakan hubungi Administrator untuk membuatkan akun.");
            }

        } catch (\Exception $e) {
            Log::error('Gagal update remaja: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $remaja = Remaja::findOrFail($id);
        
        if ($remaja->kunjungans()->count() > 0) {
            return back()->with('error', 'Gagal menghapus: Pasien remaja ini memiliki rekam medis/kunjungan yang tersimpan di sistem.');
        }
            
        $remaja->delete();
        return redirect()->route('kader.data.remaja.index')->with('success', 'Data remaja berhasil dihapus secara permanen dari direktori.');
    }

    /**
     * 🚀 RADAR SAPU JAGAT (Otomatis Mencari Afiliasi Akun)
     */
    private function findLinkedUser($nik, $nama_lengkap)
    {
        $cleanNik = preg_replace('/[^0-9]/', '', (string)$nik);
        $cleanName = trim((string)$nama_lengkap);

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
}