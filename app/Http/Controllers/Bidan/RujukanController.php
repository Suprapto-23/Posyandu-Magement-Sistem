<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use Barryvdh\DomPDF\Facade\Pdf;

class RujukanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        // LOGIKA AUTO-DETECT: Mencari EMR Valid yang berisiko
        $query = Pemeriksaan::with(['balita', 'remaja', 'lansia', 'ibuHamil', 'verifikator'])
            ->where('status_verifikasi', 'verified')
            ->where(function($q) {
                // 1. Deteksi Stunting & Gizi Buruk
                $q->whereIn('indikasi_stunting', ['Stunting', 'Sangat Stunting'])
                  ->orWhere('status_gizi', 'Gizi Buruk')
                  
                // 2. Deteksi Hipertensi (Sistolik >= 140)
                // Mengambil angka sebelum garis miring '/' dan di-cast ke Integer agar akurat
                  ->orWhereRaw("CAST(SUBSTRING_INDEX(tekanan_darah, '/', 1) AS UNSIGNED) >= 140")
                  
                // 3. Deteksi Rekomendasi Rujukan dari Catatan Bidan/Kader
                  ->orWhere('tindakan', 'LIKE', '%rujuk%')
                  ->orWhere('tindakan', 'LIKE', '%puskesmas%')
                  ->orWhere('tindakan', 'LIKE', '%rsud%');
            });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('balita', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"))
                  ->orWhereHas('lansia', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"))
                  ->orWhereHas('remaja', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"))
                  ->orWhereHas('ibuHamil', fn($s) => $s->where('nama_lengkap', 'like', "%$search%"));
            });
        }

        $rujukans = $query->latest('tanggal_periksa')->paginate(10)->withQueryString();

        return view('bidan.rujukan.index', compact('rujukans'));
    }

    public function cetak($id)
    {
        $pemeriksaan = Pemeriksaan::with(['balita', 'remaja', 'lansia', 'ibuHamil', 'verifikator', 'pemeriksa'])->findOrFail($id);

        $pdf = Pdf::loadView('bidan.rujukan.pdf', compact('pemeriksaan'));
        $pdf->setPaper('A5', 'portrait');

        $kategori = class_basename($pemeriksaan->kategori_pasien ?? $pemeriksaan->pasien_type);
        $namaFile = 'Surat_Rujukan_' . $kategori . '_' . time() . '.pdf';

        return $pdf->stream($namaFile);
    }
}