<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $nikUser = $user->nik ?? $user->username; // Fallback jika kolom nik user null

        // Ambil ID Pasien berdasarkan NIK user
        $pasienIds = [];
        
        // Cek data di tabel Remaja
        $remaja = \App\Models\Remaja::where('nik', $nikUser)->first();
        if ($remaja) {
            $pasienIds[] = [
                'id' => $remaja->id, 
                'type' => 'App\Models\Remaja',
                'nama' => $remaja->nama_lengkap
            ];
        }

        // Cek data di tabel Lansia
        $lansia = \App\Models\Lansia::where('nik', $nikUser)->first();
        if ($lansia) {
            $pasienIds[] = [
                'id' => $lansia->id, 
                'type' => 'App\Models\Lansia',
                'nama' => $lansia->nama_lengkap
            ];
        }

        // Jika Orang Tua (Cek tabel Balita berdasarkan nik_ibu/nik_ayah)
        $balitas = \App\Models\Balita::where('nik_ibu', $nikUser)
                    ->orWhere('nik_ayah', $nikUser)
                    ->get();
        
        foreach($balitas as $balita) {
            $pasienIds[] = [
                'id' => $balita->id, 
                'type' => 'App\Models\Balita',
                'nama' => $balita->nama_lengkap
            ];
        }

        // QUERY UTAMA: Ambil kunjungan berdasarkan ID & Tipe Pasien yang ditemukan
        $riwayat = Kunjungan::with(['pemeriksaan', 'imunisasis']) // Eager load biar ringan
            ->where(function($query) use ($pasienIds) {
                foreach ($pasienIds as $pasien) {
                    $query->orWhere(function($q) use ($pasien) {
                        $q->where('pasien_id', $pasien['id'])
                          ->where('pasien_type', $pasien['type']);
                    });
                }
            })
            ->orderBy('tanggal_kunjungan', 'desc')
            ->paginate(10);

        // Inject nama pasien ke dalam collection hasil pagination
        $riwayat->getCollection()->transform(function ($item) use ($pasienIds) {
            // Cari nama pasien yang cocok dari array $pasienIds
            $p = collect($pasienIds)->first(function($val) use ($item) {
                return $val['id'] == $item->pasien_id && $val['type'] == $item->pasien_type;
            });
            
            $item->pasien_nama = $p ? $p['nama'] : '-';
            
            // Format label kategori untuk tampilan
            $item->kategori_display = match($item->pasien_type) {
                'App\Models\Balita' => 'Balita',
                'App\Models\Remaja' => 'Remaja',
                'App\Models\Lansia' => 'Lansia',
                default => 'Umum'
            };
            
            return $item;
        });

        return view('user.riwayat.index', compact('riwayat'));
    }
}