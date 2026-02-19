<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;

class PemeriksaanController extends Controller
{
    /**
     * Menampilkan Riwayat Pemeriksaan (Memperbaiki Error: Undefined variable $riwayat)
     */
    public function index()
    {
        // Ambil data pemeriksaan, urutkan dari yang terbaru
        // Kita load relasi (balita, remaja, lansia) agar tidak N+1 query
        $riwayat = Pemeriksaan::with(['balita', 'remaja', 'lansia'])
            ->latest('tanggal_periksa')
            ->paginate(10);

        return view('bidan.pemeriksaan.index', compact('riwayat'));
    }

    /**
     * Menampilkan Form Input Pemeriksaan (Memperbaiki Error: Too few arguments)
     */
    public function create(Request $request)
    {
        // Default kategori jika tidak ada di URL adalah 'balita'
        $kategori = $request->query('kategori', 'balita');
        $pasien = [];

        // Logika mengambil data pasien berdasarkan kategori untuk Dropdown
        if ($kategori == 'balita') {
            $pasien = Balita::orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'nik']);
        } elseif ($kategori == 'remaja') {
            $pasien = Remaja::orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'nik']);
        } elseif ($kategori == 'lansia') {
            $pasien = Lansia::orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'nik']);
        }

        return view('bidan.pemeriksaan.create', compact('kategori', 'pasien'));
    }

    /**
     * Menyimpan Data Pemeriksaan
     */
    public function store(Request $request)
    {
        $request->validate([
            'pasien_id'       => 'required',
            'kategori_pasien' => 'required|in:balita,remaja,lansia',
            'berat_badan'     => 'required|numeric',
            'status_gizi'     => 'required',
        ]);

        Pemeriksaan::create([
            'pasien_id'       => $request->pasien_id,
            'kategori_pasien' => $request->kategori_pasien,
            'tanggal_periksa' => now(), // Atau $request->tanggal_periksa jika ada input tanggal
            'berat_badan'     => $request->berat_badan,
            'tinggi_badan'    => $request->tinggi_badan,
            'lingkar_kepala'  => $request->lingkar_kepala,
            'suhu_tubuh'      => $request->suhu_tubuh,
            'tekanan_darah'   => $request->tekanan_darah,
            'gula_darah'      => $request->gula_darah,
            'status_gizi'     => $request->status_gizi,
            'hasil_diagnosa'  => $request->hasil_diagnosa,
            'tindakan'        => $request->tindakan,
            'pemeriksa'       => auth()->user()->name ?? 'Bidan',
        ]);

        return redirect()->route('bidan.pemeriksaan.index')
            ->with('success', 'Data pemeriksaan berhasil disimpan.');
    }
}