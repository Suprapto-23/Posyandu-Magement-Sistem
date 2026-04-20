<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil;
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // =========================================================================
        // 🌟 FITUR LIVE SEARCH (AJAX) - MENCARI NAMA LANGSUNG DARI DATABASE
        // =========================================================================
        if ($request->ajax() || $request->wantsJson()) {
            if (!$search) return response()->json([]);

            $results = collect();
            
            // Mencari di semua tabel secara bersamaan, dibatasi 5 hasil per kategori agar cepat
            $balitas = Balita::where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%")->take(5)->get()->map(fn($i) => ['id' => $i->id, 'nama' => $i->nama_lengkap, 'type' => 'balita', 'kategori' => 'Balita & Anak', 'icon' => 'fa-baby', 'color' => 'rose']);
            $bumils  = IbuHamil::where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%")->take(5)->get()->map(fn($i) => ['id' => $i->id, 'nama' => $i->nama_lengkap, 'type' => 'ibu_hamil', 'kategori' => 'Ibu Hamil', 'icon' => 'fa-female', 'color' => 'pink']);
            $remajas = Remaja::where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%")->take(5)->get()->map(fn($i) => ['id' => $i->id, 'nama' => $i->nama_lengkap, 'type' => 'remaja', 'kategori' => 'Usia Remaja', 'icon' => 'fa-user-graduate', 'color' => 'indigo']);
            $lansias = Lansia::where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%")->take(5)->get()->map(fn($i) => ['id' => $i->id, 'nama' => $i->nama_lengkap, 'type' => 'lansia', 'kategori' => 'Lansia', 'icon' => 'fa-wheelchair', 'color' => 'emerald']);

            // Gabungkan semua hasil temuan dan kembalikan sebagai format JSON
            $results = $results->merge($balitas)->merge($bumils)->merge($remajas)->merge($lansias);
            
            return response()->json($results);
        }
        // =========================================================================

        $type = $request->get('type', 'balita'); // Default ke balita
        
        switch ($type) {
            case 'balita':
                $query = Balita::query();
                if ($search) $query->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%");
                $data = $query->paginate(10);
                break;
                
            case 'remaja':
                $query = Remaja::query();
                if ($search) $query->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%");
                $data = $query->paginate(10);
                break;
                
            case 'lansia':
                $query = Lansia::query();
                if ($search) $query->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%");
                $data = $query->paginate(10);
                break;

            case 'ibu_hamil':
                $query = IbuHamil::query();
                if ($search) $query->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nik', 'like', "%{$search}%");
                $data = $query->paginate(10);
                break;
                
            default:
                $data = collect();
        }
        
        return view('bidan.rekam-medis.index', compact('data', 'type', 'search'));
    }

    public function show($pasien_type, $pasien_id)
    {
        $modelClass = $this->getModelClass($pasien_type);
        $pasien = $modelClass::findOrFail($pasien_id);
        
        $kunjungans = Pemeriksaan::where('pasien_id', $pasien_id)
            ->where('kategori_pasien', $pasien_type)
            ->latest('tanggal_periksa')
            ->get();
            
        return view('bidan.rekam-medis.show', compact('pasien', 'kunjungans', 'pasien_type'));
    }

    private function getModelClass($type)
    {
        return match($type) {
            'balita'    => 'App\Models\Balita',
            'remaja'    => 'App\Models\Remaja',
            'lansia'    => 'App\Models\Lansia',
            'ibu_hamil' => 'App\Models\IbuHamil',
            default     => null,
        };
    }
}