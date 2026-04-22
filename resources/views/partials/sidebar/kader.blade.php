@php
    $userAuth = auth()->user();
    $nikAuth = $userAuth->nik ?? ($userAuth->profile->nik ?? null);
    
    // Deteksi Peran Dinamis Warga
    $isOrangTua = false; $isRemaja = false; $isLansia = false;

    if ($nikAuth) {
        $isOrangTua = \App\Models\Balita::where('nik_ibu', $nikAuth)->orWhere('nik_ayah', $nikAuth)->exists();
        $isRemaja = \App\Models\Remaja::where('nik', $nikAuth)->exists();
        $isLansia = \App\Models\Lansia::where('nik', $nikAuth)->exists();
    }

    // Fungsi Render CSS Menu Aktif yang Elegan
    if (!function_exists('user_nav_active')) {
        function user_nav_active($route) {
            return request()->routeIs($route) 
                ? 'bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-bold shadow-lg shadow-teal-200/50 scale-[1.02] rounded-[14px]' 
                : 'text-slate-500 font-medium hover:bg-teal-50 hover:text-teal-600 rounded-[14px] transition-all duration-300';
        }
    }
    
    if (!function_exists('user_nav_icon')) {
        function user_nav_icon($route) {
            return request()->routeIs($route) 
                ? 'text-white drop-shadow-sm' 
                : 'text-slate-400 group-hover:text-teal-500 transition-colors duration-300';
        }
    }
@endphp

