<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use App\Models\Konseling;
use App\Models\Balita;
use App\Models\IbuHamil;
use App\Models\Remaja;
use App\Models\Lansia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonselingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // Menarik semua data konseling diurutkan dari yang terbaru
        $konselings = Konseling::latest('tanggal')->latest('waktu')->get();

        // Fitur Pencarian Nama Pasien (Search Filter)
        if ($search) {
            $konselings = $konselings->filter(function($k) use ($search) {
                return stripos(strtolower($k->pasien->nama_lengkap ?? ''), strtolower($search)) !== false;
            });
        }

        // Menyiapkan data pasien untuk dropdown di form 'Tambah Baru'
        $dataBalita = Balita::select('id', 'nama_lengkap')->get()->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_lengkap, 'type' => 'balita', 'label' => 'Anak & Balita']);
        $dataBumil  = IbuHamil::select('id', 'nama_lengkap')->get()->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_lengkap, 'type' => 'ibu_hamil', 'label' => 'Ibu Hamil']);
        $dataRemaja = Remaja::select('id', 'nama_lengkap')->get()->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_lengkap, 'type' => 'remaja', 'label' => 'Usia Remaja']);
        $dataLansia = Lansia::select('id', 'nama_lengkap')->get()->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_lengkap, 'type' => 'lansia', 'label' => 'Lansia (Manula)']);
        
        // Gabungkan semua data pasien ke dalam satu variabel untuk dropdown
        $allPatients = $dataBalita->merge($dataBumil)->merge($dataRemaja)->merge($dataLansia);

        return view('bidan.konseling.index', compact('konselings', 'search', 'allPatients'));
    }

    public function store(Request $request)
    {
        // Menyimpan data konseling (rekap chat WA) ke database
        $request->validate([
            'pasien_data' => 'required|string', // Formatnya nanti: type|id (misal: balita|5)
            'tanggal'     => 'required|date',
            'keluhan'     => 'required|string',
            'tindakan'    => 'required|string',
        ]);

        // Pecah data "type|id" yang dikirim dari form select
        $pasienInfo = explode('|', $request->pasien_data);

        Konseling::create([
            'pasien_type' => $pasienInfo[0],
            'pasien_id'   => $pasienInfo[1],
            'tanggal'     => $request->tanggal,
            'waktu'       => date('H:i'), // Jam saat ini
            'keluhan'     => $request->keluhan,
            'tindakan'    => $request->tindakan,
            'bidan_id'    => Auth::id() ?? 1,
        ]);

        return redirect()->back()->with('success', 'Catatan edukasi telemedisin berhasil diarsipkan ke sistem EMR.');
    }
}