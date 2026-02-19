<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

// Models
use App\Models\User;
use App\Models\Balita;
use App\Models\Remaja;
use App\Models\Lansia;
use App\Models\JadwalPosyandu;
use App\Models\Notifikasi;
use App\Models\Pemeriksaan;

class DashboardController extends Controller
{
    /**
     * Halaman Utama Dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // 1. Deteksi Peran & NIK
        $dataPeran = $this->getPeranUser($user);
        $peranUser = $dataPeran['roles'];
        $nikUser   = $dataPeran['nik'];

        // 2. Ambil Data Kesehatan Sesuai Peran
        $dataAnak = collect();
        $dataRemaja = null;
        $dataLansia = null;
        $grafikData = [];

        if ($nikUser) {
            // Jika Orang Tua -> Ambil Data Balita
            if (in_array('orang_tua', $peranUser)) {
                $dataAnak = Balita::where(function($query) use ($nikUser) {
                        $query->where('nik_ibu', $nikUser)
                              ->orWhere('nik_ayah', $nikUser);
                    })
                    ->with(['pemeriksaan_terakhir']) 
                    ->orderBy('tanggal_lahir', 'desc')
                    ->get();

                // Ambil data grafik untuk anak pertama (jika ada)
                if ($dataAnak->isNotEmpty()) {
                    $grafikData = $this->getGrafikBalita($dataAnak->first()->id);
                }
            }

            // Jika Remaja -> Ambil Data Diri Remaja
            if (in_array('remaja', $peranUser)) {
                $dataRemaja = Remaja::where('nik', $nikUser)
                    ->with('pemeriksaan_terakhir')
                    ->first();
            }

            // Jika Lansia -> Ambil Data Diri Lansia
            if (in_array('lansia', $peranUser)) {
                $dataLansia = Lansia::where('nik', $nikUser)
                    ->with('pemeriksaan_terakhir')
                    ->first();
            }
        }

        // 3. Widget Data Umum (Jadwal & Notifikasi)
        // Ambil jadwal terdekat (hari ini ke depan)
        $jadwalTerdekat = $this->getJadwalQuery($peranUser)->take(5)->get();
        
        // Ambil notifikasi awal (untuk render pertama)
        $notifikasiTerbaru = Notifikasi::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        $totalNotifikasiBelumDibaca = Notifikasi::where('user_id', $user->id)
            ->whereNull('read_at') // Gunakan whereNull untuk read_at
            ->count();

        // 4. Statistik Sederhana
        $statistik = [
            'total_anak' => $dataAnak->count(),
            'notifikasi' => $totalNotifikasiBelumDibaca,
        ];

        // Pesan error jika NIK kosong tapi user login
        $pesanError = empty($nikUser) ? 'NIK belum terdaftar di sistem. Mohon lengkapi profil atau hubungi kader.' : null;

        return view('user.dashboard', compact(
            'user', 
            'peranUser', 
            'dataAnak', 
            'dataRemaja', 
            'dataLansia', 
            'grafikData', 
            'jadwalTerdekat', 
            'notifikasiTerbaru', 
            'totalNotifikasiBelumDibaca', 
            'statistik', 
            'pesanError'
        ));
    }

    /**
     * Endpoint API untuk AJAX Polling (Realtime Notification)
     * PERBAIKAN UTAMA ADA DI SINI
     */
    public function getLatestNotifications()
    {
        $user = auth()->user();
        
        // 1. Ambil Notifikasi & Format Datanya
        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function($notif) {
                return [
                    'id' => $notif->id,
                    'judul' => $notif->judul,
                    'pesan' => Str::limit($notif->pesan, 80),
                    'waktu' => $notif->created_at->diffForHumans(),
                    // LOGIKA IS_READ DIPINDAHKAN KE DALAM SINI (SCOPE YANG BENAR)
                    'is_read' => $notif->read_at !== null, 
                    'type' => $notif->type ?? 'info'
                ];
            });

        // 2. Hitung Jumlah Belum Dibaca
        $unreadCount = Notifikasi::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        // 3. Kirim Response JSON
        return response()->json([
            'status' => 'success',
            'notifikasi' => $notifikasi,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Menampilkan halaman lihat semua notifikasi
     */
    public function notifikasi()
    {
        $user = auth()->user();
        
        // Ambil semua notifikasi, paginate 10 per halaman
        $notifikasi = Notifikasi::where('user_id', $user->id)
            ->latest()
            ->paginate(10);
            
        // Tandai semua sebagai sudah dibaca saat membuka halaman ini
        Notifikasi::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('user.notifikasi.index', compact('notifikasi'));
    }

    // ==========================================
    // PRIVATE HELPER METHODS
    // ==========================================

    private function getPeranUser($user)
    {
        $roles = [];
        $nik = $user->nik ?? ($user->profile->nik ?? $user->username);

        if ($nik) {
            $isOrtu = Balita::where('nik_ibu', $nik)->orWhere('nik_ayah', $nik)->exists();
            if ($isOrtu) $roles[] = 'orang_tua';
            if (Remaja::where('nik', $nik)->exists()) $roles[] = 'remaja';
            if (Lansia::where('nik', $nik)->exists()) $roles[] = 'lansia';
        }

        if (empty($roles)) $roles[] = 'umum';

        return ['nik' => $nik, 'roles' => $roles];
    }

    private function getJadwalQuery($peranUser)
    {
        // PERBAIKAN: Hapus whereDate(Carbon::today())
        // Sekarang logika: Tampilkan semua jadwal yang statusnya 'aktif'
        $query = JadwalPosyandu::where('status', 'aktif')
            ->orderBy('tanggal', 'desc'); // Tampilkan dari yang terbaru

        $targets = ['semua'];
        if (in_array('orang_tua', $peranUser)) $targets[] = 'balita';
        if (in_array('remaja', $peranUser))    $targets[] = 'remaja';
        if (in_array('lansia', $peranUser))    $targets[] = 'lansia';

        return $query->whereIn('target_peserta', $targets);
    }

    private function getGrafikBalita($balitaId)
    {
        $riwayat = Pemeriksaan::where('pasien_id', $balitaId)
                    ->where('kategori_pasien', 'balita')
                    ->orderBy('tanggal_periksa', 'asc') 
                    ->take(12)
                    ->get();

        if ($riwayat->isEmpty()) return [];

        return [
            'labels' => $riwayat->map(fn($item) => Carbon::parse($item->tanggal_periksa)->format('d M y'))->toArray(),
            'berat'  => $riwayat->pluck('berat_badan')->toArray(),
            'tinggi' => $riwayat->pluck('tinggi_badan')->toArray(),
        ];
    }
}