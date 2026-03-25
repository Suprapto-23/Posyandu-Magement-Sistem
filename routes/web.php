<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\HomeController;

// Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController      as AdminUser;
use App\Http\Controllers\Admin\BidanController     as AdminBidan;
use App\Http\Controllers\Admin\KaderController     as AdminKader;
use App\Http\Controllers\Admin\SettingController   as AdminSetting;

// Bidan
use App\Http\Controllers\Bidan\DashboardController   as BidanDashboard;
use App\Http\Controllers\Bidan\PemeriksaanController as BidanPemeriksaan;
use App\Http\Controllers\Bidan\JadwalController      as BidanJadwal;
use App\Http\Controllers\Bidan\PasienController      as BidanPasien;
use App\Http\Controllers\Bidan\RekamMedisController as BidanRekamMedisController;

// Kader
use App\Http\Controllers\Kader\DashboardController   as KaderDashboard;
use App\Http\Controllers\Kader\BalitaController;
use App\Http\Controllers\Kader\RemajaController;
use App\Http\Controllers\Kader\LansiaController;
use App\Http\Controllers\Kader\PemeriksaanController;
use App\Http\Controllers\Kader\ImunisasiController;
use App\Http\Controllers\Kader\KunjunganController;
use App\Http\Controllers\Kader\LaporanController;
use App\Http\Controllers\Kader\JadwalController;
use App\Http\Controllers\Kader\ImportController;
use App\Http\Controllers\Kader\ProfileController    as KaderProfile;

// User (Warga)
use App\Http\Controllers\User\DashboardController   as UserDashboard;
use App\Http\Controllers\User\BalitaController      as UserBalita;
use App\Http\Controllers\User\RemajaController      as UserRemaja;
use App\Http\Controllers\User\LansiaController      as UserLansia;
use App\Http\Controllers\User\RiwayatController;
use App\Http\Controllers\User\KonselingController;
use App\Http\Controllers\User\JadwalController      as UserJadwal;
use App\Http\Controllers\User\ProfileController     as UserProfile;
use App\Http\Controllers\User\NotifikasiController  as UserNotifikasi;

// ==================== ROOT ====================
Route::get('/', function () {
    return auth()->check() ? redirect()->route('home') : redirect()->route('login');
});

// ==================== AUTH ====================
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get');

// ==================== GLOBAL ====================
Route::middleware('auth')->group(function () {
    Route::get('/home',             [HomeController::class, 'index'])->name('home');
    Route::get('/password/change',  [ChangePasswordController::class, 'showChangeForm'])->name('password.change');
    Route::post('/password/change', [ChangePasswordController::class, 'change'])->name('password.change.post');
    Route::get('/profile',          [UserProfile::class, 'edit'])->name('profile.edit');
});

