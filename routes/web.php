<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\HomeController;

// Controller Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BidanController as AdminBidanController;
use App\Http\Controllers\Admin\KaderController as AdminKaderController;
use App\Http\Controllers\Admin\PasienController as AdminPasienController;
use App\Http\Controllers\Admin\SettingController;

// Controller Bidan
use App\Http\Controllers\Bidan\DashboardController as BidanDashboard;
use App\Http\Controllers\Bidan\PemeriksaanController as BidanPemeriksaan;
use App\Http\Controllers\Bidan\JadwalController as BidanJadwal;
use App\Http\Controllers\Bidan\PasienController as BidanPasien;

// Controller Kader
use App\Http\Controllers\Kader\DashboardController as KaderDashboard;
use App\Http\Controllers\Kader\BalitaController;
use App\Http\Controllers\Kader\RemajaController;
use App\Http\Controllers\Kader\LansiaController;
use App\Http\Controllers\Kader\PemeriksaanController;
use App\Http\Controllers\Kader\ImunisasiController; // Penting
use App\Http\Controllers\Kader\KunjunganController; // Penting
use App\Http\Controllers\Kader\LaporanController;
use App\Http\Controllers\Kader\JadwalController;
use App\Http\Controllers\Kader\ImportController;
use App\Http\Controllers\Kader\ProfileController as KaderProfile;

// Controller User (Warga)
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\BalitaController as UserBalita;
use App\Http\Controllers\User\RemajaController as UserRemaja;
use App\Http\Controllers\User\LansiaController as UserLansia;
use App\Http\Controllers\User\RiwayatController;
use App\Http\Controllers\User\KonselingController;
use App\Http\Controllers\User\JadwalController as UserJadwal;
use App\Http\Controllers\User\ProfileController as UserProfile;

/*
|--------------------------------------------------------------------------
| Web Routes - COMPLETE & STANDARDIZED VERSION
|--------------------------------------------------------------------------
*/

// ==================== ROOT & PUBLIC ROUTES ====================
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// ==================== AUTHENTICATION ROUTES ====================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout Routes
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get');

