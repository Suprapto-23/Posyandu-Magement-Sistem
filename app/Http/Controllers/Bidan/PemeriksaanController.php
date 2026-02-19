<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemeriksaan;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;

class PemeriksaanController extends Controller
{
    /**
     * Index - Riwayat semua pemeriksaan + filter + statistik
     */
    public function index(Request $request)
    {
        $query = Pemeriksaan::with(['balita', 'remaja', 'lansia', 'pemeriksa', 'verifikator'])
            ->latest('tanggal_periksa');

        if ($request->filled('kategori')) {
            $query->where('kategori_pasien', $request->kategori);
        }

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_periksa', $request->bulan)
                  ->whereYear('tanggal_periksa', $request->get('tahun', now()->year));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('balita', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"))
                  ->orWhereHas('remaja', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"))
                  ->orWhereHas('lansia', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"));
            });
        }

        // Variabel $riwayat agar sesuai nama di view lama
        $riwayat = $query->paginate(15)->withQueryString();

        $stats = [
            'pending'  => Pemeriksaan::pending()->count(),
            'verified' => Pemeriksaan::verified()->count(),
            'rejected' => Pemeriksaan::rejected()->count(),
            'total'    => Pemeriksaan::count(),
        ];

        return view('bidan.pemeriksaan.index', compact('riwayat', 'stats'));
    }

    /**
     * Show - Detail pemeriksaan + form validasi
     */
    public function show(int $id)
    {
        $pemeriksaan = Pemeriksaan::with([
            'balita', 'remaja', 'lansia',
            'pemeriksa', 'verifikator', 'kunjungan'
        ])->findOrFail($id);

        // Riwayat pasien yang sama untuk perbandingan tren
        $riwayat = Pemeriksaan::where('pasien_id', $pemeriksaan->pasien_id)
            ->where('kategori_pasien', $pemeriksaan->kategori_pasien)
            ->where('id', '!=', $id)
            ->latest('tanggal_periksa')
            ->take(5)
            ->get();

        return view('bidan.pemeriksaan.show', compact('pemeriksaan', 'riwayat'));
    }

    /**
     * Create - Form input pemeriksaan baru oleh Bidan
     */
    public function create(Request $request)
    {
        $kategori = $request->get('kategori', 'balita');

        $pasien = match($kategori) {
            'remaja' => Remaja::orderBy('nama_lengkap')->get(),
            'lansia' => Lansia::orderBy('nama_lengkap')->get(),
            default  => Balita::orderBy('nama_lengkap')->get(),
        };

        return view('bidan.pemeriksaan.create', compact('kategori', 'pasien'));
    }

    /**
     * Store - Simpan pemeriksaan baru oleh Bidan
     * Bidan input → otomatis verified (tidak perlu validasi ulang)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_pasien' => 'required|in:balita,remaja,lansia',
            'pasien_id'       => 'required|integer',
            'berat_badan'     => 'nullable|numeric|min:0',
            'tinggi_badan'    => 'nullable|numeric|min:0',
            'tekanan_darah'   => 'nullable|string',
            'suhu_tubuh'      => 'nullable|numeric',
            'keluhan'         => 'nullable|string',
            'tindakan'        => 'nullable|string',
            'status_gizi'     => 'nullable|in:baik,kurang,buruk,stunting,obesitas,lebih,risiko',
        ]);

        $data = $request->only([
            'kategori_pasien', 'pasien_id',
            'berat_badan', 'tinggi_badan', 'lingkar_kepala', 'lingkar_lengan',
            'suhu_tubuh', 'tekanan_darah', 'hemoglobin',
            'gula_darah', 'kolesterol', 'asam_urat',
            'keluhan', 'tindakan', 'status_gizi',
        ]);

        // View create pakai field 'hasil_diagnosa' → dipetakan ke kolom 'diagnosa'
        $data['diagnosa']        = $request->input('hasil_diagnosa');
        $data['tanggal_periksa'] = $request->input('tanggal_periksa', now()->toDateString());
        $data['pemeriksa_id']    = Auth::id();

        // ✅ Bidan input → langsung verified
        $data['status_verifikasi'] = 'verified';
        $data['verified_by']       = Auth::id();
        $data['verified_at']       = now();

        Pemeriksaan::create($data);

        return redirect()->route('bidan.pemeriksaan.index')
            ->with('success', 'Data pemeriksaan berhasil disimpan.');
    }

    /**
     * Verifikasi penuh dari halaman show
     * Bidan bisa: isi diagnosa, tindakan, catatan, lalu pilih verified/rejected
     */
    public function verifikasi(Request $request, int $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:verified,rejected',
            'diagnosa'          => 'nullable|string|max:1000',
            'catatan_bidan'     => 'nullable|string|max:1000',
            'tindakan'          => 'nullable|string|max:1000',
        ]);

        $pemeriksaan = Pemeriksaan::findOrFail($id);

        // Jika tolak, catatan bidan wajib ada
        if ($request->status_verifikasi === 'rejected' && empty($request->catatan_bidan)) {
            return back()->withErrors([
                'catatan_bidan' => 'Catatan bidan wajib diisi jika data ditolak.'
            ])->withInput();
        }

        $pemeriksaan->update([
            'status_verifikasi' => $request->status_verifikasi,
            'diagnosa'          => $request->diagnosa,
            'catatan_bidan'     => $request->catatan_bidan,
            'tindakan'          => $request->tindakan,
            'verified_by'       => Auth::id(),
            'verified_at'       => now(),
        ]);

        $label = $request->status_verifikasi === 'verified' ? 'diverifikasi' : 'ditolak';

        return redirect()->route('bidan.pemeriksaan.index')
            ->with('success', "Data pemeriksaan berhasil {$label}.");
    }

    /**
     * Verifikasi cepat via AJAX langsung dari tabel index
     */
    public function verifikasiCepat(Request $request, int $id)
    {
        $request->validate([
            'diagnosa'      => 'required|string|max:1000',
            'catatan_bidan' => 'nullable|string|max:500',
        ]);

        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->update([
            'status_verifikasi' => 'verified',
            'diagnosa'          => $request->diagnosa,
            'catatan_bidan'     => $request->catatan_bidan,
            'verified_by'       => Auth::id(),
            'verified_at'       => now(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Data berhasil diverifikasi.',
                'sisa_antrian' => Pemeriksaan::pending()->bulanIni()->count(),
            ]);
        }

        return back()->with('success', 'Data berhasil diverifikasi.');
    }
}