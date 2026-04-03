<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\IbuHamil;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class IbuHamilController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $filter = $request->get('filter', 'semua'); // semua / aktif / hampir_lahir

        $query = IbuHamil::query()
            ->when($search, fn($q) => $q
                ->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nik', 'like', "%{$search}%")
                ->orWhere('nama_suami', 'like', "%{$search}%")
            )
            ->latest();

        // Filter HPL
        if ($filter === 'hampir_lahir') {
            // HPL dalam 30 hari ke depan
            $query->whereBetween('hpl', [now(), now()->addDays(30)]);
        } elseif ($filter === 'aktif') {
            $query->where(fn($q) => $q->whereNull('hpl')->orWhere('hpl', '>=', now()));
        }

        $ibuHamils = $query->paginate(15);

        // Stats
        $stats = [
            'total'        => IbuHamil::count(),
            'trimester1'   => IbuHamil::whereRaw('TIMESTAMPDIFF(WEEK, hpht, CURDATE()) <= 12')->count(),
            'trimester2'   => IbuHamil::whereRaw('TIMESTAMPDIFF(WEEK, hpht, CURDATE()) BETWEEN 13 AND 27')->count(),
            'trimester3'   => IbuHamil::whereRaw('TIMESTAMPDIFF(WEEK, hpht, CURDATE()) >= 28')->count(),
            'hampir_lahir' => IbuHamil::whereBetween('hpl', [now(), now()->addDays(30)])->count(),
        ];

        return view('kader.data.ibu-hamil.index', compact('ibuHamils', 'search', 'filter', 'stats'));
    }

    public function create()
    {
        return view('kader.data.ibu-hamil.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nik'             => 'nullable|digits:16|unique:ibu_hamils,nik',
            'tempat_lahir'    => 'nullable|string|max:255',
            'tanggal_lahir'   => 'nullable|date|before_or_equal:today',
            'nama_suami'      => 'nullable|string|max:255',
            'alamat'          => 'required|string',
            'telepon_ortu'    => 'nullable|string|max:15',
            'hpht'            => 'nullable|date|before_or_equal:today',
            'hpl'             => 'nullable|date|after:today',
            'golongan_darah'  => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-',
            'riwayat_penyakit'=> 'nullable|string|max:255',
            'berat_badan'     => 'nullable|numeric|min:30|max:200',
            'tinggi_badan'    => 'nullable|numeric|min:100|max:250',
        ]);

        DB::beginTransaction();
        try {
            $kode = 'IBH-' . date('ym') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Auto HPL jika HPHT diisi tapi HPL kosong (HPL = HPHT + 280 hari)
            $hpl = $request->hpl;
            if ($request->hpht && !$hpl) {
                $hpl = \Carbon\Carbon::parse($request->hpht)->addDays(280)->format('Y-m-d');
            }

            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

            IbuHamil::create([
                'user_id'          => $linkedUser?->id,
                'kode_hamil'       => $kode,
                'nama_lengkap'     => $request->nama_lengkap,
                'nik'              => $request->nik,
                'tempat_lahir'     => $request->tempat_lahir,
                'tanggal_lahir'    => $request->tanggal_lahir,
                'nama_suami'       => $request->nama_suami,
                'alamat'           => $request->alamat,
                'telepon_ortu'     => $request->telepon_ortu,
                'hpht'             => $request->hpht,
                'hpl'              => $hpl,
                'golongan_darah'   => $request->golongan_darah,
                'riwayat_penyakit' => $request->riwayat_penyakit,
                'berat_badan'      => $request->berat_badan,
                'tinggi_badan'     => $request->tinggi_badan,
                'created_by'       => Auth::id(),
            ]);

            DB::commit();
            return redirect()->route('kader.data.ibu-hamil.index')
                ->with('success', 'Data Ibu Hamil berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan ibu hamil: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $ibuHamil = IbuHamil::findOrFail($id);
        return view('kader.data.ibu-hamil.show', compact('ibuHamil'));
    }

    public function edit($id)
    {
        $ibuHamil = IbuHamil::findOrFail($id);
        return view('kader.data.ibu-hamil.edit', compact('ibuHamil'));
    }

    public function update(Request $request, $id)
    {
        $ibuHamil = IbuHamil::findOrFail($id);

        $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nik'             => 'nullable|digits:16|unique:ibu_hamils,nik,' . $id,
            'tempat_lahir'    => 'nullable|string|max:255',
            'tanggal_lahir'   => 'nullable|date|before_or_equal:today',
            'nama_suami'      => 'nullable|string|max:255',
            'alamat'          => 'required|string',
            'telepon_ortu'    => 'nullable|string|max:15',
            'hpht'            => 'nullable|date|before_or_equal:today',
            'hpl'             => 'nullable|date',
            'golongan_darah'  => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-',
            'riwayat_penyakit'=> 'nullable|string|max:255',
            'berat_badan'     => 'nullable|numeric|min:30|max:200',
            'tinggi_badan'    => 'nullable|numeric|min:100|max:250',
        ]);

        try {
            $hpl = $request->hpl;
            if ($request->hpht && !$hpl) {
                $hpl = \Carbon\Carbon::parse($request->hpht)->addDays(280)->format('Y-m-d');
            }

            $linkedUser = $this->findLinkedUser($request->nik, $request->nama_lengkap);

            $ibuHamil->update([
                'user_id'          => $linkedUser?->id,
                'nama_lengkap'     => $request->nama_lengkap,
                'nik'              => $request->nik,
                'tempat_lahir'     => $request->tempat_lahir,
                'tanggal_lahir'    => $request->tanggal_lahir,
                'nama_suami'       => $request->nama_suami,
                'alamat'           => $request->alamat,
                'telepon_ortu'     => $request->telepon_ortu,
                'hpht'             => $request->hpht,
                'hpl'              => $hpl,
                'golongan_darah'   => $request->golongan_darah,
                'riwayat_penyakit' => $request->riwayat_penyakit,
                'berat_badan'      => $request->berat_badan,
                'tinggi_badan'     => $request->tinggi_badan,
            ]);

            return redirect()->route('kader.data.ibu-hamil.index')
                ->with('success', 'Data Ibu Hamil berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Gagal update ibu hamil: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        IbuHamil::findOrFail($id)->delete();
        return redirect()->route('kader.data.ibu-hamil.index')
            ->with('success', 'Data ibu hamil berhasil dihapus.');
    }

    // Radar Sapu Jagat
    private function findLinkedUser($nik, $nama)
    {
        if (!$nik && !$nama) return null;
        $cleanNik  = preg_replace('/[^0-9]/', '', (string)$nik);
        $cleanName = trim((string)$nama);

        foreach (User::all() as $user) {
            if ($cleanNik && ($user->nik ?? '') === $cleanNik) return $user;
            if ($cleanName && stripos($user->name, $cleanName) !== false) return $user;
        }

        if (Schema::hasTable('profiles')) {
            foreach (DB::table('profiles')->get() as $p) {
                if ($cleanNik && ($p->nik ?? '') === $cleanNik) return User::find($p->user_id);
                if ($cleanName && stripos($p->full_name ?? '', $cleanName) !== false) return User::find($p->user_id);
            }
        }
        return null;
    }
}