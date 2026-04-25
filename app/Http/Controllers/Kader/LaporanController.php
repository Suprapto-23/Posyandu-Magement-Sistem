<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;
use App\Models\Imunisasi;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Menampilkan Dashboard Generator Laporan
     */
    public function index()
    {
        return view('kader.laporan.index');
    }

    /**
     * Mesin Utama Generator PDF (Direct Download)
     */
  public function generate(Request $request)
    {
        ini_set('memory_limit', '1024M'); 
        set_time_limit(300);

        // Mengambil data dari request GET
        $type   = $request->get('type');
        $bulan  = (int) $request->get('bulan');
        $tahun  = (int) $request->get('tahun');
        $validTypes = ['balita', 'ibu_hamil', 'remaja', 'lansia', 'imunisasi', 'kunjungan'];
        if (!in_array($type, $validTypes)) {
            return back()->with('error', 'Kategori laporan tidak valid di sistem.');
        }

        $namaBulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');
        $fileName  = "Laporan_{$type}_Bantarkulon_{$namaBulan}_{$tahun}";

        // =======================================================
        // PDF EXPORT DATA GATHERING
        // =======================================================
        $data = collect(); // Inisialisasi koleksi kosong
        
        if ($type === 'imunisasi') {
            $data = Imunisasi::with('kunjungan.pasien')
                ->whereMonth('tanggal_imunisasi', $bulan)
                ->whereYear('tanggal_imunisasi', $tahun)
                ->orderBy('tanggal_imunisasi', 'asc')
                ->get();
        } elseif ($type === 'kunjungan') {
            $data = Kunjungan::with('pasien', 'petugas')
                ->whereMonth('tanggal_kunjungan', $bulan)
                ->whereYear('tanggal_kunjungan', $tahun)
                ->orderBy('tanggal_kunjungan', 'asc')
                ->get();
        } else {
            // Tarik data Pemeriksaan sesuai tipe
            $kategori_query = $type === 'balita' ? ['bayi', 'balita'] : [$type];
            $data = Pemeriksaan::whereIn('kategori_pasien', $kategori_query)
                ->whereMonth('tanggal_periksa', $bulan)
                ->whereYear('tanggal_periksa', $tahun)
                ->orderBy('tanggal_periksa', 'asc')
                ->get();

            // Inject relasi profil pasien (Nama & Jenis Kelamin) untuk PDF
            foreach ($data as $row) {
                $row->nama_pasien   = $this->getNamaPasien($row->pasien_id, $row->kategori_pasien);
                $row->jenis_kelamin = $this->getJkPasien($row->pasien_id, $row->kategori_pasien);
            }
        }

        // =======================================================
        // FAILSAFE: CEK JIKA DATA KOSONG
        // =======================================================
        if ($data->isEmpty()) {
            return back()->with('error', "Tidak ada catatan " . str_replace('_', ' ', $type) . " pada periode {$namaBulan} {$tahun}.");
        }

        // =======================================================
        // RENDER & PAKSA DOWNLOAD
        // =======================================================
        $pdf = Pdf::loadView("kader.laporan.templates.table-{$type}", compact('data', 'bulan', 'tahun', 'namaBulan'))
            ->setPaper('A4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->download($fileName . '.pdf');
    }

    // ================= HELPERS (Pencari Nama Lintas Tabel) =================
    private function getNamaPasien($id, $kategori) {
        try {
            return match($kategori){
                'remaja'    => Remaja::find($id)?->nama_lengkap ?? 'Unknown',
                'lansia'    => Lansia::find($id)?->nama_lengkap ?? 'Unknown',
                'ibu_hamil' => IbuHamil::find($id)?->nama_lengkap ?? 'Unknown',
                default     => Balita::find($id)?->nama_lengkap ?? 'Unknown',
            };
        } catch(\Throwable $e) { return 'Unknown'; }
    }

    private function getJkPasien($id, $kategori) {
        try {
            return match($kategori){
                'remaja'    => Remaja::find($id)?->jenis_kelamin ?? '-',
                'lansia'    => Lansia::find($id)?->jenis_kelamin ?? '-',
                'ibu_hamil' => 'P', // Ibu hamil pasti perempuan
                default     => Balita::find($id)?->jenis_kelamin ?? '-',
            };
        } catch(\Throwable $e) { return '-'; }
    }
}