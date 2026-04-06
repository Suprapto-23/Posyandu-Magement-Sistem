<?php

namespace App\Http\Controllers\Bidan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\JadwalPosyandu;
use App\Models\Notifikasi;
use App\Models\User;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\IbuHamil; // WAJIB DIPANGGIL

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPosyandu::orderBy('tanggal', 'desc')->paginate(10);
        return view('bidan.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        return view('bidan.jadwal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'          => 'required|string|max:191',
            'tanggal'        => 'required|date',
            'waktu_mulai'    => 'required',
            'waktu_selesai'  => 'required',
            'lokasi'         => 'required|string',
            'kategori'       => 'required|in:imunisasi,pemeriksaan,konseling,posyandu,lainnya',
            'target_peserta' => 'required|in:semua,balita,remaja,lansia,ibu_hamil',
        ]);

        DB::beginTransaction();
        try {
            JadwalPosyandu::create([
                'judul'          => $request->judul,
                'deskripsi'      => $request->deskripsi,
                'tanggal'        => $request->tanggal,
                'waktu_mulai'    => $request->waktu_mulai,
                'waktu_selesai'  => $request->waktu_selesai,
                'lokasi'         => $request->lokasi,
                'kategori'       => $request->kategori,
                'target_peserta' => $request->target_peserta,
                'status'         => 'aktif',
                'created_by'     => Auth::id()
            ]);

            // Kirim notifikasi cerdas ke Warga dan Kader
            $this->kirimNotifikasi($request);

            DB::commit();
            return redirect()->route('bidan.jadwal.index')
                ->with('success', 'Jadwal berhasil dipublikasikan! Notifikasi telah disebar ke HP Warga & Kader.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Gagal menyimpan jadwal: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $jadwal = JadwalPosyandu::findOrFail($id);
        return view('bidan.jadwal.edit', compact('jadwal'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalPosyandu::findOrFail($id);
        
        $request->validate([
            'judul'         => 'required|string|max:191',
            'tanggal'       => 'required|date',
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required',
            'status'        => 'required|in:aktif,selesai,dibatalkan',
        ]);
        
        $jadwal->update($request->except(['_token', '_method']));
        
        return redirect()->route('bidan.jadwal.index')
            ->with('success', 'Perubahan agenda jadwal berhasil disimpan.');
    }

    public function destroy($id)
    {
        JadwalPosyandu::findOrFail($id)->delete();
        return redirect()->route('bidan.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus permanen dari sistem.');
    }

    // ========================================================================
    // LOGIKA BROADCAST NOTIFIKASI (BUG FIXED & DIUPGRADE)
    // ========================================================================
    private function kirimNotifikasi($request)
    {
        $targetUsers = collect();

        if ($request->target_peserta == 'semua') {
            $targetUsers = User::where('role', 'user')->where('status', 'active')->pluck('id');
        } elseif ($request->target_peserta == 'balita') {
            $nikOrtu = Balita::pluck('nik_ibu')->merge(Balita::pluck('nik_ayah'))->filter()->unique();
            $targetUsers = User::whereIn('nik', $nikOrtu)->pluck('id');
        } elseif ($request->target_peserta == 'ibu_hamil') {
            // FIX: Sebelumnya tergabung dengan balita. Sekarang mengambil dari tabel IbuHamil langsung.
            $nikBumil = IbuHamil::pluck('nik')->filter()->unique();
            $targetUsers = User::whereIn('nik', $nikBumil)->pluck('id');
        } elseif ($request->target_peserta == 'remaja') {
            $nikRemaja = Remaja::pluck('nik')->filter()->unique();
            $targetUsers = User::whereIn('nik', $nikRemaja)->pluck('id');
        } elseif ($request->target_peserta == 'lansia') {
            $nikLansia = Lansia::pluck('nik')->filter()->unique();
            $targetUsers = User::whereIn('nik', $nikLansia)->pluck('id');
        }

        // Kader selalu menerima notifikasi agar mereka bisa bersiap di Meja 1-4
        $kaderUsers = User::where('role', 'kader')->pluck('id');

        // Gabungkan ID tanpa duplikat
        $allTargetIds = $targetUsers->merge($kaderUsers)->unique();

        $tanggalFormat = Carbon::parse($request->tanggal)->translatedFormat('d F Y');
        $judulNotif = "Jadwal Baru: " . $request->judul;
        $pesanNotif = "Pemberitahuan kegiatan {$request->kategori} di {$request->lokasi} pada tanggal {$tanggalFormat}. Harap hadir tepat waktu.";

        $notifData = [];
        $now = now();
        
        foreach ($allTargetIds as $userId) {
            $notifData[] = [
                'user_id'    => $userId,
                'judul'      => $judulNotif,
                'pesan'      => $pesanNotif,
                'tipe'       => 'jadwal',
                'is_read'    => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Batch Insert (Lebih cepat dan aman untuk ratusan user)
        if (!empty($notifData)) {
            $chunks = array_chunk($notifData, 500);
            foreach ($chunks as $chunk) {
                Notifikasi::insert($chunk);
            }
        }
    }
}