// ==================== ADMIN ====================
Route::prefix('admin')->name('admin.')->middleware(['auth','checkstatus','role:admin'])->group(function () {
    Route::get('/', fn() => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('users', AdminUser::class);
    Route::post('users/{id}/generate-password', [AdminUser::class, 'generatePassword'])->name('users.generate-password');
    Route::post('users/{id}/reset-password',    [AdminUser::class, 'resetPassword'])->name('users.reset-password');

    Route::resource('bidans', AdminBidan::class);
    Route::post('bidans/{id}/reset-password', [AdminBidan::class, 'resetPassword'])->name('bidans.reset-password');

    Route::resource('kaders', AdminKader::class);
    Route::post('kaders/{id}/reset-password', [AdminKader::class, 'resetPassword'])->name('kaders.reset-password');

    Route::get('/settings',                [AdminSetting::class, 'index'])->name('settings.index');
    Route::put('/settings',                [AdminSetting::class, 'update'])->name('settings.update');
    Route::put('/settings/change-password',[AdminSetting::class, 'changePassword'])->name('settings.change-password');
    
});

// ==================== BIDAN ====================
Route::prefix('bidan')->name('bidan.')->middleware(['auth','checkstatus','role:bidan'])->group(function () {
    Route::get('/', fn() => redirect()->route('bidan.dashboard'));
    Route::get('/dashboard', [BidanDashboard::class, 'index'])->name('dashboard');

    // PEMERIKSAAN
    Route::get('/pemeriksaan',             [BidanPemeriksaan::class, 'index'])->name('pemeriksaan.index');
    Route::get('/pemeriksaan/create',      [BidanPemeriksaan::class, 'create'])->name('pemeriksaan.create');
    Route::post('/pemeriksaan',            [BidanPemeriksaan::class, 'store'])->name('pemeriksaan.store');
    Route::get('/pemeriksaan/{id}',        [BidanPemeriksaan::class, 'show'])->name('pemeriksaan.show');
    Route::put('/pemeriksaan/{id}/verifikasi', [BidanPemeriksaan::class, 'verifikasi'])->name('pemeriksaan.verifikasi');

    // JADWAL
    Route::resource('jadwal', BidanJadwal::class);

    // DATA WARGA
    Route::get('/pasien/balita', [BidanPasien::class, 'indexBalita'])->name('pasien.balita');
    Route::get('/pasien/remaja', [BidanPasien::class, 'indexRemaja'])->name('pasien.remaja');
    Route::get('/pasien/lansia', [BidanPasien::class, 'indexLansia'])->name('pasien.lansia');

    // IMUNISASI (Akses Khusus Bidan)
    Route::get('/imunisasi', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'index'])->name('imunisasi.index');
    Route::get('/imunisasi/create', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'create'])->name('imunisasi.create');
    Route::post('/imunisasi', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'store'])->name('imunisasi.store');
    Route::get('/imunisasi/{id}', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'show'])->name('imunisasi.show');
    Route::delete('/imunisasi/{id}', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'destroy'])->name('imunisasi.destroy');

    // REKAM MEDIS (RUTE BARU)
    Route::get('/rekam-medis', [BidanRekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('/rekam-medis/{pasien_type}/{pasien_id}', [BidanRekamMedisController::class, 'show'])->name('rekam-medis.show');

    // LAPORAN & TANDA TANGAN
    Route::get('/laporan',       [\App\Http\Controllers\Bidan\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [\App\Http\Controllers\Bidan\LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::post('/laporan/upload-ttd', [\App\Http\Controllers\Bidan\LaporanController::class, 'uploadTtd'])->name('laporan.upload-ttd');
});

// ==================== KADER ====================
Route::prefix('kader')->name('kader.')->middleware(['auth','checkstatus','role:kader'])->group(function () {
    Route::get('/', fn() => redirect()->route('kader.dashboard'));
    Route::get('/dashboard', [KaderDashboard::class, 'index'])->name('dashboard');

    Route::prefix('data')->name('data.')->group(function () {
        Route::resource('balita', BalitaController::class);
        Route::get('balita/{id}/sync', [\App\Http\Controllers\Kader\BalitaController::class, 'syncUser'])->name('balita.sync');
        Route::resource('remaja', RemajaController::class);
        Route::resource('lansia', LansiaController::class);
    });

    Route::prefix('pemeriksaan')->name('pemeriksaan.')->group(function () {
        Route::get('/',          [PemeriksaanController::class, 'index'])->name('index');
        Route::get('/create',    [PemeriksaanController::class, 'create'])->name('create');
        Route::post('/',         [PemeriksaanController::class, 'store'])->name('store');
        Route::get('/{id}',      [PemeriksaanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PemeriksaanController::class, 'edit'])->name('edit');
        Route::put('/{id}',      [PemeriksaanController::class, 'update'])->name('update');
        Route::delete('/{id}',   [PemeriksaanController::class, 'destroy'])->name('destroy');
    });

    Route::get('/imunisasi',                                 [ImunisasiController::class, 'index'])->name('imunisasi.index');
    Route::get('/imunisasi/{id}',                            [ImunisasiController::class, 'show'])->name('imunisasi.show');
    Route::delete('/imunisasi/{id}',                         [ImunisasiController::class, 'destroy'])->name('imunisasi.destroy');
    Route::get('/kunjungan/{kunjungan_id}/imunisasi/create', [ImunisasiController::class, 'create'])->name('imunisasi.create');
    Route::post('/kunjungan/{kunjungan_id}/imunisasi',       [ImunisasiController::class, 'store'])->name('imunisasi.store');

    Route::resource('kunjungan', KunjunganController::class);

 // REPORT / LAPORAN KADER
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Kader\LaporanController::class, 'index'])->name('index');
        Route::get('/balita', [\App\Http\Controllers\Kader\LaporanController::class, 'balita'])->name('balita');
        Route::get('/remaja', [\App\Http\Controllers\Kader\LaporanController::class, 'remaja'])->name('remaja');
        Route::get('/lansia', [\App\Http\Controllers\Kader\LaporanController::class, 'lansia'])->name('lansia');
        
        // 👇 TAMBAHKAN BARIS INI UNTUK FUNGSI UNDUH PDF 👇
        Route::get('/generate/{type}', [\App\Http\Controllers\Kader\LaporanController::class, 'generate'])->name('generate');
    });

    Route::resource('jadwal', JadwalController::class);
    Route::post('/jadwal/broadcast/{id}', [JadwalController::class, 'broadcast'])->name('jadwal.broadcast');

    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/',                        [ImportController::class, 'index'])->name('index');
        Route::get('/create',                  [ImportController::class, 'create'])->name('create');
        Route::post('/',                       [ImportController::class, 'store'])->name('store');
        Route::get('/history',                 [ImportController::class, 'history'])->name('history');
        Route::get('/download-template/{type}',[ImportController::class, 'downloadTemplate'])->name('download-template');
        Route::get('/{id}',                    [ImportController::class, 'show'])->name('show');
        Route::delete('/{id}',                 [ImportController::class, 'destroy'])->name('destroy');
    });
