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
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'bayi');
        $pasiens  = $this->getPasienByKategori($kategori);
        $tanggal  = today()->format('Y-m-d');

        // Cek apakah sesi hari ini sudah ada
        $sesiHariIni = AbsensiPosyandu::with('details')->where('kategori', $kategori)
            ->whereDate('tanggal_posyandu', $tanggal)
            ->first();

        // Siapkan array data kehadiran jika sudah pernah diisi sebelumnya
        $absensiData = [];
        $pertemuanBerikutnya = AbsensiPosyandu::where('kategori', $kategori)->count() + 1;

        if ($sesiHariIni) {
            foreach ($sesiHariIni->details as $detail) {
                $absensiData[$detail->pasien_id] = [
                    'hadir'      => $detail->hadir,
                    'keterangan' => $detail->keterangan
                ];
            }
            $pertemuanBerikutnya = $sesiHariIni->nomor_pertemuan; 
        }

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
     * BULK SUBMIT: Menyimpan seluruh absensi dalam 1x Transaksi Database.
     * Sangat aman dan profesional untuk skala Enterprise / Skripsi.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori'      => 'required|in:bayi,balita,remaja,lansia,ibu_hamil',
            'kehadiran'     => 'required|array', // Berisi ID pasien => status (1 atau 0)
            'keterangan'    => 'nullable|array', // Berisi ID pasien => keterangan alasan
        ]);

        $kategori = $request->kategori;
        $tanggal  = today()->format('Y-m-d');

        DB::beginTransaction();
        try {
            // 1. BUAT ATAU AMBIL SESI ABSENSI HARI INI
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
                    'dicatat_oleh'     => auth()->id(),
                ]
            );

            // 2. LOOPING DATA DAN SIMPAN KE DATABASE (Bulk Upsert Logic)
            foreach ($request->kehadiran as $pasien_id => $statusHadir) {
                // Konversi string '1' / '0' menjadi boolean
                $isHadir = $statusHadir == '1' ? true : false;
                
                // Ambil keterangan jika pasien absen, kosongkan jika hadir
                $keterangan = !$isHadir ? ($request->keterangan[$pasien_id] ?? null) : null;

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

            DB::commit();

            // Arahkan langsung ke halaman Ringkasan (Show)
            return redirect()->route('kader.absensi.show', $sesi->id)
                ->with('success', 'Sesi absensi berhasil disimpan dan dikunci!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal Simpan Absensi: ' . $e->getMessage());
            return back()->with('error', 'Sistem Gagal Menyimpan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $absensi = AbsensiPosyandu::with('pencatat')->findOrFail($id);
        $details = AbsensiDetail::where('absensi_id', $id)->get();

        foreach ($details as $d) {
            $d->pasien_data = match($absensi->kategori) {
                'remaja'    => Remaja::find($d->pasien_id),
                'lansia'    => Lansia::find($d->pasien_id),
                'ibu_hamil' => IbuHamil::find($d->pasien_id),
                default     => Balita::find($d->pasien_id),
            };
        }

        $totalPasien = $details->count();
        $totalHadir  = $details->where('hadir', true)->count();
        $totalAbsen  = $totalPasien - $totalHadir;

        // 🔥 LOGIKA BARU: Tarik seluruh riwayat sesi di kategori yang sama untuk menu Dropdown
        $semuaSesi = AbsensiPosyandu::where('kategori', $absensi->kategori)
            ->orderBy('tanggal_posyandu', 'desc') // Urutkan dari yang terbaru
            ->get();

        return view('kader.absensi.show', compact(
            'absensi', 'details', 'totalHadir', 'totalAbsen', 'totalPasien',
            'semuaSesi'
        ));
    }

    public function riwayat(Request $request)
    {
        $kategori = $request->get('kategori');
        $bulan    = $request->get('bulan');

        $query = AbsensiPosyandu::with('details')->latest('tanggal_posyandu');

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($bulan) {
            $tahun = substr($bulan, 0, 4);
            $bln   = substr($bulan, 5, 2);
            $query->whereYear('tanggal_posyandu', $tahun)
                  ->whereMonth('tanggal_posyandu', $bln);
        }

        $riwayat = $query->paginate(15)->withQueryString();

        return view('kader.absensi.riwayat', compact('riwayat'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $absensi = AbsensiPosyandu::findOrFail($id);
            AbsensiDetail::where('absensi_id', $absensi->id)->delete();
            $absensi->delete();
            DB::commit();
            return back()->with('success', 'Berhasil! Sesi absensi pertemuan tersebut telah dihapus secara permanen.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data riwayat: ' . $e->getMessage());
        }
    }

    private function getPasienByKategori(string $kategori)
    {
        return match($kategori) {
            'bayi'      => Balita::whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) BETWEEN 0 AND 11')->orderBy('nama_lengkap')->get(),
            'balita'    => Balita::whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) BETWEEN 12 AND 59')->orderBy('nama_lengkap')->get(),
            'remaja'    => Remaja::orderBy('nama_lengkap')->get(),
            'lansia'    => Lansia::orderBy('nama_lengkap')->get(),
            'ibu_hamil' => IbuHamil::orderBy('nama_lengkap')->get(),
            default     => collect(),
        };
    }
}