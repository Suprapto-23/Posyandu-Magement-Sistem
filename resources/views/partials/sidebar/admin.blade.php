@php
    // Palet Warna Medical SaaS Premium (Sky Blue & Teal Gradient)
    $activeClass = 'bg-gradient-to-r from-sky-500 to-teal-500 text-white shadow-lg shadow-sky-500/25 font-bold scale-[1.02] rounded-[14px]';
    $inactiveClass = 'text-slate-400 hover:bg-slate-800/60 hover:text-sky-300 font-medium rounded-[14px] transition-all duration-300';
    
    $activeIconClass = 'text-white drop-shadow-sm';
    $inactiveIconClass = 'text-slate-500 group-hover:text-sky-400 transition-colors duration-300';
@endphp

<div class="space-y-6 font-poppins">
    
    {{-- BLOK 1: CORE SYSTEM --}}
    <div>
        <p class="px-5 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-3">Core System</p>
        <div class="space-y-1.5 px-3">
            <a href="{{ route('admin.dashboard') }}" class="smooth-route group flex items-center gap-3.5 px-4 py-3 {{ request()->routeIs('admin.dashboard*') ? $activeClass : $inactiveClass }}">
                <div class="w-5 flex justify-center">
                    <i class="fas fa-tachometer-alt text-[16px] {{ request()->routeIs('admin.dashboard*') ? $activeIconClass : $inactiveIconClass }}"></i>
                </div>
                <span class="text-[13.5px] tracking-wide">Overview Sistem</span>
            </a>
        </div>
    </div>

    {{-- BLOK 2: DATA MASTER --}}
    <div>
        <p class="px-5 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-3">Data Master</p>
        <div class="space-y-1.5 px-3">
            
            <a href="{{ route('admin.users.index') }}" class="smooth-route group flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.users.*') ? $activeClass : $inactiveClass }}">
                <div class="flex items-center gap-3.5">
                    <div class="w-5 flex justify-center">
                        <i class="fas fa-users text-[16px] {{ request()->routeIs('admin.users.*') ? $activeIconClass : $inactiveIconClass }}"></i>
                    </div>
                    <span class="text-[13.5px] tracking-wide">User Warga</span>
                </div>
                
                {{-- Lencana (Badge) NIK hanya muncul jika tidak sedang aktif --}}
                @if(!request()->routeIs('admin.users.*'))
                    <span class="bg-slate-800/80 text-slate-400 text-[9px] font-black px-2 py-0.5 rounded shadow-inner border border-slate-700/50">NIK</span>
                @endif
            </a>

            <a href="{{ route('admin.kaders.index') }}" class="smooth-route group flex items-center gap-3.5 px-4 py-3 {{ request()->routeIs('admin.kaders.*') ? $activeClass : $inactiveClass }}">
                <div class="w-5 flex justify-center">
                    <i class="fas fa-user-nurse text-[16px] {{ request()->routeIs('admin.kaders.*') ? $activeIconClass : $inactiveIconClass }}"></i>
                </div>
                <span class="text-[13.5px] tracking-wide">Akun Kader</span>
            </a>

            <a href="{{ route('admin.bidans.index') }}" class="smooth-route group flex items-center gap-3.5 px-4 py-3 {{ request()->routeIs('admin.bidans.*') ? $activeClass : $inactiveClass }}">
                <div class="w-5 flex justify-center">
                    <i class="fas fa-user-md text-[16px] {{ request()->routeIs('admin.bidans.*') ? $activeIconClass : $inactiveIconClass }}"></i>
                </div>
                <span class="text-[13.5px] tracking-wide">Akun Bidan</span>
            </a>
            
        </div>
    </div>
</div>