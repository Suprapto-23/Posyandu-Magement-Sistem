{{-- 
    File: resources/views/partials/sidebar-kader.blade.php 
    Deskripsi: Sidebar navigasi khusus untuk role Kader
--}}

<div class="nav-section-title">Menu Utama</div>

<div class="nav-item">
    <a href="{{ route('kader.dashboard') }}" class="nav-link {{ request()->routeIs('kader.dashboard') ? 'active' : '' }}">
        <i class="fas fa-th-large text-primary"></i>
        <span>Dashboard</span>
    </a>
</div>

<div class="nav-section-title">Data Warga</div>

<div class="nav-item">
    <a href="#dataMasterSubmenu" 
       class="nav-link {{ request()->routeIs('kader.data.*') || request()->routeIs('kader.import.*') ? '' : 'collapsed' }}" 
       data-bs-toggle="collapse" 
       role="button" 
       aria-expanded="{{ request()->routeIs('kader.data.*') || request()->routeIs('kader.import.*') ? 'true' : 'false' }}" 
       aria-controls="dataMasterSubmenu">
        <i class="fas fa-users text-secondary"></i>
        <span>Data Pasien</span>
        <i class="fas fa-angle-down ms-auto"></i>
    </a>
    
    <div class="collapse {{ request()->routeIs('kader.data.*') || request()->routeIs('kader.import.*') ? 'show' : '' }}" id="dataMasterSubmenu">
        <div class="nav flex-column ps-3 pt-1">
            {{-- Data Balita --}}
            <a href="{{ route('kader.data.balita.index') }}" class="nav-link {{ request()->routeIs('kader.data.balita.*') ? 'active' : '' }}">
                <i class="fas fa-baby fa-fw me-2 text-info"></i> Balita
            </a>
            
            {{-- Data Remaja --}}
            <a href="{{ route('kader.data.remaja.index') }}" class="nav-link {{ request()->routeIs('kader.data.remaja.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate fa-fw me-2 text-success"></i> Remaja
            </a>
            
            {{-- Data Lansia --}}
            <a href="{{ route('kader.data.lansia.index') }}" class="nav-link {{ request()->routeIs('kader.data.lansia.*') ? 'active' : '' }}">
                <i class="fas fa-user-clock fa-fw me-2 text-warning"></i> Lansia
            </a>

            {{-- Import Data --}}
            <a href="{{ route('kader.import.index') }}" class="nav-link {{ request()->routeIs('kader.import.*') ? 'active' : '' }}">
                <i class="fas fa-file-import fa-fw me-2 text-dark"></i> Import Excel
            </a>
        </div>
    </div>
</div>

<div class="nav-section-title">Layanan Medis</div>

<div class="nav-item">
    <a href="{{ route('kader.pemeriksaan.index') }}" class="nav-link {{ request()->routeIs('kader.pemeriksaan.*') ? 'active' : '' }}">
        <i class="fas fa-stethoscope text-danger"></i>
        <span>Pemeriksaan</span>
    </a>
</div>

<div class="nav-item">
    <a href="{{ route('kader.imunisasi.index') }}" class="nav-link {{ request()->routeIs('kader.imunisasi.*') ? 'active' : '' }}">
        <i class="fas fa-syringe text-info"></i>
        <span>Imunisasi</span>
    </a>
</div>

<div class="nav-item">
    <a href="{{ route('kader.kunjungan.index') }}" class="nav-link {{ request()->routeIs('kader.kunjungan.*') ? 'active' : '' }}">
        <i class="fas fa-clipboard-list text-success"></i>
        <span>Riwayat Kunjungan</span>
    </a>
</div>

<div class="nav-section-title">Kegiatan</div>

<div class="nav-item">
    <a href="{{ route('kader.jadwal.index') }}" class="nav-link {{ request()->routeIs('kader.jadwal.*') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt text-warning"></i>
        <span>Jadwal Posyandu</span>
    </a>
</div>

<div class="nav-section-title">Akun</div>

<div class="nav-item">
    {{-- Pastikan route profile kader ada, jika belum ada arahkan ke dashboard dulu atau buat controllernya --}}
   <a href="{{ route('kader.profile.index') }}" class="nav-link {{ request()->routeIs('kader.profile.*') ? 'active' : '' }}">
        <i class="fas fa-user-cog text-secondary"></i>
        <span>Profil Saya</span>
    </a>
</div>

<div class="nav-item mt-2">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent text-danger">
            <i class="fas fa-sign-out-alt"></i>
            <span>Keluar</span>
        </button>
    </form>
</div>