<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Imunisasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ImunisasiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Imunisasi::with(['kunjungan.pasien', 'kunjungan.petugas'])->latest('tanggal_imunisasi');
        
        if ($search) {
            $query->where('vaksin', 'like', "%{$search}%")
                  ->orWhereHas('kunjungan.pasien', function($q) use ($search) {
                      $q->where('nama_lengkap', 'like', "%{$search}%");
                  });
        }
        
        $imunisasis = $query->paginate(10)->withQueryString();
        return view('bidan.imunisasi.index', compact('imunisasis', 'search'));
    }

    public function create()
    {
        // BUG FIXED: Menambahkan IbuHamil (Vaksin TT) & mengambil data kunjungan terbaru (H-7) agar antrian valid.
        $kunjungans = Kunjungan::with('pasien')
            ->whereIn('pasien_type', ['App\Models\Balita', 'App\Models\Remaja', 'App\Models\IbuHamil'])
            ->whereDate('tanggal_kunjungan', '>=', Carbon::today()->subDays(7))
            ->latest('tanggal_kunjungan')
            ->get();
            
        return view('bidan.imunisasi.create', compact('kunjungans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kunjungan_id'      => 'required|exists:kunjungans,id',
            'jenis_imunisasi'   => 'required|string|max:255',
            'vaksin'            => 'required|string|max:255',
            'dosis'             => 'required|string|max:50',
            'tanggal_imunisasi' => 'required|date',
        ]);

        Imunisasi::create($request->all());

        return redirect()->route('bidan.imunisasi.index')
            ->with('success', 'Data Vaksinasi berhasil dicatat dan disinkronkan ke rekam medis pasien!');
    }

    public function show($id)
    {
        $imunisasi = Imunisasi::with(['kunjungan.pasien', 'kunjungan.petugas'])->findOrFail($id);
        return view('bidan.imunisasi.show', compact('imunisasi'));
    }

    public function edit($id)
    {
        $imunisasi = Imunisasi::findOrFail($id);
        $kunjungans = Kunjungan::with('pasien')
            ->whereIn('pasien_type', ['App\Models\Balita', 'App\Models\Remaja', 'App\Models\IbuHamil'])
            ->whereDate('tanggal_kunjungan', '>=', Carbon::today()->subDays(7))
            ->latest('tanggal_kunjungan')
            ->get();
            
        return view('bidan.imunisasi.edit', compact('imunisasi', 'kunjungans'));
    }

    public function update(Request $request, $id)
    {
        $imunisasi = Imunisasi::findOrFail($id);
        
        $request->validate([
            'kunjungan_id'      => 'required|exists:kunjungans,id',
            'jenis_imunisasi'   => 'required|string|max:255',
            'vaksin'            => 'required|string|max:255',
            'dosis'             => 'required|string|max:50',
            'tanggal_imunisasi' => 'required|date',
        ]);

        $imunisasi->update($request->all());

        return redirect()->route('bidan.imunisasi.index')
            ->with('success', 'Koreksi data vaksinasi berhasil disimpan!');
    }

    public function destroy($id)
    {
        Imunisasi::findOrFail($id)->delete();
        return redirect()->route('bidan.imunisasi.index')->with('success', 'Riwayat vaksinasi telah dihapus permanen dari sistem.');
    }
}