<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPosyandu;
use App\Models\AbsensiDetail;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    /**
     * =========================================================================
     * 1. HALAMAN UTAMA INPUT ABSENSI (PRESENSI)
     * =========================================================================
     */
    public function index(Request $request)
    {
        // Default ke 'bayi' jika tidak ada kategori yang dipilih di URL
        $kategori = $request->get('kategori', 'bayi');
        $pasiens  = $this->getPasienByKategori($kategori);
        $tanggal  = today()->format('Y-m-d');

        // Cek apakah sesi absensi hari ini untuk kategori tersebut sudah ada
        $sesiHariIni = AbsensiPosyandu::with('details')
            ->where('kategori', $kategori)
            ->whereDate('tanggal_posyandu', $tanggal)
            ->first();

        // Siapkan *array* memori untuk mempertahankan status centang (hadir/absen) jika kader merefresh halaman
        $absensiData = [];
        $pertemuanBerikutnya = AbsensiPosyandu::where('kategori', $kategori)->count() + 1;

        if ($sesiHariIni) {
            foreach ($sesiHariIni->details as $detail) {
                $absensiData[$detail->pasien_id] = [
                    'hadir'      => $detail->hadir,
                    'keterangan' => $detail->keterangan
                ];
            }
            // Jika sesi hari ini sudah ada, nomor pertemuan tidak bertambah
            $pertemuanBerikutnya = $sesiHariIni->nomor_pertemuan; 
        }

        // Kalkulasi Statistik Mini untuk Sidebar / Header Kategori
        $statsPerKategori = [];
        foreach (['bayi', 'balita', 'remaja', 'lansia', 'ibu_hamil'] as $kat) {
            $statsPerKategori[$kat] = [
                'total_pertemuan' => AbsensiPosyandu::where('kategori', $kat)->count(),
                'total_pasien'    => $this->getPasienByKategori($kat)->count(),
            ];
        }

        return view('kader.absensi.index', compact(
            'kategori', 'pasiens', 'pertemuanBerikutnya', 'sesiHariIni', 'statsPerKategori', 'absensiData'
        ));
    }

    /**
     * =========================================================================
     * 2. MESIN PENYIMPANAN MASSAL (BULK UPSERT LOGIC)
     * =========================================================================
     * Sangat aman karena menggunakan DB::beginTransaction(). 
     * Jika mati lampu/koneksi putus di tengah jalan, database tidak akan rusak.
     */
    public function store(Request $request)
    {
        // Validasi ketat untuk memastikan tidak ada data siluman yang masuk
        $request->validate([
            'kategori'      => 'required|in:bayi,balita,remaja,lansia,ibu_hamil',
            'kehadiran'     => 'required|array', // Array ID_Pasien => 1 (Hadir) / 0 (Absen)
            'keterangan'    => 'nullable|array', // Array ID_Pasien => Alasan Absen
        ]);

        $kategori = $request->kategori;
        $tanggal  = today()->format('Y-m-d');

        // Mengunci Database sementara untuk proses masuk data massal
        DB::beginTransaction();
        try {
            // A. BUAT / AMBIL HEADER SESI ABSENSI HARI INI
            $sesi = AbsensiPosyandu::firstOrCreate(
                [
                    'kategori'         => $kategori,
                    'tanggal_posyandu' => $tanggal
                ],
                [
                    'kode_absensi'     => 'ABS-' . strtoupper(substr($kategori, 0, 3)) . '-' . date('Ymd') . '-' . rand(100,999),
                    'nomor_pertemuan'  => AbsensiPosyandu::where('kategori', $kategori)->count() + 1,
                    'bulan'            => date('m'),
                    'tahun'            => date('Y'),
                    'dicatat_oleh'     => auth()->id(), // Mencatat ID Kader yang sedang bertugas
                ]
            );

            // B. PROSES ISI DAFTAR HADIR (LOOPING PASIEN)
            foreach ($request->kehadiran as $pasien_id => $statusHadir) {
                // Konversi string '1' / '0' dari HTML Form menjadi boolean murni
                $isHadir = ($statusHadir == '1') ? true : false;
                
                // Ambil keterangan hanya jika pasien tersebut absen
                $keterangan = !$isHadir ? ($request->keterangan[$pasien_id] ?? null) : null;

                // Gunakan UpdateOrCreate agar tidak ada data dobel jika Kader mengklik simpan 2 kali di hari yang sama
                AbsensiDetail::updateOrCreate(
                    [
                        'absensi_id'  => $sesi->id,
                        'pasien_id'   => $pasien_id,
                        'pasien_type' => $kategori
                    ],
                    [
                        'hadir'       => $isHadir,
                        'keterangan'  => $keterangan,
                        'updated_at'  => now(),
                    ]
                );
            }

            // Simpan semua data di atas ke pangkalan data secara permanen
            DB::commit();

            // C. ARAHKAN KE HALAMAN ANIMASI SUKSES
            // FIX: Kita hilangkan parameter $sesi->id agar sesuai dengan deklarasi rute di web.php
            return redirect()->route('kader.absensi.success')
                             ->with('success', 'Sesi absensi berhasil disimpan dan dikunci!');

        } catch (\Exception $e) {
            // Jika ada error/crash, batalkan semua inputan agar database tetap bersih (Rollback)
            DB::rollBack();
            Log::error('Gagal Simpan Absensi (Sistem Kader): ' . $e->getMessage());
            
            return back()->with('error', 'Sistem Gagal Menyimpan: Terjadi kesalahan integritas data.');
        }
    }

    /**
     * =========================================================================
     * 3. HALAMAN ANIMASI SUKSES (POST-ACTION SUCCESS SCREEN)
     * =========================================================================
     */
    public function success()
    {
        return view('kader.absensi.success');
    }

    /**
     * =========================================================================
     * 4. HALAMAN DETAIL ABSENSI (VIEW SESI SPESIFIK)
     * =========================================================================
     */
    public function show($id)
    {
        $absensi = AbsensiPosyandu::with('pencatat')->findOrFail($id);
        $details = AbsensiDetail::where('absensi_id', $id)->get();

        // Menyuntikkan (Inject) data asli pasien (Nama & NIK) ke dalam baris detail absensi
        foreach ($details as $d) {
            $d->pasien_data = match($absensi->kategori) {
                'remaja'    => Remaja::find($d->pasien_id),
                'lansia'    => Lansia::find($d->pasien_id),
                'ibu_hamil' => IbuHamil::find($d->pasien_id),
                default     => Balita::find($d->pasien_id), // Default menangani "Bayi" dan "Balita"
            };
        }

        // Kalkulasi Cerdas untuk Grafik Lingkaran (Hadir vs Absen)
        $totalPasien = $details->count();
        $totalHadir  = $details->where('hadir', true)->count();
        $totalAbsen  = $totalPasien - $totalHadir;

        // Tarik seluruh riwayat sesi di kategori yang sama untuk menu Navigasi Cepat (Dropdown di halaman Show)
        $semuaSesi = AbsensiPosyandu::where('kategori', $absensi->kategori)
            ->orderBy('tanggal_posyandu', 'desc')
            ->get();

        return view('kader.absensi.show', compact(
            'absensi', 'details', 'totalHadir', 'totalAbsen', 'totalPasien', 'semuaSesi'
        ));
    }

    /**
     * =========================================================================
     * 5. HALAMAN DAFTAR RIWAYAT (HISTORY)
     * =========================================================================
     */
    public function riwayat(Request $request)
    {
        $kategori = $request->get('kategori');
        $bulan    = $request->get('bulan');

        // Tarik data dari yang paling terbaru
        $query = AbsensiPosyandu::with('details')->latest('tanggal_posyandu');

        // Filter Spesifik Kategori (jika ada)
        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        // Filter Spesifik Bulan (Format input UI: YYYY-MM)
        if ($bulan) {
            $tahun = substr($bulan, 0, 4);
            $bln   = substr($bulan, 5, 2);
            $query->whereYear('tanggal_posyandu', $tahun)
                  ->whereMonth('tanggal_posyandu', $bln);
        }

        // Terapkan Paginasi (15 Sesi per halaman)
        $riwayat = $query->paginate(15)->withQueryString();

        return view('kader.absensi.riwayat', compact('riwayat'));
    }

    /**
     * =========================================================================
     * 6. FUNGSI HAPUS (DELETE / DESTROY)
     * =========================================================================
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $absensi = AbsensiPosyandu::findOrFail($id);
            
            // Hapus isi anak-anak tabelnya (Detail) terlebih dahulu agar tidak ada orphaned data
            AbsensiDetail::where('absensi_id', $absensi->id)->delete();
            
            // Lalu hapus ibunya (Sesi Utama)
            $absensi->delete();
            
            DB::commit();
            return back()->with('success', 'Berhasil! Sesi absensi pertemuan tersebut telah dihapus secara permanen.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal Hapus Absensi: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data riwayat. Pastikan tidak ada data yang sedang terkunci.');
        }
    }

    /**
     * =========================================================================
     * 7. ENGINE PERANTARA (HELPER: AMBIL DAFTAR WARGA)
     * =========================================================================
     * Fungsi ini bertugas memilah dan menarik data warga sesuai usianya.
     */
    private function getPasienByKategori(string $kategori)
    {
        return match($kategori) {
            // Bayi: 0 sampai 11 Bulan
            'bayi'      => Balita::whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) BETWEEN 0 AND 11')->orderBy('nama_lengkap')->get(),
            // Balita: 12 sampai 59 Bulan
            'balita'    => Balita::whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) BETWEEN 12 AND 59')->orderBy('nama_lengkap')->get(),
            
            // Remaja, Lansia, Bumil ditarik dari tabelnya masing-masing
            'remaja'    => Remaja::orderBy('nama_lengkap')->get(),
            'lansia'    => Lansia::orderBy('nama_lengkap')->get(),
            'ibu_hamil' => IbuHamil::orderBy('nama_lengkap')->get(),
            
            default     => collect(),
        };
    }
}