<style>
    /* Styling khusus Sidebar dengan palet warna menarik */
    :root {
        --sidebar-primary: #5e72e4;
        --sidebar-secondary: #825ee4;
        --sidebar-bg: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
        --sidebar-text-light: #c7d2fe;
        --sidebar-text-lighter: #e0e7ff;
        --sidebar-border: rgba(99, 102, 241, 0.3);
        --sidebar-hover: rgba(99, 102, 241, 0.2);
        --sidebar-active: rgba(99, 102, 241, 0.3);
        --sidebar-width: 280px;
        --sidebar-collapsed: 80px;
    }

    .sidebar {
        width: var(--sidebar-width);
        min-width: var(--sidebar-width);
        background: var(--sidebar-bg);
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1100;
        overflow-y: auto;
        overflow-x: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        box-shadow: 0 0 40px rgba(0, 0, 0, 0.2);
    }

    .sidebar.collapsed {
        width: var(--sidebar-collapsed);
        min-width: var(--sidebar-collapsed);
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(199, 210, 254, 0.5);
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(199, 210, 254, 0.7);
    }

    /* Brand Section */
    .sidebar-brand {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px solid var(--sidebar-border);
        position: relative;
        overflow: hidden;
        min-height: 80px;
        transition: all 0.4s ease;
    }

    .sidebar-brand::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        animation: shimmer 3s infinite linear;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .brand-icon {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
        position: relative;
        z-index: 1;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .brand-icon:hover {
        transform: rotate(15deg) scale(1.1);
    }

    .brand-text {
        position: relative;
        z-index: 1;
        overflow: hidden;
        transition: all 0.4s ease;
    }

    .sidebar.collapsed .brand-text {
        opacity: 0;
        width: 0;
        margin: 0;
    }

    .brand-text h6 {
        margin: 0;
        font-weight: 700;
        color: white;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }

    .brand-text small {
        color: var(--sidebar-text-light);
        font-size: 0.75rem;
        margin-top: 0.1rem;
        display: block;
    }

    

    /* Navigation */
    .nav {
        flex: 1;
        padding: 1rem 0;
        overflow-y: auto;
    }

    .nav-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--sidebar-text-light);
        padding: 1rem 1.5rem 0.5rem;
        letter-spacing: 1px;
        margin-top: 0.5rem;
        transition: all 0.4s ease;
        opacity: 0.8;
        position: relative;
    }

    .sidebar.collapsed .nav-label {
        opacity: 0;
        height: 0;
        padding: 0;
        margin: 0;
        overflow: hidden;
    }

    .nav-label::after {
        content: '';
        position: absolute;
        left: 1.5rem;
        right: 1.5rem;
        bottom: -0.25rem;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--sidebar-border), transparent);
    }

    /* Navigation Links */
    .nav-link-custom {
        display: flex;
        align-items: center;
        padding: 0.85rem 1.5rem;
        color: var(--sidebar-text-lighter);
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        margin: 0.15rem 0.75rem;
        border-radius: 10px;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .nav-link-custom::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
        transform: scaleY(0);
        transition: transform 0.3s ease;
        border-radius: 0 3px 3px 0;
    }

    .nav-link-custom:hover {
        background: var(--sidebar-hover);
        color: white;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .nav-link-custom:hover::before {
        transform: scaleY(1);
    }

    .nav-link-custom.active {
        background: var(--sidebar-active);
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    }

    .nav-link-custom.active::before {
        transform: scaleY(1);
    }

    .nav-link-custom i {
        width: 22px;
        margin-right: 12px;
        font-size: 1.1rem;
        text-align: center;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .nav-link-custom:hover i {
        transform: scale(1.15);
    }

    .nav-link-custom span {
        white-space: nowrap;
        overflow: hidden;
        transition: opacity 0.4s ease;
    }

    /* Collapsed state */
    .sidebar.collapsed .nav-link-custom {
        padding: 0.85rem;
        margin: 0.15rem auto;
        width: 50px;
        height: 50px;
        justify-content: center;
    }

    .sidebar.collapsed .nav-link-custom span {
        opacity: 0;
        width: 0;
        position: absolute;
    }

    .sidebar.collapsed .nav-link-custom i {
        margin-right: 0;
        font-size: 1.2rem;
    }

    /* Tooltip for collapsed state */
    .nav-link-custom[data-tooltip] {
        position: relative;
    }

    .nav-link-custom[data-tooltip]::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%) translateX(10px);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
        pointer-events: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .nav-link-custom[data-tooltip]:hover::after {
        opacity: 1;
        visibility: visible;
        transform: translateY(-50%) translateX(15px);
    }

    /* Icon colors */
    .nav-link-custom i.fa-th-large { color: #60a5fa; }
    .nav-link-custom i.fa-baby { color: #34d399; }
    .nav-link-custom i.fa-user-graduate { color: #fbbf24; }
    .nav-link-custom i.fa-wheelchair { color: #f87171; }
    .nav-link-custom i.fa-stethoscope { color: #ef4444; }
    .nav-link-custom i.fa-file-medical { color: #8b5cf6; }
    .nav-link-custom i.fa-calendar-alt { color: #a78bfa; }

    /* Responsive */
    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-100%);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.3);
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
        }
    }

    /* Animation on load */
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .nav-link-custom {
        animation: slideInLeft 0.4s ease-out;
        animation-fill-mode: both;
    }

    .nav-link-custom:nth-child(1) { animation-delay: 0.1s; }
    .nav-link-custom:nth-child(2) { animation-delay: 0.15s; }
    .nav-link-custom:nth-child(3) { animation-delay: 0.2s; }
    .nav-link-custom:nth-child(4) { animation-delay: 0.25s; }
    .nav-link-custom:nth-child(5) { animation-delay: 0.3s; }
    .nav-link-custom:nth-child(6) { animation-delay: 0.35s; }
    .nav-link-custom:nth-child(7) { animation-delay: 0.4s; }
    .nav-link-custom:nth-child(8) { animation-delay: 0.45s; }
</style>

@php
    // Menghitung jumlah antrian validasi (diagnosa masih kosong) untuk bulan ini
    $pendingCount = \App\Models\Pemeriksaan::whereMonth('created_at', \Carbon\Carbon::now()->month)
        ->where(function($q) {
            $q->whereNull('diagnosa')
              ->orWhere('diagnosa', '');
        })->count();
@endphp

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-heartbeat"></i>
        </div>
        <div class="brand-text">
            <h6 class="mb-0 fw-bold">E-Posyandu</h6>
            <small style="font-size: 0.7rem; opacity: 0.8;">Portal Medis Bidan</small>
        </div>
    </div>

    <nav class="nav-content">
        
        <a class="nav-link-custom {{ request()->routeIs('bidan.dashboard') ? 'active' : '' }}" 
           href="{{ route('bidan.dashboard') }}"
           data-tooltip="Dashboard">
            <i class="fas fa-th-large"></i> <span>Dashboard</span>
        </a>

        <div class="nav-label">Layanan Medis</div>

        <a class="nav-link-custom {{ request()->routeIs('bidan.pemeriksaan.create') ? 'active' : '' }}" 
           href="{{ route('bidan.pemeriksaan.create') }}"
           data-tooltip="Input Manual">
            <i class="fas fa-stethoscope text-primary"></i> <span>Input Manual</span>
        </a>

        <a class="nav-link-custom {{ request()->routeIs('bidan.pemeriksaan.index') || request()->routeIs('bidan.pemeriksaan.show') ? 'active' : '' }}" 
           href="{{ route('bidan.pemeriksaan.index') }}"
           data-tooltip="Riwayat Medis">
            <i class="fas fa-file-medical text-danger"></i> 
            <span class="d-flex justify-content-between align-items-center w-100 pe-2">
                Riwayat & Validasi
                @if($pendingCount > 0)
                    {{-- Badge merah akan muncul jika ada data yang belum divaidasi --}}
                    <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem; padding: 0.25em 0.6em;">{{ $pendingCount }}</span>
                @endif
            </span>
        </a>

        <div class="nav-label">Data Warga</div>
        
        <a class="nav-link-custom {{ request()->routeIs('bidan.pasien.balita') ? 'active' : '' }}" 
           href="{{ route('bidan.pasien.balita') }}"
           data-tooltip="Data Balita">
            <i class="fas fa-baby text-info"></i> <span>Data Balita</span>
        </a>
        
        <a class="nav-link-custom {{ request()->routeIs('bidan.pasien.remaja') ? 'active' : '' }}" 
           href="{{ route('bidan.pasien.remaja') }}"
           data-tooltip="Data Remaja">
            <i class="fas fa-user-graduate text-success"></i> <span>Data Remaja</span>
        </a>
        
        <a class="nav-link-custom {{ request()->routeIs('bidan.pasien.lansia') ? 'active' : '' }}" 
           href="{{ route('bidan.pasien.lansia') }}"
           data-tooltip="Data Lansia">
            <i class="fas fa-wheelchair text-warning"></i> <span>Data Lansia</span>
        </a>

        <div class="nav-label">Administrasi</div>

        <a class="nav-link-custom {{ request()->routeIs('bidan.jadwal.*') ? 'active' : '' }}" 
           href="{{ route('bidan.jadwal.index') }}"
           data-tooltip="Kelola Jadwal">
            <i class="fas fa-calendar-alt text-primary"></i> <span>Kelola Jadwal</span>
        </a>

        <a class="nav-link-custom {{ request()->routeIs('bidan.laporan.*') ? 'active' : '' }}" 
           href="{{ route('bidan.laporan.index') }}"
           data-tooltip="Laporan Bulanan">
            <i class="fas fa-chart-bar text-success"></i> <span>Laporan Bulanan</span>
        </a>

        <div class="mt-4 pt-3 border-top border-secondary mx-3 mb-4">
            <a class="nav-link-custom text-danger px-0" 
               href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               data-tooltip="Keluar">
                <i class="fas fa-sign-out-alt"></i> <span>Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>

    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    
    // Restore sidebar state
    if (window.innerWidth >= 992) {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
        }
    }
    
   
});
</script>