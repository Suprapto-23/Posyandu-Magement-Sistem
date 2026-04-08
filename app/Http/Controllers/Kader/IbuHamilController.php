<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\IbuHamil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IbuHamilController extends Controller
{
    // ================================================================
    // INDEX: Daftar ibu hamil + statistik trimester
    // ================================================================
    public function index(Request $request)
    {
        $search = $request->get('search');
        $filter = $request->get('filter', 'semua');

        $query = IbuHamil::query()->latest();

        if ($search) {
            $query->where(fn($q) =>
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%")
                  ->orWhere('nama_suami', 'like', "%$search%")
            );
        }

        if ($filter === 'aktif') {
            $query->where('status', 'aktif');
        } elseif ($filter === 'hampir_lahir') {
            $query->hampirLahir(30);
        }

        $ibuHamils = $query->paginate(15);

        // Statistik
        $all  = IbuHamil::aktif()->get();
        $stats = [
            'total'       => IbuHamil::count(),
            'trimester1'  => $all->filter(fn($i) => $i->trimester_angka === 1)->count(),
            'trimester2'  => $all->filter(fn($i) => $i->trimester_angka === 2)->count(),
            'trimester3'  => $all->filter(fn($i) => $i->trimester_angka === 3)->count(),
            'hampir_lahir'=> IbuHamil::aktif()->hampirLahir(30)->count(),
        ];

        return view('kader.data.ibu-hamil.index', compact(
            'ibuHamils', 'stats', 'search', 'filter'
        ));
    }

    // ================================================================
    // CREATE
    // ================================================================
    public function create()
    {
        return view('kader.data.ibu-hamil.create');
    }

    // ================================================================
    // STORE: Kader simpan data identitas + fisik dasar
    // ================================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nik'             => 'nullable|digits:16|unique:ibu_hamils,nik',
            'tempat_lahir'    => 'nullable|string|max:255',
            'tanggal_lahir'   => 'nullable|date|before:today',
            'nama_suami'      => 'nullable|string|max:255',
            'alamat'          => 'required|string',
            'telepon_ortu'    => 'nullable|string|max:15',
            'golongan_darah'  => 'nullable|string|max:3',
            'hpht'            => 'nullable|date|before_or_equal:today',
            'hpl'             => 'nullable|date',
            'riwayat_penyakit'=> 'nullable|string|max:255',
            'berat_badan'     => 'nullable|numeric|min:20|max:200',
            'tinggi_badan'    => 'nullable|numeric|min:100|max:250',
        ]);

        try {
            // Hitung IMT jika BB & TB tersedia
            $imt = null;
            if (!empty($validated['berat_badan']) && !empty($validated['tinggi_badan'])) {
                $tinggiM = $validated['tinggi_badan'] / 100;
                $imt = round($validated['berat_badan'] / ($tinggiM * $tinggiM), 2);
            }

            $kode = 'IBH-' . date('ym') . rand(1000, 9999);

            IbuHamil::create([
                ...$validated,
                'kode_hamil' => $kode,
                'imt'        => $imt,
                'status'     => 'aktif',
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('kader.data.ibu-hamil.index')
                ->with('success', 'Data ibu hamil berhasil disimpan. Bidan akan melakukan pemeriksaan mendalam.');

        } catch (\Exception $e) {
            Log::error('Gagal simpan ibu hamil: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // ================================================================
    // SHOW: Detail ibu hamil + riwayat pemeriksaan bidan
    // ================================================================
    public function show($id)
    {
        $ibuHamil = IbuHamil::with(['pemeriksaans.bidan', 'pemeriksaan_terakhir'])
            ->findOrFail($id);

        return view('kader.data.ibu-hamil.show', compact('ibuHamil'));
    }

    // ================================================================
    // EDIT: Kader hanya bisa edit data identitas & fisik dasar
    // TIDAK BISA edit hasil pemeriksaan bidan
    // ================================================================
    public function edit($id)
    {
        $ibuHamil = IbuHamil::findOrFail($id);
        return view('kader.data.ibu-hamil.edit', compact('ibuHamil'));
    }

    // ================================================================
    // UPDATE: Kader update data identitas + fisik dasar saja
    // ================================================================
    public function update(Request $request, $id)
    {
        $ibuHamil = IbuHamil::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nik'             => 'nullable|digits:16|unique:ibu_hamils,nik,' . $id,
            'tempat_lahir'    => 'nullable|string|max:255',
            'tanggal_lahir'   => 'nullable|date|before:today',
            'nama_suami'      => 'nullable|string|max:255',
            'alamat'          => 'required|string',
            'telepon_ortu'    => 'nullable|string|max:15',
            'golongan_darah'  => 'nullable|string|max:3',
            'hpht'            => 'nullable|date|before_or_equal:today',
            'hpl'             => 'nullable|date',
            'riwayat_penyakit'=> 'nullable|string|max:255',
            'berat_badan'     => 'nullable|numeric|min:20|max:200',
            'tinggi_badan'    => 'nullable|numeric|min:100|max:250',
        ]);

        try {
            $imt = null;
            if (!empty($validated['berat_badan']) && !empty($validated['tinggi_badan'])) {
                $tinggiM = $validated['tinggi_badan'] / 100;
                $imt = round($validated['berat_badan'] / ($tinggiM * $tinggiM), 2);
            }

            $ibuHamil->update([...$validated, 'imt' => $imt]);

            return redirect()->route('kader.data.ibu-hamil.index')
                ->with('success', 'Data berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    // ================================================================
    // DESTROY: Hanya bisa hapus jika belum ada pemeriksaan bidan
    // ================================================================
    public function destroy($id)
    {
        $ibuHamil = IbuHamil::withCount('pemeriksaans')->findOrFail($id);

        if ($ibuHamil->pemeriksaans_count > 0) {
            return back()->with('error',
                'Tidak dapat menghapus: ibu hamil ini sudah memiliki ' .
                $ibuHamil->pemeriksaans_count . ' riwayat pemeriksaan dari bidan.'
            );
        }

        $ibuHamil->delete();
        return redirect()->route('kader.data.ibu-hamil.index')
            ->with('success', 'Data ibu hamil berhasil dihapus.');
    }
    // ================================================================
    // FITUR BARU: Hapus Banyak Data Sekaligus (Bulk Delete)
    // ================================================================
    public function bulkDelete(\Illuminate\Http\Request $request)
    {
        $ids = $request->ids;
        if (!$ids || count($ids) == 0) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih untuk dihapus!');
        }

        // Proteksi: Jangan hapus yang sudah punya rekam medis bidan
        $ibuHamils = IbuHamil::withCount('pemeriksaans')->whereIn('id', $ids)->get();
        $deleted = 0;
        $failed = 0;

        foreach ($ibuHamils as $ibu) {
            if ($ibu->pemeriksaans_count == 0) {
                $ibu->delete();
                $deleted++;
            } else {
                $failed++;
            }
        }

        if ($failed > 0) {
            return redirect()->back()->with('warning', "$deleted data berhasil dihapus. $failed data dilewati karena sudah memiliki riwayat pemeriksaan dari Bidan.");
        }

        return redirect()->back()->with('success', "$deleted Data ibu hamil berhasil dihapus secara permanen!");
    }

    // ================================================================
    // FITUR BARU: Sinkronisasi Akun Web Warga
    // ================================================================
    public function syncUser($id)
    {
        $ibuHamil = IbuHamil::findOrFail($id);
        
        // Cari akun berdasarkan NIK Ibu
        $user = $this->findLinkedUser($ibuHamil->nik, $ibuHamil->nama_lengkap);
        
        if ($user) {
            $ibuHamil->user_id = $user->id;
            $ibuHamil->save();
            return redirect()->back()->with('success', 'Berhasil ditarik! Akun ibu hamil ini sudah terhubung dengan HP miliknya (' . $user->name . ').');
        }

        return redirect()->back()->with('error', 'Gagal! Tidak ditemukan akun Warga dengan NIK tersebut. Pastikan Ibu sudah melakukan registrasi di aplikasi Warga.');
    }

    // Helper Pencari Akun Lintas Tabel (Users & Profiles)
    private function findLinkedUser($nik, $nama_lengkap)
    {
        $cleanNik  = preg_replace('/[^0-9]/', '', (string)$nik);

        if (empty($cleanNik)) return null;

        // Cek di tabel users
        $user = User::where('nik', $cleanNik)->orWhere('username', $cleanNik)->first();
        if ($user) return $user;

        // Cek di tabel profiles
        if (\Illuminate\Support\Facades\Schema::hasTable('profiles')) {
            $profile = \Illuminate\Support\Facades\DB::table('profiles')
                        ->where('nik', $cleanNik)
                        ->orWhere('no_ktp', $cleanNik)
                        ->first();
            if ($profile) return User::find($profile->user_id);
        }

        return null;
    }
}