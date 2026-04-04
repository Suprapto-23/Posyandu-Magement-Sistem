<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemeriksaan;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;

class PemeriksaanController extends Controller
{
    /**
     * 1. Menampilkan Antrian (Pending) & Riwayat (Verified)
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'pending');
        $search = $request->get('search');

        $query = Pemeriksaan::with(['balita', 'remaja', 'lansia', 'pemeriksa'])->latest('tanggal_periksa');
        $query->where('status_verifikasi', $tab);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('balita', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"))
                  ->orWhereHas('remaja', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"))
                  ->orWhereHas('lansia', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"));
            });
        }

        $pemeriksaans = $query->paginate(15)->withQueryString();
        $pendingCount = Pemeriksaan::where('status_verifikasi', 'pending')->count();

        return view('bidan.pemeriksaan.index', compact('pemeriksaans', 'tab', 'pendingCount', 'search'));
    }

    /**
     * 2. Menampilkan Form Input Manual oleh Bidan (Ini yang menyebabkan error 404 sebelumnya)
     */
    public function create(Request $request)
    {
        $kategori = $request->get('kategori', 'balita');

        $pasien = match($kategori) {
            'remaja' => Remaja::orderBy('nama_lengkap')->get(),
            'lansia' => Lansia::orderBy('nama_lengkap')->get(),
            'ibu_hamil' => IbuHamil::orderBy('nama_lengkap')->get(),
            default  => Balita::orderBy('nama_lengkap')->get(),
        };

        return view('bidan.pemeriksaan.create', compact('kategori', 'pasien'));
    }

    /**
     * 3. Menyimpan Input Manual Bidan (Langsung terverifikasi)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_pasien' => 'required|in:balita,remaja,lansia,ibu_hamil',
            'pasien_id'       => 'required|integer',
            'berat_badan'     => 'nullable|numeric|min:0',
            'tinggi_badan'    => 'nullable|numeric|min:0',
            'diagnosa'        => 'required|string',
            'tindakan'        => 'required|string',
        ]);

        $data = $request->all();
        $data['tanggal_periksa'] = $request->input('tanggal_periksa', now()->toDateString());
        $data['pemeriksa_id']    = Auth::id();
        
        // Karena Bidan yang menginput, langsung statusnya Verified
        $data['status_verifikasi'] = 'verified';
        $data['verified_by']       = Auth::id();
        $data['verified_at']       = now();

        Pemeriksaan::create($data);

        return redirect()->route('bidan.pemeriksaan.index', ['tab' => 'verified'])
            ->with('success', 'Pemeriksaan medis berhasil dicatat dan divalidasi sistem.');
    }

    /**
     * 4. Halaman Form Validasi Medis (Dari antrian Kader)
     */
    public function validasi($id)
    {
        $pemeriksaan = Pemeriksaan::with(['balita', 'remaja', 'lansia', 'pemeriksa'])->findOrFail($id);
        
        if ($pemeriksaan->status_verifikasi === 'verified') {
            return redirect()->route('bidan.pemeriksaan.index', ['tab' => 'verified'])
                ->with('error', 'Data ini sudah divalidasi sebelumnya.');
        }

        return view('bidan.pemeriksaan.validasi', compact('pemeriksaan'));
    }

    /**
     * 5. Menyimpan Hasil Validasi Bidan
     */
    public function simpanValidasi(Request $request, $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $request->validate([
            'diagnosa'      => 'required|string',
            'tindakan'      => 'required|string',
        ]);

        $pemeriksaan->diagnosa = $request->diagnosa;
        $pemeriksaan->tindakan = $request->tindakan;
        $pemeriksaan->catatan_bidan = $request->catatan_bidan;
        
        if (in_array($pemeriksaan->kategori_pasien, ['ibu_hamil', 'bumil', 'IbuHamil'])) {
            $pemeriksaan->tfu = $request->tfu;
            $pemeriksaan->djj = $request->djj;
            $pemeriksaan->posisi_janin = $request->posisi_janin;
        }

        $pemeriksaan->status_verifikasi = 'verified';
        $pemeriksaan->verified_by = Auth::id();
        $pemeriksaan->verified_at = now();
        $pemeriksaan->save();

        return redirect()->route('bidan.pemeriksaan.index', ['tab' => 'pending'])
            ->with('success', 'Validasi klinis berhasil disimpan. Pasien masuk ke Riwayat Rekam Medis.');
    }

    /**
     * 6. Fungsi Standar Show, Edit, Update, Destroy
     */
    public function show($id) {
        $pemeriksaan = Pemeriksaan::with(['balita', 'remaja', 'lansia', 'pemeriksa', 'verifikator'])->findOrFail($id);
        return view('bidan.pemeriksaan.show', compact('pemeriksaan'));
    }

    public function edit($id) {
        // Logika edit bisa diarahkan ke validasi jika diperlukan
        return redirect()->route('bidan.pemeriksaan.validasi', $id);
    }

    public function update(Request $request, $id) {
        // Handle update
    }

    public function destroy($id) {
        Pemeriksaan::findOrFail($id)->delete();
        return back()->with('success', 'Data pemeriksaan berhasil dihapus permanen.');
    }
}