<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Gunakan Model yang Benar
use App\Models\JadwalPosyandu;
use App\Models\Notifikasi;
use App\Models\User;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;

class JadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal (READ)
     */
    public function index()
    {
        // Menggunakan $jadwals (jamak) sesuai permintaan View
        $jadwals = JadwalPosyandu::orderBy('tanggal', 'desc')->paginate(10);
        
        return view('bidan.jadwal.index', compact('jadwals'));
    }

    /**
     * Form tambah jadwal (CREATE)
     */
    public function create()
    {
        return view('bidan.jadwal.create');
    }

    /**
     * Simpan jadwal baru & Kirim Notifikasi (STORE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:191',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'lokasi' => 'required|string',
            'kategori' => 'required|in:imunisasi,pemeriksaan,konseling,posyandu,lainnya',
            'target_peserta' => 'required|in:semua,balita,remaja,lansia,ibu_hamil',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Data ke Database
            JadwalPosyandu::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $request->waktu_mulai,   // Format: HH:mm
                'waktu_selesai' => $request->waktu_selesai, // Format: HH:mm
                'lokasi' => $request->lokasi,
                'kategori' => $request->kategori,
                'target_peserta' => $request->target_peserta,
                'status' => 'aktif',
                'created_by' => Auth::id()
            ]);

            // 2. Kirim Notifikasi ke Warga
            $this->kirimNotifikasi($request);

            DB::commit();
            return redirect()->route('bidan.jadwal.index')
                ->with('success', 'Jadwal berhasil dibuat dan notifikasi dikirim!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan jadwal: ' . $e->getMessage());
        }
    }

    /**
     * Form edit jadwal (EDIT)
     */
    public function edit($id)
    {
        $jadwal = JadwalPosyandu::findOrFail($id);
        return view('bidan.jadwal.edit', compact('jadwal'));
    }

    /**
     * Update data jadwal (UPDATE)
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalPosyandu::findOrFail($id);
        
        $request->validate([
            'judul' => 'required|string|max:191',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'status' => 'required|in:aktif,selesai,dibatalkan',
        ]);

        $jadwal->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'lokasi' => $request->lokasi,
            'kategori' => $request->kategori,
            'target_peserta' => $request->target_peserta,
            'status' => $request->status,
        ]);

        return redirect()->route('bidan.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    /**
     * Hapus jadwal (DELETE)
     */
    public function destroy($id)
    {
        $jadwal = JadwalPosyandu::findOrFail($id);
        $jadwal->delete();
        
        return redirect()->route('bidan.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }

    /**
     * Helper: Logika Kirim Notifikasi
     */
    private function kirimNotifikasi($request)
    {
        $targetUsers = collect();

        // Filter User berdasarkan Target Peserta
        if ($request->target_peserta == 'semua') {
            $targetUsers = User::where('role', 'user')->where('status', 'active')->pluck('id');
        } 
        elseif ($request->target_peserta == 'balita' || $request->target_peserta == 'ibu_hamil') {
            $nikOrtu = Balita::pluck('nik_ibu')->merge(Balita::pluck('nik_ayah'))->filter()->unique();
            $targetUsers = User::whereIn('nik', $nikOrtu)->pluck('id');
        } 
        elseif ($request->target_peserta == 'remaja') {
            $nikRemaja = Remaja::pluck('nik');
            $targetUsers = User::whereIn('nik', $nikRemaja)->pluck('id');
        } 
        elseif ($request->target_peserta == 'lansia') {
            $nikLansia = Lansia::pluck('nik');
            $targetUsers = User::whereIn('nik', $nikLansia)->pluck('id');
        }

        // Buat Pesan
        $tanggalFormat = Carbon::parse($request->tanggal)->translatedFormat('d F Y');
        $judulNotif = "Jadwal Baru: " . $request->judul;
        $pesanNotif = "Halo! Ada kegiatan {$request->kategori} di {$request->lokasi} pada tanggal {$tanggalFormat}.";

        // Insert Batch (Lebih Cepat)
        $notifData = [];
        $now = now();
        
        foreach ($targetUsers as $userId) {
            $notifData[] = [
                'user_id' => $userId,
                'judul' => $judulNotif,
                'pesan' => $pesanNotif,
                'tipe' => 'jadwal',
                'is_read' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($notifData)) {
            Notifikasi::insert($notifData);
        }
    }
}