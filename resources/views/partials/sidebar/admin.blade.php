@php
    // Palet Warna Executive Obsidian (Dark Slate & Amber Gold)
    $activeClass = 'bg-slate-800 border-l-4 border-amber-500 text-amber-400 shadow-sm transition-all';
    $inactiveClass = 'text-slate-400 hover:bg-slate-800 hover:text-slate-100 transition-all border-l-4 border-transparent';
    
    $activeIconClass = 'text-amber-400';
    $inactiveIconClass = 'text-slate-500 group-hover:text-slate-300';
@endphp

<div class="space-y-6">
    
    <div>
        <p class="px-5 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 font-poppins">Core System</p>
        <a href="{{ route('admin.dashboard') }}" class="smooth-route group flex items-center gap-3 px-4 py-3 rounded-r-xl font-bold text-sm {{ request()->routeIs('admin.dashboard*') ? $activeClass : $inactiveClass }}">
            <i class="fas fa-tachometer-alt w-6 text-center text-[18px] transition-colors {{ request()->routeIs('admin.dashboard*') ? $activeIconClass : $inactiveIconClass }}"></i>
            <span class="font-poppins tracking-wide">Dashboard</span>
        </a>
    </div>

    <div>
        <p class="px-5 text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3 font-poppins">Data Master</p>
        <div class="space-y-1">
            
            <a href="{{ route('admin.users.index') }}" class="smooth-route group flex items-center justify-between px-4 py-3 rounded-r-xl font-bold text-sm {{ request()->routeIs('admin.users.*') ? $activeClass : $inactiveClass }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-users w-6 text-center text-[18px] transition-colors {{ request()->routeIs('admin.users.*') ? $activeIconClass : $inactiveIconClass }}"></i>
                    <span class="font-poppins tracking-wide">User Warga</span>
                </div>
                <span class="bg-slate-800 text-slate-400 text-[9px] font-black px-2 py-0.5 rounded uppercase border border-slate-700">NIK</span>
            </a>

            <a href="{{ route('admin.kaders.index') }}" class="smooth-route group flex items-center justify-between px-4 py-3 rounded-r-xl font-bold text-sm {{ request()->routeIs('admin.kaders.*') ? $activeClass : $inactiveClass }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-nurse w-6 text-center text-[18px] transition-colors {{ request()->routeIs('admin.kaders.*') ? $activeIconClass : $inactiveIconClass }}"></i>
                    <span class="font-poppins tracking-wide">Akun Kader</span>
                </div>
            </a>

            <a href="{{ route('admin.bidans.index') }}" class="smooth-route group flex items-center justify-between px-4 py-3 rounded-r-xl font-bold text-sm {{ request()->routeIs('admin.bidans.*') ? $activeClass : $inactiveClass }}">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-md w-6 text-center text-[18px] transition-colors {{ request()->routeIs('admin.bidans.*') ? $activeIconClass : $inactiveIconClass }}"></i>
                    <span class="font-poppins tracking-wide">Akun Bidan</span>
                </div>
            </a>
            
        </div>
    </div>
</div>