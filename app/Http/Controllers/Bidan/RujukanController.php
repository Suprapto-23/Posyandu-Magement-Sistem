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
                // Deteksi Stunting (Dari kolom baru)
                $q->where('indikasi_stunting', 'Stunting')
                  ->orWhere('indikasi_stunting', 'Sangat Stunting')
                // Deteksi Gizi Buruk
                  ->orWhere('status_gizi', 'Gizi Buruk')
                // Deteksi Hipertensi (Tensi > 140)
                  ->orWhere('tekanan_darah', 'LIKE', '140/%')
                  ->orWhere('tekanan_darah', 'LIKE', '150/%')
                  ->orWhere('tekanan_darah', 'LIKE', '160/%')
                  ->orWhere('tekanan_darah', 'LIKE', '170/%')
                // Deteksi Catatan Bidan
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