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

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'bayi');
        $pasiens  = $this->getPasienByKategori($kategori);
        $tanggal  = today()->format('Y-m-d'); // Kunci absensi ke hari ini

        // Cek apakah sudah ada sesi yang terbuat HARI INI
        $sesiHariIni = AbsensiPosyandu::where('kategori', $kategori)
            ->whereDate('tanggal_posyandu', $tanggal)
            ->first();

        // Tarik data siapa saja yang sudah dicentang (untuk antisipasi refresh halaman)
        $hadirList = [];
        $pertemuanBerikutnya = AbsensiPosyandu::where('kategori', $kategori)->count() + 1;

        if ($sesiHariIni) {
            $hadirList = AbsensiDetail::where('absensi_id', $sesiHariIni->id)
                            ->where('hadir', true)
                            ->pluck('pasien_id')
                            ->toArray();
            
            // Pertemuan tidak bertambah jika sesi hari ini sudah ada
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
            'kategori', 'pasiens', 'pertemuanBerikutnya', 'sesiHariIni', 'statsPerKategori', 'hadirList'
        ));
    }

    /**
     * FUNGSI BARU: INSTANT SAVE AJAX (Menggantikan metode store lama)
     * Dipanggil otomatis di belakang layar setiap kali Kader klik centang/un-centang
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'kategori'  => 'required|in:bayi,balita,remaja,lansia,ibu_hamil',
            'pasien_id' => 'required|integer',
            'hadir'     => 'required|boolean', // true jika dicentang, false jika dihilangkan
        ]);

        $kategori = $request->kategori;
        $tanggal  = today()->format('Y-m-d');

        try {
            // 1. CARI ATAU BUAT SESI (Smart Initialize)
            // Jika ini centangan pertama hari ini, sistem otomatis membuat wadah sesinya
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

            // 2. SIMPAN/UPDATE CENTANGAN (Real-Time)
            AbsensiDetail::updateOrCreate(
                [
                    'absensi_id'  => $sesi->id,
                    'pasien_id'   => $request->pasien_id,
                    'pasien_type' => $kategori
                ],
                [
                    'hadir'       => $request->hadir,
                    'updated_at'  => now(),
                ]
            );

            return response()->json([
                'status'  => 'success',
                'message' => $request->hadir ? 'Hadir tersimpan' : 'Hadir dibatalkan',
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * FUNGSI BARU: Penutup Sesi
     * Dipanggil saat Kader klik tombol "Selesai & Ringkasan" di paling bawah
     */
    public function selesaiSesi(Request $request)
    {
        $kategori = $request->kategori;
        $tanggal  = today()->format('Y-m-d');

        $sesi = AbsensiPosyandu::where('kategori', $kategori)
            ->whereDate('tanggal_posyandu', $tanggal)
            ->first();

        // Jika Kader klik selesai tanpa mencentang 1 orang pun (maka sesi belum tercipta)
        if (!$sesi) {
            return back()->with('error', 'Tidak ada data absensi yang dicatat hari ini.');
        }

        return redirect()->route('kader.absensi.show', $sesi->id)
            ->with('success', 'Sesi absensi hari ini telah dikunci dan diselesaikan.');
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

        $sebelumnya = AbsensiPosyandu::where('kategori', $absensi->kategori)
            ->where('nomor_pertemuan', '<', $absensi->nomor_pertemuan)
            ->orderBy('nomor_pertemuan', 'desc')->first();

        $berikutnya = AbsensiPosyandu::where('kategori', $absensi->kategori)
            ->where('nomor_pertemuan', '>', $absensi->nomor_pertemuan)
            ->orderBy('nomor_pertemuan', 'asc')->first();

        return view('kader.absensi.show', compact(
            'absensi', 'details', 'totalHadir', 'totalAbsen', 'totalPasien',
            'sebelumnya', 'berikutnya'
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