<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Lansia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class LansiaController extends Controller
{
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
            
        // Deteksi jika request dari Live Search (AJAX)
        if ($request->ajax()) {
            return view('kader.data.lansia.index', compact('lansias', 'search'))->render();
        }
            
        return view('kader.data.lansia.index', compact('lansias', 'search'));
    }

    public function create()
    {
        return view('kader.data.lansia.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nik'             => 'required|numeric|digits:16|unique:lansias,nik',
            'jenis_kelamin'   => 'required|in:L,P',
            'tempat_lahir'    => 'required|string|max:100',
            'tanggal_lahir'   => 'required|date|before_or_equal:today',
            'alamat'          => 'required|string',
            'penyakit_bawaan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Radar Sapu Jagat
            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

            // 2. Generate Kode Unik
            $kode = 'LNS-' . date('ym') . rand(1000, 9999);

            // 3. Simpan Data
            Lansia::create([
                'user_id'         => $linkedUser ? $linkedUser->id : null,
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

            if ($linkedUser) {
                return redirect()->route('kader.data.lansia.index')
                    ->with('success', 'Data Lansia berhasil disimpan dan tersinkronisasi otomatis dengan akun Warga.');
            } else {
                return redirect()->route('kader.data.lansia.index')
                    ->with('warning', "Data berhasil disimpan. Sistem tidak dapat menemukan profil Warga dengan NIK {$request->nik}. Silakan hubungi Administrator untuk mendaftarkan akun warga tersebut.");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan lansia: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memproses data: ' . $e->getMessage());
        }
    }

    public function show($id) 
    {
        $lansia = Lansia::with(['kunjungans' => function($q) {
            $q->latest()->take(10);
        }])->findOrFail($id);
        
        $usia = \Carbon\Carbon::parse($lansia->tanggal_lahir)->age;

        return view('kader.data.lansia.show', compact('lansia', 'usia'));
    }

    public function edit($id)
    {
        $lansia = Lansia::findOrFail($id);
        return view('kader.data.lansia.edit', compact('lansia'));
    }

    public function update(Request $request, $id)
    {
        $lansia = Lansia::findOrFail($id);
            
        $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nik'             => 'required|numeric|digits:16|unique:lansias,nik,' . $id,
            'jenis_kelamin'   => 'required|in:L,P',
            'tempat_lahir'    => 'required|string|max:100',
            'tanggal_lahir'   => 'required|date|before_or_equal:today',
            'alamat'          => 'required|string',
            'penyakit_bawaan' => 'nullable|string',
        ]);

        try {
            // Radar Sapu Jagat (Cek Ulang)
            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

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

            if ($linkedUser) {
                return redirect()->route('kader.data.lansia.index')
                    ->with('success', 'Data Lansia berhasil diperbarui dan tersinkronisasi.');
            } else {
                return redirect()->route('kader.data.lansia.index')
                    ->with('warning', "Data diperbarui. Sistem tidak dapat menemukan profil Warga dengan NIK {$request->nik}. Silakan hubungi Administrator untuk membuatkan akun.");
            }

        } catch (\Exception $e) {
            Log::error('Gagal update lansia: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $lansia = Lansia::findOrFail($id);
        
        if ($lansia->kunjungans()->count() > 0) {
            return back()->with('error', 'Gagal menghapus: Pasien lansia ini memiliki rekam medis/kunjungan yang tersimpan di sistem.');
        }
            
        $lansia->delete();
        return redirect()->route('kader.data.lansia.index')->with('success', 'Data lansia berhasil dihapus secara permanen.');
    }

    /**
     * 🚀 RADAR SAPU JAGAT
     */
    private function findLinkedUser($nik, $nama_lengkap)
    {
        $cleanNik = preg_replace('/[^0-9]/', '', (string)$nik);
        $cleanName = trim((string)$nama_lengkap);

        // 1. Geledah Users
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

        // 2. Geledah Profiles
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