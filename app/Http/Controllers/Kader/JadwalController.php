<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\JadwalPosyandu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
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

        // AJAX Live Search
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
            'status'         => 'required|in:aktif,selesai,batal',
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
        return redirect()->route('kader.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Broadcast Pengumuman ke Warga (AJAX Ready)
     */
    public function broadcast(Request $request, $id)
    {
        try {
            $jadwal = JadwalPosyandu::findOrFail($id);
            if ($jadwal->status !== 'aktif') {
                throw new \Exception("Hanya jadwal aktif yang bisa di-broadcast.");
            }

            DB::transaction(function() use ($jadwal) {
                // Logika pencarian target user ID (seperti di kode Anda sebelumnya)
                $userIds = [];
                if ($jadwal->target_peserta === 'semua') {
                    $userIds = User::where('role', 'warga')->pluck('id')->toArray();
                } else {
                    $table = match($jadwal->target_peserta) {
                        'balita' => 'balitas',
                        'remaja' => 'remajas',
                        'lansia' => 'lansias',
                        'ibu_hamil' => 'ibu_hamils',
                        default => null
                    };
                    if ($table) {
                        $userIds = DB::table($table)->whereNotNull('user_id')->pluck('user_id')->toArray();
                    }
                }

                $userIds = array_unique($userIds);
                $notifikasiData = [];
                $waktuSekarang = now();
                $tgl = \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y');
                $pesan = "Pengumuman Posyandu! Terdapat agenda: *{$jadwal->judul}* pada {$tgl}, pukul {$jadwal->waktu_mulai} di {$jadwal->lokasi}. Mohon kehadirannya.";

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