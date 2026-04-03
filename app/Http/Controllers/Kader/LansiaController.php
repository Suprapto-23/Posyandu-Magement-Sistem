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
            'berat_badan'     => 'nullable|numeric|min:1|max:300',
            'tinggi_badan'    => 'nullable|numeric|min:50|max:250',
            'kemandirian'     => 'nullable|in:A,B,C',
        ]);

        try {
            DB::transaction(function () use ($request, &$linkedUser) {
                $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);
                $kode = 'LNS-' . date('ym') . rand(1000, 9999);

                // Hitung IMT otomatis jika berat & tinggi diisi
                $imt = null;
                if ($request->berat_badan && $request->tinggi_badan) {
                    $tinggiM = $request->tinggi_badan / 100;
                    $imt = round($request->berat_badan / ($tinggiM * $tinggiM), 2);
                }

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
                    'berat_badan'     => $request->berat_badan,
                    'tinggi_badan'    => $request->tinggi_badan,
                    'imt'             => $imt, // IMT Tersimpan
                    'kemandirian'     => $request->kemandirian, // Kemandirian Tersimpan
                    'created_by'      => Auth::id(),
                ]);
            });

            $msg = $linkedUser
                ? 'Data Lansia berhasil disimpan dan tersinkronisasi otomatis dengan akun Warga.'
                : "Data berhasil disimpan. Sistem tidak dapat menemukan profil Warga dengan NIK {$request->nik}.";

            return redirect()->route('kader.data.lansia.index')
                ->with($linkedUser ? 'success' : 'warning', $msg);

        } catch (\Exception $e) {
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
            'berat_badan'     => 'nullable|numeric|min:1|max:300',
            'tinggi_badan'    => 'nullable|numeric|min:50|max:250',
            'kemandirian'     => 'nullable|in:A,B,C',
        ]);

        try {
            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

            // Hitung ulang IMT
            $imt = null;
            if ($request->berat_badan && $request->tinggi_badan) {
                $tinggiM = $request->tinggi_badan / 100;
                $imt = round($request->berat_badan / ($tinggiM * $tinggiM), 2);
            }

            $lansia->update([
                'user_id'         => $linkedUser ? $linkedUser->id : null,
                'nik'             => $request->nik,
                'nama_lengkap'    => $request->nama_lengkap,
                'tempat_lahir'    => $request->tempat_lahir,
                'tanggal_lahir'   => $request->tanggal_lahir,
                'jenis_kelamin'   => $request->jenis_kelamin,
                'alamat'          => $request->alamat,
                'penyakit_bawaan' => $request->penyakit_bawaan,
                'berat_badan'     => $request->berat_badan,
                'tinggi_badan'    => $request->tinggi_badan,
                'imt'             => $imt, // Update IMT
                'kemandirian'     => $request->kemandirian, // Update Kemandirian
            ]);

            $msg = $linkedUser
                ? 'Data Lansia berhasil diperbarui dan tersinkronisasi.'
                : "Data diperbarui. Sistem tidak dapat menemukan profil Warga dengan NIK {$request->nik}.";

            return redirect()->route('kader.data.lansia.index')
                ->with($linkedUser ? 'success' : 'warning', $msg);

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

    private function findLinkedUser($nik, $nama_lengkap)
    {
        $cleanNik  = preg_replace('/[^0-9]/', '', (string)$nik);
        $cleanName = trim((string)$nama_lengkap);

        $users = User::all();
        foreach ($users as $user) {
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

        if (Schema::hasTable('profiles')) {
            $profiles = DB::table('profiles')->get();
            foreach ($profiles as $p) {
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