// 👇 SISTEM NOTIFIKASI KADER (REAL-TIME) 👇
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Kader\NotifikasiController::class, 'index'])->name('index');
        Route::get('/fetch', [\App\Http\Controllers\Kader\NotifikasiController::class, 'fetchRecent'])->name('fetch'); // Untuk AJAX
        Route::post('/mark-all-read', [\App\Http\Controllers\Kader\NotifikasiController::class, 'markAllRead'])->name('markAllRead');
        Route::post('/{id}/read', [\App\Http\Controllers\Kader\NotifikasiController::class, 'markAsRead'])->name('read');
        Route::delete('/{id}', [\App\Http\Controllers\Kader\NotifikasiController::class, 'destroy'])->name('destroy');
    });
    
    Route::get('/profile',          [KaderProfile::class, 'index'])->name('profile.index');
    Route::put('/profile',          [KaderProfile::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [KaderProfile::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [KaderProfile::class, 'updatePassword'])->name('profile.update-password');
});

// ==================== USER (WARGA) ====================
Route::prefix('user')->name('user.')->middleware(['auth','checkstatus','role:user'])->group(function () {
    Route::get('/', fn() => redirect()->route('user.dashboard'));

    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
    Route::get('/stats',     [UserDashboard::class, 'getStats'])->name('stats');

    Route::resource('balita', UserBalita::class);
    Route::get('imunisasi',   [UserBalita::class, 'imunisasi'])->name('imunisasi.index');
    Route::resource('remaja', UserRemaja::class);
    Route::resource('lansia', UserLansia::class);

    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    Route::get('/jadwal', [UserJadwal::class, 'index'])->name('jadwal.index');

    Route::get('/notifikasi',                [UserNotifikasi::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/latest',         [UserNotifikasi::class, 'latest'])->name('notifikasi.latest');
    Route::get('/notifikasi/{id}/read',      [UserNotifikasi::class, 'markRead'])->name('notifikasi.read');
    Route::post('/notifikasi/mark-all-read', [UserNotifikasi::class, 'markAllRead'])->name('notifikasi.markall');

    Route::get('/konseling',  [KonselingController::class, 'index'])->name('konseling.index');
    Route::post('/konseling', [KonselingController::class, 'store'])->name('konseling.store');

    // 👇 INI YANG DIPERBAIKI (Ubah 'profile.index' menjadi 'profile.edit') 👇
    Route::get('/profile',   [UserProfile::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserProfile::class, 'update'])->name('profile.update');
});