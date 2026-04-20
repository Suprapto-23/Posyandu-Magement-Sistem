<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\JadwalPosyandu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        // 1. ENGINE "LAZY UPDATE" (Otomatis Selesai jika waktu terlewat)
        // Mengecek jadwal aktif yang sudah melewati tanggal dan waktu selesai
        $now = Carbon::now('Asia/Jakarta');
        
        JadwalPosyandu::where('status', 'aktif')
            ->where(function ($query) use ($now) {
                // Skenario A: Jika tanggal kegiatan adalah kemarin atau lebih lama
                $query->whereDate('tanggal', '<', $now->toDateString())
                      // Skenario B: Jika tanggal adalah hari ini, TAPI jam selesai sudah lewat
                      ->orWhere(function ($q) use ($now) {
                          $q->whereDate('tanggal', '=', $now->toDateString())
                            ->whereTime('waktu_selesai', '<', $now->toTimeString());
                      });
            })->update(['status' => 'selesai']);


        // 2. QUERY GET DATA (Dengan Filter & Search)
        $search = $request->get('search');
        $status = $request->get('status', 'semua');

        $query = JadwalPosyandu::query()->orderBy('tanggal', 'desc');

        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $jadwals = $query->paginate(12)->withQueryString();

        // AJAX Live Search Output
        if ($request->ajax()) {
            return view('kader.jadwal.index', compact('jadwals', 'search', 'status'))->render();
        }

        return view('kader.jadwal.index', compact('jadwals', 'search', 'status'));
    }

    public function create()
    {
        return view('kader.jadwal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'          => 'required|string|max:255',
            'kategori'       => 'required|in:kesehatan_ibu_anak,imunisasi,penyuluhan,pemeriksaan_lansia,lainnya',
            'target_peserta' => 'required|in:semua,balita,remaja,ibu_hamil,lansia',
            'tanggal'        => 'required|date',
            'waktu_mulai'    => 'required',
            'waktu_selesai'  => 'required',
            'lokasi'         => 'required|string|max:255',
            'deskripsi'      => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();
            $jadwal = JadwalPosyandu::create([
                'judul'          => $request->judul,
                'kategori'       => $request->kategori,
                'target_peserta' => $request->target_peserta,
                'tanggal'        => $request->tanggal,
                'waktu_mulai'    => $request->waktu_mulai,
                'waktu_selesai'  => $request->waktu_selesai,
                'lokasi'         => $request->lokasi,
                'deskripsi'      => $request->deskripsi,
                'status'         => 'aktif',
                'created_by'     => auth()->id(),
            ]);
            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Jadwal berhasil dibuat!',
                    'redirect' => route('kader.jadwal.index')
                ]);
            }
            return redirect()->route('kader.jadwal.index')->with('success', 'Jadwal berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) return response()->json(['status' => 'error', 'message' => 'Gagal: ' . $e->getMessage()], 500);
            return back()->with('error', 'Gagal membuat jadwal.');
        }
    }

    public function show($id)
    {
        $jadwal = JadwalPosyandu::findOrFail($id);
        return view('kader.jadwal.show', compact('jadwal'));
    }

    public function edit($id)
    {
        $jadwal = JadwalPosyandu::findOrFail($id);
        return view('kader.jadwal.edit', compact('jadwal'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalPosyandu::findOrFail($id);

        $request->validate([
            'judul'          => 'required|string|max:255',
            'kategori'       => 'required|in:kesehatan_ibu_anak,imunisasi,penyuluhan,pemeriksaan_lansia,lainnya',
            'target_peserta' => 'required|in:semua,balita,remaja,ibu_hamil,lansia',
            'tanggal'        => 'required|date',
            'waktu_mulai'    => 'required',
            'waktu_selesai'  => 'required',
            'lokasi'         => 'required|string|max:255',
            'status'         => 'required|in:aktif,selesai,dibatalkan', // Tepat sesuai database
            'deskripsi'      => 'nullable|string'
        ]);

        try {
            $jadwal->update($request->all());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Jadwal berhasil diperbarui!',
                    'redirect' => route('kader.jadwal.show', $jadwal->id)
                ]);
            }
            return redirect()->route('kader.jadwal.show', $jadwal->id)->with('success', 'Jadwal berhasil diperbarui!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) return response()->json(['status' => 'error', 'message' => 'Gagal: ' . $e->getMessage()], 500);
            return back()->with('error', 'Gagal memperbarui jadwal.');
        }
    }

    public function destroy($id)
    {
        JadwalPosyandu::findOrFail($id)->delete();
        return redirect()->route('kader.jadwal.index')->with('success', 'Jadwal berhasil dihapus secara permanen.');
    }

    /**
     * Broadcast Pengumuman ke Warga (AJAX Ready)
     */
    public function broadcast(Request $request, $id)
    {
        try {
            $jadwal = JadwalPosyandu::findOrFail($id);
            if ($jadwal->status !== 'aktif') {
                throw new \Exception("Hanya jadwal aktif yang bisa disiarkan ke warga.");
            }

            DB::transaction(function() use ($jadwal) {
                // Logika pencarian target user ID yang sangat spesifik
                $userIds = [];
                if ($jadwal->target_peserta === 'semua') {
                    // Ambil user yang ber-role 'user' atau 'warga'
                    if (\Schema::hasColumn('users', 'role')) {
                        $userIds = User::whereIn('role', ['user', 'warga'])->pluck('id')->toArray();
                    } else {
                        $userIds = User::pluck('id')->toArray(); // Sesuaikan dengan Permission Spatie jika perlu
                    }
                } else {
                    $table = match($jadwal->target_peserta) {
                        'balita' => 'balitas',
                        'remaja' => 'remajas',
                        'lansia' => 'lansias',
                        'ibu_hamil' => 'ibu_hamils',
                        default => null
                    };
                    if ($table && \Schema::hasTable($table)) {
                        $userIds = DB::table($table)->whereNotNull('user_id')->pluck('user_id')->toArray();
                    }
                }

                $userIds = array_unique($userIds);
                $notifikasiData = [];
                $waktuSekarang = Carbon::now('Asia/Jakarta');
                $tgl = Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y');
                $pesan = "Panggilan Pelayanan! Agenda: *{$jadwal->judul}* pada {$tgl}, pukul {$jadwal->waktu_mulai} di {$jadwal->lokasi}. Diharapkan kehadirannya.";

                foreach ($userIds as $userId) {
                    $notifikasiData[] = [
                        'user_id'    => $userId,
                        'judul'      => 'Jadwal Posyandu Baru',
                        'pesan'      => $pesan,
                        'is_read'    => false,
                        'created_at' => $waktuSekarang,
                        'updated_at' => $waktuSekarang,
                    ];
                }

                if (!empty($notifikasiData)) {
                    DB::table('notifikasis')->insert($notifikasiData);
                }
            });

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Pengumuman telah berhasil disebarkan ke aplikasi warga!']);
            }
            return back()->with('success', 'Broadcast berhasil dikirim!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            return back()->with('error', $e->getMessage());
        }
    }
}