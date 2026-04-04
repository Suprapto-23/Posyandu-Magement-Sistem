<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil; // WAJIB DITAMBAHKAN
use App\Models\Pemeriksaan;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
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

            case 'ibu_hamil': // DUKUNGAN UNTUK IBU HAMIL DITAMBAHKAN
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
            'ibu_hamil' => 'App\Models\IbuHamil', // DUKUNGAN UNTUK IBU HAMIL DITAMBAHKAN
            default     => null,
        };
    }
}