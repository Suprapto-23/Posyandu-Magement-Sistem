<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\AbsensiPosyandu;
use App\Models\AbsensiDetail;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    // ================================================================
    // HALAMAN UTAMA: Pilih kategori & isi absensi
    // ================================================================
    public function index(Request $request)
    {
        $kategori = $request->get('kategori', 'bayi');
        $pasiens  = $this->getPasienByKategori($kategori);

        // Hitung nomor pertemuan berikutnya untuk kategori ini
        $pertemuanBerikutnya = AbsensiPosyandu::where('kategori', $kategori)->count() + 1;

        // Cek apakah hari ini sudah ada sesi untuk kategori ini
        $sesiHariIni = AbsensiPosyandu::where('kategori', $kategori)
            ->whereDate('tanggal_posyandu', today())
            ->first();

        // Ringkasan statistik per kategori
        $statsPerKategori = [];
        foreach (['bayi', 'balita', 'remaja', 'lansia'] as $kat) {
            $statsPerKategori[$kat] = [
                'total_pertemuan' => AbsensiPosyandu::where('kategori', $kat)->count(),
                'total_pasien'    => $this->getPasienByKategori($kat)->count(),
            ];
        }

        return view('kader.absensi.index', compact(
            'pasiens', 'kategori', 'pertemuanBerikutnya', 'sesiHariIni', 'statsPerKategori'
        ));
    }

    // ================================================================
    // SIMPAN ABSENSI
    // ================================================================
    public function store(Request $request)
    {
        $request->validate([
            'kategori'         => 'required|in:bayi,balita,remaja,lansia',
            'tanggal_posyandu' => 'required|date',
            'catatan'          => 'nullable|string|max:1000',
            'hadir'            => 'nullable|array',
            'hadir.*'          => 'integer',
            'keterangan'       => 'nullable|array',
            'keterangan.*'     => 'nullable|string|max:100',
        ]);

        $kategori = $request->kategori;

        // Cegah duplikat di hari yang sama untuk kategori yang sama
        $existing = AbsensiPosyandu::where('kategori', $kategori)
            ->whereDate('tanggal_posyandu', $request->tanggal_posyandu)
            ->first();

        if ($existing) {
            return back()->with('error',
                "Absensi {$this->labelKategori($kategori)} untuk tanggal ini sudah ada. Lihat di riwayat."
            );
        }

        try {
            DB::transaction(function () use ($request, $kategori) {
                // Hitung nomor pertemuan otomatis
                $nomorPertemuan = AbsensiPosyandu::where('kategori', $kategori)->count() + 1;

                $kode = 'ABS-' . strtoupper($kategori) . '-P' . str_pad($nomorPertemuan, 3, '0', STR_PAD_LEFT);

                $absensi = AbsensiPosyandu::create([
                    'kode_absensi'     => $kode,
                    'nomor_pertemuan'  => $nomorPertemuan,
                    'kategori'         => $kategori,
                    'tanggal_posyandu' => $request->tanggal_posyandu,
                    'bulan'            => Carbon::parse($request->tanggal_posyandu)->month,
                    'tahun'            => Carbon::parse($request->tanggal_posyandu)->year,
                    'catatan'          => $request->catatan,
                    'dicatat_oleh'     => Auth::id(),
                ]);

                $semua    = $this->getPasienByKategori($kategori);
                $hadirIds = $request->input('hadir', []);

                foreach ($semua as $pasien) {
                    AbsensiDetail::create([
                        'absensi_id'  => $absensi->id,
                        'pasien_id'   => $pasien->id,
                        'pasien_type' => $kategori,
                        'hadir'       => in_array($pasien->id, $hadirIds),
                        'keterangan'  => $request->input("keterangan.{$pasien->id}"),
                    ]);
                }
            });

            return redirect()->route('kader.absensi.riwayat')
                ->with('success', "Absensi {$this->labelKategori($kategori)} berhasil disimpan!");

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }
    }

    // ================================================================
    // RIWAYAT: Filter per kategori & pertemuan
    // ================================================================
    public function riwayat(Request $request)
    {
        $kategori   = $request->get('kategori');
        $pertemuan  = $request->get('pertemuan');  // nomor pertemuan
        $tahun      = $request->get('tahun', now()->year);

        $riwayat = AbsensiPosyandu::with(['pencatat', 'details'])
            ->when($kategori,  fn($q) => $q->where('kategori', $kategori))
            ->when($pertemuan, fn($q) => $q->where('nomor_pertemuan', $pertemuan))
            ->when($tahun,     fn($q) => $q->where('tahun', $tahun))
            ->latest('tanggal_posyandu')
            ->paginate(20);

        // Untuk dropdown pertemuan: ambil max per kategori
        $maxPertemuan = AbsensiPosyandu::when($kategori, fn($q) => $q->where('kategori', $kategori))
            ->max('nomor_pertemuan') ?? 0;

        return view('kader.absensi.riwayat', compact(
            'riwayat', 'kategori', 'pertemuan', 'tahun', 'maxPertemuan'
        ));
    }

    // ================================================================
    // DETAIL SATU SESI
    // ================================================================
    public function show($id)
    {
        $absensi = AbsensiPosyandu::with(['pencatat', 'details'])->findOrFail($id);

        $details = $absensi->details->map(function ($d) {
            $d->pasien_data = $d->pasien;
            return $d;
        });

        $totalHadir  = $absensi->details->where('hadir', true)->count();
        $totalAbsen  = $absensi->details->where('hadir', false)->count();
        $totalPasien = $absensi->details->count();

        // Navigasi: sesi sebelum & sesudah (kategori yang sama)
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

    // ================================================================
    // HELPER
    // ================================================================
    private function getPasienByKategori(string $kategori)
    {
        return match($kategori) {
            'bayi'   => Balita::whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) BETWEEN 0 AND 11')
                            ->orderBy('nama_lengkap')->get(),
            'balita' => Balita::whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) BETWEEN 12 AND 59')
                            ->orderBy('nama_lengkap')->get(),
            'remaja' => Remaja::orderBy('nama_lengkap')->get(),
            'lansia' => Lansia::orderBy('nama_lengkap')->get(),
            default  => collect(),
        };
    }

    private function labelKategori(string $k): string
    {
        return match($k) {
            'bayi'   => 'Bayi (0–11 bln)',
            'balita' => 'Balita (12–59 bln)',
            'remaja' => 'Remaja',
            'lansia' => 'Lansia',
            default  => $k,
        };
    }
}