// ==================== GLOBAL AUTHENTICATED ROUTES ====================
Route::middleware('auth')->group(function () {
    // Change Password
    Route::get('/password/change', [ChangePasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/password/change', [ChangePasswordController::class, 'change'])->name('password.change.post');
    
    // Home Redirector (Mengarahkan user ke dashboard sesuai role)
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// ==================== ADMIN ROUTES ====================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'checkstatus', 'role:admin'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [AdminDashboard::class, 'getStats'])->name('dashboard.stats');
    
    // User Management
    Route::resource('users', UserController::class);
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{id}/generate-password', [UserController::class, 'generatePassword'])->name('users.generate-password');

    // Bidan Management
    Route::resource('bidans', AdminBidanController::class);
    Route::post('/bidans/{id}/reset-password', [AdminBidanController::class, 'resetPassword'])->name('bidans.reset-password');
    
    // Kader Management
    Route::resource('kaders', AdminKaderController::class);
    Route::post('/kaders/{id}/reset-password', [AdminKaderController::class, 'resetPassword'])->name('kaders.reset-password');
    
    // Pasien Routes (Grouped)
    Route::prefix('pasien')->name('pasien.')->group(function() {
        // Balita
        Route::get('/balita', [AdminPasienController::class, 'balitaIndex'])->name('balita.index');
        Route::get('/balita/create', [AdminPasienController::class, 'balitaCreate'])->name('balita.create');
        Route::post('/balita', [AdminPasienController::class, 'balitaStore'])->name('balita.store');
        Route::get('/balita/{id}', [AdminPasienController::class, 'balitaShow'])->name('balita.show');
        Route::get('/balita/{id}/edit', [AdminPasienController::class, 'balitaEdit'])->name('balita.edit');
        Route::put('/balita/{id}', [AdminPasienController::class, 'balitaUpdate'])->name('balita.update');
        Route::delete('/balita/{id}', [AdminPasienController::class, 'balitaDestroy'])->name('balita.destroy');

        // Remaja
        Route::get('/remaja', [AdminPasienController::class, 'remajaIndex'])->name('remaja.index');
        Route::get('/remaja/create', [AdminPasienController::class, 'remajaCreate'])->name('remaja.create');
        Route::post('/remaja', [AdminPasienController::class, 'remajaStore'])->name('remaja.store');
        Route::get('/remaja/{id}', [AdminPasienController::class, 'remajaShow'])->name('remaja.show');
        Route::get('/remaja/{id}/edit', [AdminPasienController::class, 'remajaEdit'])->name('remaja.edit');
        Route::put('/remaja/{id}', [AdminPasienController::class, 'remajaUpdate'])->name('remaja.update');
        Route::delete('/remaja/{id}', [AdminPasienController::class, 'remajaDestroy'])->name('remaja.destroy');

        // Lansia
        Route::get('/lansia', [AdminPasienController::class, 'lansiaIndex'])->name('lansia.index');
        Route::get('/lansia/create', [AdminPasienController::class, 'lansiaCreate'])->name('lansia.create');
        Route::post('/lansia', [AdminPasienController::class, 'lansiaStore'])->name('lansia.store');
        Route::get('/lansia/{id}', [AdminPasienController::class, 'lansiaShow'])->name('lansia.show');
        Route::get('/lansia/{id}/edit', [AdminPasienController::class, 'lansiaEdit'])->name('lansia.edit');
        Route::put('/lansia/{id}', [AdminPasienController::class, 'lansiaUpdate'])->name('lansia.update');
        Route::delete('/lansia/{id}', [AdminPasienController::class, 'lansiaDestroy'])->name('lansia.destroy');
    });

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    Route::get('/', fn() => redirect()->route('admin.dashboard'));
});

// ==================== BIDAN ROUTES ====================
Route::prefix('bidan')->name('bidan.')->middleware(['auth', 'checkstatus', 'role:bidan'])->group(function () {
    
    Route::get('/dashboard', [BidanDashboard::class, 'index'])->name('dashboard');
    
    // Pemeriksaan
    Route::get('/pemeriksaan', [BidanPemeriksaan::class, 'index'])->name('pemeriksaan.index');
    Route::get('/pemeriksaan/create', [BidanPemeriksaan::class, 'create'])->name('pemeriksaan.create');
    Route::post('/pemeriksaan', [BidanPemeriksaan::class, 'store'])->name('pemeriksaan.store');
    Route::get('/pemeriksaan/{id}', [BidanPemeriksaan::class, 'show'])->name('pemeriksaan.show');
    Route::post('/pemeriksaan/{id}/verifikasi', [BidanPemeriksaan::class, 'verifikasi'])->name('pemeriksaan.verifikasi');
    Route::post('/pemeriksaan/{id}/verifikasi-cepat', [BidanPemeriksaan::class, 'verifikasiCepat'])->name('pemeriksaan.verifikasi-cepat');

    // Jadwal
    Route::resource('jadwal', BidanJadwal::class);

    // Pasien (Read Only)
    Route::get('/pasien/balita', [BidanPasien::class, 'indexBalita'])->name('pasien.balita');
    Route::get('/pasien/remaja', [BidanPasien::class, 'indexRemaja'])->name('pasien.remaja');
    Route::get('/pasien/lansia', [BidanPasien::class, 'indexLansia'])->name('pasien.lansia');
    
    // Laporan
    Route::get('/laporan/lansia', [BidanPasien::class, 'laporanLansia'])->name('laporan.lansia');
    Route::get('/laporan/balita', [BidanPasien::class, 'laporanBalita'])->name('laporan.balita');
    Route::get('/laporan/remaja', [BidanPasien::class, 'laporanRemaja'])->name('laporan.remaja');
    
    // Laporan (ringkas — generate di browser, tidak simpan file)
    Route::get('/laporan', [\App\Http\Controllers\Bidan\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [\App\Http\Controllers\Bidan\LaporanController::class, 'cetak'])->name('laporan.cetak');

    Route::get('/', fn() => redirect()->route('bidan.dashboard'));
}); 
Route::prefix('kader')->name('kader.')->middleware(['auth', 'checkstatus', 'role:kader'])->group(function () {
    
    Route::get('/dashboard', [KaderDashboard::class, 'index'])->name('dashboard');
    
    // 1. CRUD Data Pasien
    Route::prefix('data')->name('data.')->group(function () {
        Route::resource('balita', BalitaController::class);
        Route::resource('remaja', RemajaController::class);
        Route::resource('lansia', LansiaController::class);
    });
    
    // 2. Pemeriksaan (Termasuk Filter & Delete)
    Route::prefix('pemeriksaan')->name('pemeriksaan.')->group(function () {
        Route::get('/', [PemeriksaanController::class, 'index'])->name('index');
        Route::get('/create', [PemeriksaanController::class, 'create'])->name('create');
        Route::post('/', [PemeriksaanController::class, 'store'])->name('store');
        Route::get('/{id}', [PemeriksaanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PemeriksaanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PemeriksaanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PemeriksaanController::class, 'destroy'])->name('destroy'); // Rute Delete
        
        // Filter Routes (Opsional jika pakai query string, tapi disiapkan saja)
        Route::get('/filter/balita', [PemeriksaanController::class, 'balita'])->name('balita');
        Route::get('/filter/remaja', [PemeriksaanController::class, 'remaja'])->name('remaja');
        Route::get('/filter/lansia', [PemeriksaanController::class, 'lansia'])->name('lansia');
    });

    // 3. Imunisasi (DITAMBAHKAN AGAR TIDAK ERROR)
    // Index & Detail & Hapus
    Route::get('/imunisasi', [ImunisasiController::class, 'index'])->name('imunisasi.index');
    Route::get('/imunisasi/{id}', [ImunisasiController::class, 'show'])->name('imunisasi.show');
    Route::delete('/imunisasi/{id}', [ImunisasiController::class, 'destroy'])->name('imunisasi.destroy');
    // Create & Store via Kunjungan
    Route::get('/kunjungan/{kunjungan_id}/imunisasi/create', [ImunisasiController::class, 'create'])->name('imunisasi.create');
    Route::post('/kunjungan/{kunjungan_id}/imunisasi', [ImunisasiController::class, 'store'])->name('imunisasi.store');

    // 4. Riwayat Kunjungan (DITAMBAHKAN)
    Route::resource('kunjungan', KunjunganController::class);
    
    // 5. Laporan
   Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/balita', [LaporanController::class, 'balita'])->name('balita');
        Route::get('/remaja', [LaporanController::class, 'remaja'])->name('remaja');
        Route::get('/lansia', [LaporanController::class, 'lansia'])->name('lansia');
        Route::get('/imunisasi', [LaporanController::class, 'imunisasi'])->name('imunisasi');
        Route::get('/kunjungan', [LaporanController::class, 'kunjungan'])->name('kunjungan');
        Route::get('/generate/{type}', [LaporanController::class, 'generate'])->name('generate');
        Route::get('/download/{filename}', [LaporanController::class, 'download'])->name('download');
        Route::get('/cetak', [LaporanController::class, 'cetak'])->name('cetak');
    });
    
    // 6. Jadwal
    Route::resource('jadwal', JadwalController::class);
    Route::post('/jadwal/broadcast/{id}', [JadwalController::class, 'broadcast'])->name('jadwal.broadcast');
    
    // 7. Import Data
    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/', [ImportController::class, 'index'])->name('index');
        Route::get('/create', [ImportController::class, 'create'])->name('create');
        Route::post('/', [ImportController::class, 'store'])->name('store');
        Route::get('/history', [ImportController::class, 'history'])->name('history'); // Perbaikan rute history
        Route::get('/download-template/{type}', [ImportController::class, 'downloadTemplate'])->name('download-template');
        Route::get('/{id}', [ImportController::class, 'show'])->name('show');
    });
    
    // 8. Profile Kader
    Route::get('/profile', [KaderProfile::class, 'index'])->name('profile.index');
    Route::put('/profile', [KaderProfile::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [KaderProfile::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [KaderProfile::class, 'updatePassword'])->name('profile.update-password');
    
    Route::get('/', fn() => redirect()->route('kader.dashboard'));
});

// ==================== USER ROUTES (WARGA) ====================
Route::prefix('user')->name('user.')->middleware(['auth', 'checkstatus', 'role:user'])->group(function () {
    
    // 1. Dashboard Utama
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
    Route::get('/stats', [UserDashboard::class, 'getStats'])->name('stats');
    
    // 2. Fitur Data Kesehatan
    Route::resource('balita', UserBalita::class);
    Route::get('imunisasi', [UserBalita::class, 'imunisasi'])->name('imunisasi.index');
    
    Route::resource('remaja', UserRemaja::class);
    // HAPUS baris ini karena sudah dipindah ke KonselingController
    // Route::get('konseling', [UserRemaja::class, 'konseling'])->name('konseling.index'); 
    
    Route::resource('lansia', UserLansia::class);

    // 3. Riwayat Umum
    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    
    // 4. Jadwal & Notifikasi
    Route::get('/jadwal', [UserJadwal::class, 'index'])->name('jadwal.index'); 
    
    Route::get('/notifikasi', [UserDashboard::class, 'notifikasi'])->name('notifikasi.index');
    Route::get('/notifications/latest', [UserDashboard::class, 'getLatestNotifications'])->name('notifikasi.latest');

    // 5. FITUR KONSELING TERPUSAT (BARU)
    // Ini perbaikan intinya: Mengarah ke KonselingController
    Route::get('/konseling', [KonselingController::class, 'index'])->name('konseling.index');

    // 6. Profil
    Route::get('/profile', [UserProfile::class, 'edit'])->name('profile.index'); 
    Route::patch('/profile', [UserProfile::class, 'update'])->name('profile.update');
    
    Route::get('/', fn() => redirect()->route('user.dashboard'));
});

// Route Profile Umum (Fallback)
Route::middleware('auth')->get('/profile', [UserProfile::class, 'edit'])->name('profile.edit');