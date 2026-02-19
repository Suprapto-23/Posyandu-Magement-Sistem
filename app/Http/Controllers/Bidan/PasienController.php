<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\Pemeriksaan;

class PasienController extends Controller
{
    // ========================================================================
    //                              DATA BALITA
    // ========================================================================
    
    public function indexBalita(Request $request)
    {
        $search = $request->get('search');

        $balitas = Balita::query()
            ->when($search, function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('nama_ibu', 'like', "%{$search}%");
            })
            ->with('pemeriksaan_terakhir') 
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return view('bidan.pasien.partials.table_balita', compact('balitas'))->render();
        }

        return view('bidan.pasien.balita', compact('balitas'));
    }

    public function laporanBalita()
    {
        $balitas = Balita::with('pemeriksaan_terakhir')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return view('bidan.laporan.balita_print', compact('balitas'));
    }


    // ========================================================================
    //                              DATA REMAJA
    // ========================================================================

    public function indexRemaja(Request $request)
    {
        $search = $request->get('search');

        $remajas = Remaja::query()
            ->when($search, function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('sekolah', 'like', "%{$search}%");
            })
            ->with('pemeriksaan_terakhir')
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {
            return view('bidan.pasien.partials.table_remaja', compact('remajas'))->render();
        }

        return view('bidan.pasien.remaja', compact('remajas'));
    }

    public function laporanRemaja()
    {
        $remajas = Remaja::with('pemeriksaan_terakhir')
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        return view('bidan.laporan.remaja_print', compact('remajas'));
    }


    // ========================================================================
    //                              DATA LANSIA
    // ========================================================================

    public function indexLansia(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Lansia::with('pemeriksaan_terakhir')->latest();

        // 1. Logika Pencarian (Search)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  // KEMBALI KE 'diagnosa'
                  ->orWhereHas('pemeriksaan_terakhir', function($sq) use ($search) {
                      $sq->where('diagnosa', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Logika Filter Status
        if ($status) {
            if ($status == 'hipertensi') {
                $query->whereHas('pemeriksaan_terakhir', function($q) {
                    // KEMBALI KE 'diagnosa'
                    $q->whereRaw("CAST(SUBSTRING_INDEX(tekanan_darah, '/', 1) AS UNSIGNED) >= 140")
                      ->orWhere('diagnosa', 'like', '%hipertensi%');
                });
            } elseif ($status == 'diabetes') {
                $query->whereHas('pemeriksaan_terakhir', function($q) {
                    // KEMBALI KE 'diagnosa'
                    $q->where('gula_darah', '>=', 200)
                      ->orWhere('diagnosa', 'like', '%diabetes%')
                      ->orWhere('diagnosa', 'like', '%gula%');
                });
            } elseif ($status == 'normal') {
                $query->whereHas('pemeriksaan_terakhir', function($q) {
                    $q->whereRaw("CAST(SUBSTRING_INDEX(tekanan_darah, '/', 1) AS UNSIGNED) < 140");
                });
            }
        }

        $lansias = $query->paginate(10);

        // --- Statistik Ringkas untuk Dashboard Lansia ---
        $allPemeriksaan = Pemeriksaan::where('kategori_pasien', 'lansia')
            ->whereIn('id', function($q) {
                $q->selectRaw('MAX(id)')->from('pemeriksaans')->groupBy('pasien_id');
            })->get();

        $statistik = (object) [
            'normal' => $allPemeriksaan->filter(function($p) {
                $tensi = intval(explode('/', $p->tekanan_darah)[0] ?? 0);
                return $tensi > 0 && $tensi < 120;
            })->count(),

            'hipertensi' => $allPemeriksaan->filter(function($p) {
                $tensi = intval(explode('/', $p->tekanan_darah)[0] ?? 0);
                // KEMBALI KE 'diagnosa'
                $textDiagnosa = strtolower($p->diagnosa ?? '');
                return $tensi >= 140 || str_contains($textDiagnosa, 'hipertensi');
            })->count(),

            'kritis' => $allPemeriksaan->filter(function($p) {
                $tensi = intval(explode('/', $p->tekanan_darah)[0] ?? 0);
                return $tensi >= 180;
            })->count(),
        ];

        $lansiaBerisiko = Lansia::whereHas('pemeriksaan_terakhir', function($q) {
            $q->whereRaw("CAST(SUBSTRING_INDEX(tekanan_darah, '/', 1) AS UNSIGNED) >= 140");
        })->take(3)->get();
        
        if ($request->ajax()) {
            return view('bidan.pasien.partials.table_lansia', compact('lansias'))->render();
        }

        return view('bidan.pasien.lansia', compact('lansias', 'statistik', 'lansiaBerisiko'));
    }

    public function laporanLansia()
    {
        $lansias = Lansia::with('pemeriksaan_terakhir')
            ->orderBy('nama_lengkap', 'asc')
            ->get();
        
        return view('bidan.laporan.lansia_print', compact('lansias'));
    }
}