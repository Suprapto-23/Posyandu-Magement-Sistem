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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'bayi');
        $pasiens  = $this->getPasienByKategori($kategori);

        $pertemuanBerikutnya = AbsensiPosyandu::where('kategori', $kategori)->count() + 1;

        $sesiHariIni = AbsensiPosyandu::where('kategori', $kategori)
            ->whereDate('tanggal_posyandu', today())
            ->first();

        $statsPerKategori = [];
        foreach (['bayi', 'balita', 'remaja', 'lansia', 'ibu_hamil'] as $kat) {
            $statsPerKategori[$kat] = [
                'total_pertemuan' => AbsensiPosyandu::where('kategori', $kat)->count(),
                'total_pasien'    => $this->getPasienByKategori($kat)->count(),
            ];
        }

        return view('kader.absensi.index', compact(
            'kategori', 'pasiens', 'pertemuanBerikutnya', 'sesiHariIni', 'statsPerKategori'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori'         => 'required|in:bayi,balita,remaja,lansia,ibu_hamil',
            'tanggal_posyandu' => 'required|date',
            'hadir'            => 'nullable|array',
            'keterangan'       => 'nullable|array',
        ]);

        $kategori = $request->kategori;
        $tanggal  = $request->tanggal_posyandu;

        $cek = AbsensiPosyandu::where('kategori', $kategori)
            ->whereDate('tanggal_posyandu', $tanggal)->first();

        if ($cek) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Absensi untuk tanggal tersebut sudah dicatat sebelumnya.'], 422);
            }
            return back()->with('error', 'Absensi tanggal tersebut sudah dicatat.');
        }

        $nomor_pertemuan = AbsensiPosyandu::where('kategori', $kategori)->count() + 1;
        $tglFormat = Carbon::parse($tanggal);

        DB::beginTransaction();
        try {
            $absensi = AbsensiPosyandu::create([
                'kode_absensi'     => 'ABS-' . strtoupper(substr($kategori, 0, 3)) . '-' . date('Ymd') . '-' . rand(100,999),
                'kategori'         => $kategori,
                'tanggal_posyandu' => $tanggal,
                'nomor_pertemuan'  => $nomor_pertemuan,
                'bulan'            => $tglFormat->format('m'),
                'tahun'            => $tglFormat->format('Y'),
                'catatan'          => $request->catatan,
                'dicatat_oleh'     => auth()->id(),
            ]);

            $pasiens = $this->getPasienByKategori($kategori);
            $hadirArray = $request->hadir ?? [];
            $keteranganArray = $request->keterangan ?? [];

            $details = [];
            foreach ($pasiens as $p) {
                $isHadir = in_array($p->id, $hadirArray);
                $details[] = [
                    'absensi_id'  => $absensi->id,
                    'pasien_id'   => $p->id,
                    'pasien_type' => $kategori, // <--- INI KUNCI FIX ERRORNYA
                    'hadir'       => $isHadir,
                    'keterangan'  => $keteranganArray[$p->id] ?? null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }

            AbsensiDetail::insert($details);
            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Absensi berhasil dikunci dan disimpan!',
                    'redirect' => route('kader.absensi.show', $absensi->id)
                ]);
            }

            return redirect()->route('kader.absensi.show', $absensi->id)->with('success', 'Absensi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Gagal: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
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