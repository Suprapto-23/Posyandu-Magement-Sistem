<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPosyandu; 

class JadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal (Read Only)
     * Data berasal dari inputan Bidan
     */
    public function index()
    {
        // Ambil jadwal yang statusnya aktif/akan datang dulu, baru yang lama
        $jadwals = JadwalPosyandu::orderBy('tanggal', 'desc')->paginate(10);
        
        return view('kader.jadwal.index', compact('jadwals'));
    }

    // Tidak ada method create, store, edit, update, destroy
    // Karena Kader hanya menerima jadwal.
}