<div class="flex flex-col h-full bg-white/95 backdrop-blur-xl border-r border-slate-100 shadow-[10px_0_40px_rgba(0,0,0,0.02)]" style="font-family: 'Poppins', sans-serif;">
    
    {{-- 1. LOGO & BRANDING --}}
    <div class="h-[80px] flex items-center px-6 shrink-0 border-b border-slate-50">
        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 w-full group">
            <div class="w-10 h-10 rounded-[12px] bg-gradient-to-br from-teal-500 to-emerald-600 text-white flex items-center justify-center shadow-md shadow-teal-200/50 group-hover:rotate-6 group-hover:scale-105 transition-all duration-300">
                <i class="fas fa-leaf text-[18px]"></i>
            </div>
            <div>
                <h2 class="text-[19px] font-black text-slate-800 tracking-tight leading-none mb-1">Posyandu<span class="text-teal-600">Care</span></h2>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Portal Warga</p>
            </div>
        </a>
    </div>

    {{-- 2. MINI PROFILE CARD (Identitas Pengguna) --}}
    <div class="px-5 pt-6 pb-3 shrink-0">
        <div class="p-3 bg-white border border-slate-100 rounded-[16px] flex items-center gap-3 shadow-sm hover:border-teal-200 transition-colors duration-300 group">
            <div class="w-11 h-11 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-lg font-black shrink-0 border border-teal-100 group-hover:bg-teal-500 group-hover:text-white transition-colors duration-300">
                {{ strtoupper(substr($userAuth->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[13px] font-bold text-slate-800 truncate">{{ $userAuth->name ?? 'Pengguna Warga' }}</p>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Akses Publik</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. NAVIGASI UTAMA (Menu Berdasarkan Controller) --}}
    <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-6 custom-scrollbar">
        
        {{-- BLOK 1: Layanan Utama --}}
        <div>
            <p class="px-3 text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-3">Layanan Utama</p>
            <div class="space-y-1.5">
                <a href="{{ route('user.dashboard') }}" class="group flex items-center gap-3.5 px-4 py-3 {{ user_nav_active('user.dashboard') }}">
                    <div class="w-5 flex justify-center"><i class="fas fa-home text-[16px] {{ user_nav_icon('user.dashboard') }}"></i></div>
                    <span class="text-[13.5px]">Beranda Saya</span>
                </a>
                <a href="{{ route('user.jadwal.index') }}" class="group flex items-center gap-3.5 px-4 py-3 {{ user_nav_active('user.jadwal.*') }}">
                    <div class="w-5 flex justify-center"><i class="fas fa-calendar-check text-[16px] {{ user_nav_icon('user.jadwal.*') }}"></i></div>
                    <span class="text-[13.5px]">Agenda Posyandu</span>
                </a>
                <a href="{{ route('user.notifikasi.index') }}" class="group flex items-center gap-3.5 px-4 py-3 {{ user_nav_active('user.notifikasi.*') }}">
                    <div class="w-5 flex justify-center"><i class="fas fa-bell text-[16px] {{ user_nav_icon('user.notifikasi.*') }}"></i></div>
                    <span class="text-[13.5px] flex-1">Pesan & Notifikasi</span>
                    {{-- Opsional: Badge Notifikasi Unread bisa ditaruh di sini --}}
                </a>
            </div>
        </div>

        {{-- BLOK 2: Kesehatan Keluarga (Muncul Dinamis Berdasarkan NIK) --}}
        @if($isOrangTua || $isRemaja || $isLansia)
            <div>
                <p class="px-3 text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-3">Kesehatan Keluarga</p>
                <div class="space-y-1.5">
                    
                    @if($isOrangTua)
                        <a href="{{ route('user.balita.index') }}" class="group flex items-center gap-3.5 px-4 py-3 {{ user_nav_active('user.balita.*') }}">
                            <div class="w-5 flex justify-center"><i class="fas fa-baby text-[16px] {{ user_nav_icon('user.balita.*') }}"></i></div>
                            <span class="text-[13.5px]">KMS Anak & Balita</span>
                        </a>
                        <a href="{{ route('user.imunisasi.index') }}" class="group flex items-center gap-3.5 px-4 py-3 {{ user_nav_active('user.imunisasi.*') }}">
                            <div class="w-5 flex justify-center"><i class="fas fa-shield-virus text-[16px] {{ user_nav_icon('user.imunisasi.*') }}"></i></div>
                            <span class="text-[13.5px]">Riwayat Imunisasi</span>
                        </a>
                    @endif
                    
                    @if($isRemaja)
                        <a href="{{ route('user.remaja.index') }}" class="group flex items-center gap-3.5 px-4 py-3 {{ user_nav_active('user.remaja.*') }}">
                            <div class="w-5 flex justify-center"><i class="fas fa-user-graduate text-[16px] {{ user_nav_icon('user.remaja.*') }}"></i></div>
                            <span class="text-[13.5px]">Kesehatan Remaja</span>
                        </a>
                        <a href="{{ route('user.konseling.index') }}" class="group flex items-center gap-3.5 px-4 py-3 {{ user_nav_active('user.konseling.*') }}">
                            <div class="w-5 flex justify-center"><i class="fas fa-comments-medical text-[16px] {{ user_nav_icon('user.konseling.*') }}"></i></div>
                            <span class="text-[13.5px]">Ruang Konsultasi</span>
                        </a>
                    @endif
                    
                    @if($isLansia)
                        <a href="{{ route('user.lansia.index') }}" class="group flex items-center gap-3.5 px-4 py-3 {{ user_nav_active('user.lansia.*') }}">
                            <div class="w-5 flex justify-center"><i class="fas fa-wheelchair text-[16px] {{ user_nav_icon('user.lansia.*') }}"></i></div>
                            <span class="text-[13.5px]">Pemantauan Lansia</span>
                        </a>
                    @endif

                </div>
            </div>
        @else
            {{-- BLOK PERINGATAN: Jika warga belum sync NIK dengan Database Posyandu --}}
            <div class="mx-4 mt-2 mb-4 p-5 bg-gradient-to-br from-rose-50 to-orange-50 border border-rose-100 rounded-[20px] shadow-sm relative overflow-hidden">
                <i class="fas fa-id-card-clip absolute -right-3 -bottom-3 text-5xl text-rose-500/10"></i>
                <div class="flex items-center gap-2 mb-3 relative z-10">
                    <div class="w-6 h-6 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center text-xs"><i class="fas fa-lock"></i></div>
                    <h4 class="text-[11px] font-black text-rose-700 uppercase tracking-widest">Akses Terkunci</h4>
                </div>
                <p class="text-[11px] font-medium text-rose-600/90 leading-relaxed mb-4 relative z-10">Rekam medis (KMS/Imunisasi) akan otomatis terbuka setelah NIK Anda sinkron dengan database Posyandu.</p>
                <a href="{{ route('user.profile.edit') }}" class="block w-full text-center py-2.5 bg-rose-500 text-white text-[11px] font-bold rounded-xl hover:bg-rose-600 transition-colors shadow-sm relative z-10">
                    Lengkapi NIK Sekarang
                </a>
            </div>
        @endif

        {{-- BLOK 3: Pengaturan Akun --}}
        <div>
            <p class="px-3 text-[10px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-3 mt-2">Personal</p>
            <div class="space-y-1.5">
                <a href="{{ route('user.riwayat.index') }}" class="group flex items-center gap-3.5 px-4 py-3 {{ user_nav_active('user.riwayat.*') }}">
                    <div class="w-5 flex justify-center"><i class="fas fa-notes-medical text-[16px] {{ user_nav_icon('user.riwayat.*') }}"></i></div>
                    <span class="text-[13.5px]">Buku Rekam Medis</span>
                </a>
                <a href="{{ route('user.profile.edit') }}" class="group flex items-center gap-3.5 px-4 py-3 {{ user_nav_active('user.profile.*') }}">
                    <div class="w-5 flex justify-center"><i class="fas fa-user-cog text-[16px] {{ user_nav_icon('user.profile.*') }}"></i></div>
                    <span class="text-[13.5px]">Data Profil Warga</span>
                </a>
            </div>
        </div>

    </nav>
    
    {{-- 4. TOMBOL LOGOUT --}}
    <div class="p-5 border-t border-slate-50 shrink-0 bg-slate-50/50">
        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
            @csrf
            <button type="submit" onclick="showGlobalLoader()" class="w-full flex items-center justify-center gap-2.5 px-4 py-3.5 bg-white border border-slate-200 rounded-[14px] text-rose-500 font-bold hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm">
                <i class="fas fa-sign-out-alt"></i>
                <span class="text-[13px]">Keluar Aplikasi</span>
            </button>
        </form>
    </div>
</div>