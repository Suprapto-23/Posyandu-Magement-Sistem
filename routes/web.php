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
use App\Http\Controllers\Bidan\KonselingController   as BidanKonseling;

// Kader
use App\Http\Controllers\Kader\DashboardController   as KaderDashboard;
use App\Http\Controllers\Kader\BalitaController;
use App\Http\Controllers\Kader\RemajaController;
use App\Http\Controllers\Kader\LansiaController;
use App\Http\Controllers\Kader\IbuHamilController;
use App\Http\Controllers\Kader\PemeriksaanController;
use App\Http\Controllers\Kader\ImunisasiController;
use App\Http\Controllers\Kader\KunjunganController;
use App\Http\Controllers\Kader\LaporanController;
use App\Http\Controllers\Kader\JadwalController;
use App\Http\Controllers\Kader\ImportController;
use App\Http\Controllers\Kader\ProfileController    as KaderProfile;
use App\Http\Controllers\Kader\NotifikasiController as KaderNotifikasi;
use App\Http\Controllers\Kader\AbsensiController; // 👈 Controller Absensi ditambahkan di sini

// User (Warga)
use App\Http\Controllers\User\DashboardController   as UserDashboard;
use App\Http\Controllers\User\BalitaController      as UserBalita;
use App\Http\Controllers\User\RemajaController      as UserRemaja;
use App\Http\Controllers\User\LansiaController      as UserLansia;
use App\Http\Controllers\User\JadwalController      as UserJadwal;
use App\Http\Controllers\User\ProfileController     as UserProfile;
use App\Http\Controllers\User\NotifikasiController  as UserNotifikasi;
use App\Http\Controllers\User\RiwayatController;
use App\Http\Controllers\User\KonselingController   as UserKonseling;

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
    
    // 1. DASHBOARD
    Route::get('/', fn() => redirect()->route('bidan.dashboard'));
    Route::get('/dashboard', [BidanDashboard::class, 'index'])->name('dashboard');

    // 2. PEMERIKSAAN MEDIS (MEJA 5) - SUPER CONTROLLER
    Route::prefix('pemeriksaan')->name('pemeriksaan.')->group(function () {
        Route::get('/', [BidanPemeriksaan::class, 'index'])->name('index');
        
        // Rute Input Mandiri oleh Bidan (KEMBALI DIAKTIFKAN)
        Route::get('/create', [BidanPemeriksaan::class, 'create'])->name('create');
        Route::post('/', [BidanPemeriksaan::class, 'store'])->name('store');
        
        // Rute Validasi (Split-View)
        Route::get('/validasi/{id}', [BidanPemeriksaan::class, 'validasi'])->name('validasi');
        Route::put('/validasi/{id}', [BidanPemeriksaan::class, 'simpanValidasi'])->name('simpan-validasi');
        
        // Rute Standar (Show, Edit, Update, Destroy)
        Route::get('/{id}', [BidanPemeriksaan::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BidanPemeriksaan::class, 'edit'])->name('edit');
        Route::put('/{id}', [BidanPemeriksaan::class, 'update'])->name('update');
        Route::delete('/{id}', [BidanPemeriksaan::class, 'destroy'])->name('destroy');
    });

    // 3. JADWAL POSYANDU
    Route::resource('jadwal', BidanJadwal::class);

    // 4. IMUNISASI (VAKSIN)
    Route::prefix('imunisasi')->name('imunisasi.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Bidan\ImunisasiController::class, 'destroy'])->name('destroy');
    });

    // 5. REKAM MEDIS & DATABASE PASIEN
    Route::get('/rekam-medis', [BidanRekamMedisController::class, 'index'])->name('rekam-medis.index');
    Route::get('/rekam-medis/{pasien_type}/{pasien_id}', [BidanRekamMedisController::class, 'show'])->name('rekam-medis.show');
    
    // Alias Route: Agar tombol "Data Pasien (View)" di Sidebar tidak error, 
    // kita arahkan langsung ke pusat Rekam Medis (DRY - Don't Repeat Yourself)
    Route::get('/pasien', [BidanRekamMedisController::class, 'index'])->name('pasien.index');

    // 6. LAPORAN PDF
    Route::get('/laporan', [\App\Http\Controllers\Bidan\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [\App\Http\Controllers\Bidan\LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::post('/laporan/upload-ttd', [\App\Http\Controllers\Bidan\LaporanController::class, 'uploadTtd'])->name('laporan.upload-ttd');

    // 7. KONSELING MEJA 5
    Route::prefix('konseling')->name('konseling.')->group(function () {
        Route::get('/', [BidanKonseling::class, 'index'])->name('index');
        Route::get('/fetch-list', [BidanKonseling::class, 'fetchList'])->name('fetch-list');
        Route::get('/fetch-chat/{user_id}', [BidanKonseling::class, 'fetchChat'])->name('fetch-chat');
        Route::post('/reply/{user_id}', [BidanKonseling::class, 'reply'])->name('reply');
    });
});

// ==================== KADER ====================
Route::prefix('kader')->name('kader.')->middleware(['auth','checkstatus','role:kader'])->group(function () {
    Route::get('/', fn() => redirect()->route('kader.dashboard'));
    Route::get('/dashboard', [KaderDashboard::class, 'index'])->name('dashboard');

    // 👇 RUTE ABSENSI DITAMBAHKAN DI SINI 👇
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [AbsensiController::class, 'index'])->name('index'); 
        Route::get('/riwayat', [AbsensiController::class, 'riwayat'])->name('riwayat'); 
        Route::get('/{id}', [AbsensiController::class, 'show'])->name('show'); 
        Route::post('/', [AbsensiController::class, 'store'])->name('store');
        Route::put('/{id}', [AbsensiController::class, 'update'])->name('update');
        Route::delete('/{id}', [AbsensiController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('data')->name('data.')->group(function () {
        Route::resource('balita', BalitaController::class);
        Route::get('balita/{id}/sync', [\App\Http\Controllers\Kader\BalitaController::class, 'syncUser'])->name('balita.sync');
        Route::post('balita/bulk-delete', [\App\Http\Controllers\Kader\BalitaController::class, 'bulkDelete'])->name('balita.bulk-delete');
        Route::resource('remaja', RemajaController::class);
        Route::post('remaja/bulk-delete', [\App\Http\Controllers\Kader\RemajaController::class, 'bulkDelete'])->name('remaja.bulk-delete');
        Route::get('remaja/{id}/sync', [\App\Http\Controllers\Kader\RemajaController::class, 'syncUser'])->name('remaja.sync');
        Route::resource('lansia', LansiaController::class);
        Route::resource('ibu-hamil', IbuHamilController::class);
        
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

    Route::prefix('laporan')
        ->name('laporan.')
        ->controller(\App\Http\Controllers\Kader\LaporanController::class)
        ->group(function () {
            
            // 1. Dashboard UI Utama (Pusat Unduhan Semua Laporan)
            Route::get('/', 'index')->name('index');
            
            // 2. Mesin Generator Laporan (Direct Download PDF & Excel)
            // Parameter type, bulan, tahun, format dikirim via Query String (GET)
            Route::get('/generate', 'generate')->name('generate');
            
    });

    Route::resource('jadwal', JadwalController::class);
    Route::post('/jadwal/broadcast/{id}', [JadwalController::class, 'broadcast'])->name('jadwal.broadcast');

    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/',                [ImportController::class, 'index'])->name('index');
        Route::get('/create',          [ImportController::class, 'create'])->name('create');
        Route::post('/',               [ImportController::class, 'store'])->name('store');
        Route::get('/history',         [ImportController::class, 'history'])->name('history');
        Route::get('/download-template/{type}',[ImportController::class, 'downloadTemplate'])->name('download-template');
        Route::get('/{id}',            [ImportController::class, 'show'])->name('show');
        Route::delete('/{id}',         [ImportController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [KaderNotifikasi::class, 'index'])->name('index');
        Route::get('/fetch', [KaderNotifikasi::class, 'fetchRecent'])->name('fetch');
        Route::post('/mark-all-read', [KaderNotifikasi::class, 'markAllRead'])->name('markAllRead');
        Route::post('/{id}/read', [KaderNotifikasi::class, 'markAsRead'])->name('read');
        Route::delete('/{id}', [KaderNotifikasi::class, 'destroy'])->name('destroy');
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

    Route::get('/jadwal', [UserJadwal::class, 'index'])->name('jadwal.index');

    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [UserNotifikasi::class, 'index'])->name('index');
        Route::get('/fetch', [UserNotifikasi::class, 'fetchRecent'])->name('fetch');
        Route::post('/mark-all-read', [UserNotifikasi::class, 'markAllRead'])->name('markall');
        Route::post('/{id}/read', [UserNotifikasi::class, 'markRead'])->name('read');
    });

    Route::get('/profile',   [UserProfile::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserProfile::class, 'update'])->name('profile.update');
    
    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');

    Route::prefix('konseling')->name('konseling.')->group(function () {
        Route::get('/', [UserKonseling::class, 'index'])->name('index');
        Route::get('/fetch-chat', [UserKonseling::class, 'fetchChat'])->name('fetch-chat');
        Route::post('/store', [UserKonseling::class, 'store'])->name('store');
    });
});