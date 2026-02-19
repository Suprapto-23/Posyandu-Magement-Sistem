<div class="py-4 px-3">
    <a class="navbar-brand d-flex align-items-center mb-4 ps-2" href="{{ route('user.dashboard') }}">
        <div class="bg-primary text-white rounded-3 p-2 me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i class="fas fa-heartbeat fa-lg"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0 text-dark">E-Posyandu</h6>
            <small class="text-muted" style="font-size: 0.7rem;">Panel Warga</small>
        </div>
    </a>

    <nav class="nav flex-column gap-1">
        
        <div class="text-uppercase text-muted fw-bold mb-2 mt-2 px-3" style="font-size: 0.7rem; letter-spacing: 1px;">Menu Utama</div>
        
        <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->routeIs('user.dashboard') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" 
           href="{{ route('user.dashboard') }}">
            <i class="fas fa-tachometer-alt fa-fw me-3"></i>
            <span class="fw-medium">Dashboard</span>
        </a>

        <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->routeIs('user.jadwal*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" 
           href="{{ route('user.jadwal.index') }}">
            <i class="fas fa-calendar-alt fa-fw me-3"></i>
            <span class="fw-medium">Jadwal Posyandu</span>
        </a>

        <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->routeIs('user.notifikasi*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" 
           href="{{ route('user.notifikasi.index') }}">
            <div class="d-flex justify-content-between w-100 align-items-center">
                <div>
                    <i class="fas fa-bell fa-fw me-3"></i>
                    <span class="fw-medium">Notifikasi</span>
                </div>
                {{-- Badge Notifikasi --}}
                @if(isset($totalNotifikasi) && $totalNotifikasi > 0)
                    <span class="badge bg-danger rounded-pill">{{ $totalNotifikasi }}</span>
                @endif
            </div>
        </a>

        {{-- ========================================================== --}}
        {{-- LOGIKA MENU EKSLUSIF BERDASARKAN PERAN (PRIORITAS) --}}
        {{-- ========================================================== --}}

        {{-- 1. KHUSUS ORANG TUA (Balita) --}}
        @if(in_array('orang_tua', $peranUser))
            <div class="text-uppercase text-muted fw-bold mb-2 mt-4 px-3" style="font-size: 0.7rem; letter-spacing: 1px;">Kesehatan Anak</div>
            
            <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->routeIs('user.balita*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" 
               href="{{ route('user.balita.index') }}">
                <i class="fas fa-baby fa-fw me-3"></i>
                <span class="fw-medium">Data Balita</span>
            </a>
            <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->routeIs('user.imunisasi*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" 
               href="{{ route('user.imunisasi.index') }}">
                <i class="fas fa-syringe fa-fw me-3"></i>
                <span class="fw-medium">Riwayat Imunisasi</span>
            </a>
        @endif

        {{-- 2. KHUSUS REMAJA --}}
        @if(in_array('remaja', $peranUser))
            <div class="text-uppercase text-muted fw-bold mb-2 mt-4 px-3" style="font-size: 0.7rem; letter-spacing: 1px;">Kesehatan Remaja</div>
            
            <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->routeIs('user.remaja*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" 
               href="{{ route('user.remaja.index') }}">
                <i class="fas fa-user-graduate fa-fw me-3"></i>
                <span class="fw-medium">Data Diri</span>
            </a>
            <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->routeIs('user.konseling*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" 
               href="{{ route('user.konseling.index') }}">
                <i class="fas fa-comments fa-fw me-3"></i>
                <span class="fw-medium">Konseling</span>
            </a>
        @endif

        {{-- 3. KHUSUS LANSIA --}}
        @if(in_array('lansia', $peranUser))
            <div class="text-uppercase text-muted fw-bold mb-2 mt-4 px-3" style="font-size: 0.7rem; letter-spacing: 1px;">Kesehatan Lansia</div>
            
            <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->routeIs('user.lansia*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" 
               href="{{ route('user.lansia.index') }}">
                <i class="fas fa-blind fa-fw me-3"></i>
                <span class="fw-medium">Data Pemeriksaan</span>
            </a>
        @endif

        {{-- 4. JIKA BELUM TERDAFTAR DI KATEGORI APAPUN --}}
        @if(empty(array_intersect(['orang_tua', 'remaja', 'lansia'], $peranUser)))
            <div class="mt-3 px-3">
                <div class="alert alert-light border small text-muted mb-0">
                    <i class="fas fa-info-circle me-1"></i> Data kesehatan belum terhubung. Hubungi Kader untuk pendaftaran NIK.
                </div>
            </div>
        @endif

        {{-- MENU AKUN (SELALU MUNCUL) --}}
        <div class="text-uppercase text-muted fw-bold mb-2 mt-4 px-3" style="font-size: 0.7rem; letter-spacing: 1px;">Riwayat & Akun</div>

        <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->routeIs('user.riwayat*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" 
           href="{{ route('user.riwayat.index') }}">
            <i class="fas fa-history fa-fw me-3"></i>
            <span class="fw-medium">Riwayat Kunjungan</span>
        </a>

        <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 {{ request()->routeIs('profile*') ? 'bg-primary text-white shadow-sm' : 'text-secondary hover-bg-light' }}" 
           href="{{ route('profile.edit') }}">
            <i class="fas fa-user-cog fa-fw me-3"></i>
            <span class="fw-medium">Profil</span>
        </a>

        <a class="nav-link d-flex align-items-center rounded-3 px-3 py-2 text-danger hover-bg-danger-light mt-2" 
           href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
            <i class="fas fa-sign-out-alt fa-fw me-3"></i>
            <span class="fw-medium">Keluar</span>
        </a>
        
        <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

    </nav>
</div>

<style>
    .hover-bg-light:hover {
        background-color: var(--neutral-100);
        color: var(--primary-600) !important;
        transition: all 0.2s ease;
    }
    .hover-bg-danger-light:hover {
        background-color: #fee2e2;
        color: #b91c1c !important;
        transition: all 0.2s ease;
    }
</style>