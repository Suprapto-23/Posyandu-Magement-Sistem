@extends('layouts.admin')
@section('title', 'Detail Warga')
@section('page-name', 'Detail Profil Warga')

@section('content')
<div class="max-w-5xl mx-auto" style="animation: menuPop 0.4s ease-out forwards;">

    {{-- Hero Section (Simetri Rata Tengah) --}}
    <div class="bg-gradient-to-br from-obsidian-900 to-slate-800 rounded-[32px] p-8 md:p-12 mb-8 relative overflow-hidden shadow-xl border border-slate-700 flex flex-col items-center justify-center text-center group">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="relative z-10 w-full flex flex-col items-center">
            {{-- Avatar Besar --}}
            <div class="w-24 h-24 rounded-full bg-obsidian-900 border-2 border-amber-500 text-amber-500 flex items-center justify-center font-black text-4xl shadow-[0_0_25px_rgba(245,158,11,0.4)] mb-5">
                {{ strtoupper(substr($user->profile->full_name ?? $user->name, 0, 1)) }}
            </div>
            
            <h2 class="text-3xl font-black text-white font-poppins tracking-tight">
                {{ $user->profile->full_name ?? $user->name }}
            </h2>
            
            {{-- Badge Status & NIK --}}
            <div class="mt-3 flex items-center justify-center gap-3">
                <span class="bg-white/10 backdrop-blur-md border border-white/20 text-slate-200 font-mono text-xs font-bold px-4 py-1.5 rounded-lg flex items-center gap-2">
                    <i class="fas fa-id-card text-amber-500"></i> NIK: {{ $user->nik ?? $user->profile?->nik ?? '-' }}
                </span>
                @if($user->status === 'active')
                    <span class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 font-bold text-[11px] uppercase tracking-widest px-4 py-1.5 rounded-lg"><i class="fas fa-check-circle mr-1"></i> Akun Aktif</span>
                @else
                    <span class="bg-rose-500/20 border border-rose-500/50 text-rose-400 font-bold text-[11px] uppercase tracking-widest px-4 py-1.5 rounded-lg"><i class="fas fa-ban mr-1"></i> Nonaktif</span>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex gap-3">
                <a href="{{ route('admin.users.index') }}" class="bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-all smooth-route"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-amber-500 hover:bg-amber-400 text-obsidian-900 text-xs font-bold px-5 py-2.5 rounded-xl transition-all shadow-[0_4px_15px_rgba(245,158,11,0.3)] smooth-route"><i class="fas fa-edit mr-1"></i> Edit Profil</a>
            </div>
        </div>
    </div>

    {{-- Grid 2 Kolom untuk Biodata & Kontak --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        {{-- Card 1: Biodata Pribadi --}}
        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm p-8 hover:shadow-md hover:border-slate-300 transition-all">
            <div class="flex items-center gap-4 mb-6 border-b border-slate-100 pb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 text-obsidian-900 border border-slate-200 flex items-center justify-center text-xl shadow-sm shrink-0">
                    <i class="fas fa-user"></i>
                </div>
                <h4 class="text-sm font-black text-obsidian-900 uppercase tracking-widest font-poppins">Biodata Warga</h4>
            </div>
            
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Jenis Kelamin</span>
                    <span class="text-sm font-bold text-slate-800">{{ ($user->profile?->jenis_kelamin == 'L') ? 'Laki-Laki' : (($user->profile?->jenis_kelamin == 'P') ? 'Perempuan' : '-') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tempat Lahir</span>
                    <span class="text-sm font-bold text-slate-800">{{ $user->profile?->tempat_lahir ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Lahir</span>
                    <span class="text-sm font-bold text-slate-800">{{ $user->profile?->tanggal_lahir ? \Carbon\Carbon::parse($user->profile->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Usia</span>
                    <span class="text-sm font-black text-amber-600">{{ $user->profile?->tanggal_lahir ? \Carbon\Carbon::parse($user->profile->tanggal_lahir)->age . ' Tahun' : '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Informasi Kontak --}}
        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm p-8 hover:shadow-md hover:border-slate-300 transition-all">
            <div class="flex items-center gap-4 mb-6 border-b border-slate-100 pb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-xl shadow-sm shrink-0">
                    <i class="fas fa-address-book"></i>
                </div>
                <h4 class="text-sm font-black text-obsidian-900 uppercase tracking-widest font-poppins">Kontak & Sistem</h4>
            </div>
            
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">ID / Email Sistem</span>
                    <span class="text-sm font-bold text-slate-800 break-all">{{ $user->email }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Telepon / WhatsApp</span>
                    <span class="text-sm font-bold text-slate-800">{{ $user->profile?->telepon ?? '-' }}</span>
                </div>
                <div class="flex flex-col border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</span>
                    <span class="text-sm font-bold text-slate-800 leading-relaxed">{{ $user->profile?->alamat ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Akun Dibuat</span>
                    <span class="text-sm font-bold text-slate-800">{{ $user->created_at->translatedFormat('d F Y, H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Section: Data Pasien Terhubung (Diperbarui menjadi Floating Cards) --}}
    @if(isset($linkedData))
    <div class="mt-12 mb-10">
        
        {{-- Floating Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6 px-2">
            <div>
                <h3 class="text-xl font-black text-obsidian-900 font-poppins flex items-center gap-3">
                    <i class="fas fa-link text-amber-500"></i> Entitas Pasien Terhubung
                </h3>
                <p class="text-[13px] font-medium text-slate-500 mt-1">Data rekam medis yang berafiliasi dengan NIK {{ $user->profile->full_name ?? $user->name }}.</p>
            </div>
        </div>

        {{-- Conditional Content --}}
        @if($linkedData['balita']->isEmpty() && empty($linkedData['remaja']) && empty($linkedData['lansia']))
            {{-- Empty State Premium --}}
            <div class="bg-white rounded-[32px] border border-slate-200 border-dashed p-12 text-center shadow-sm">
                <div class="w-20 h-20 mx-auto bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4 border border-slate-100">
                    <i class="fas fa-folder-open text-3xl"></i>
                </div>
                <h4 class="text-base font-black text-obsidian-900 font-poppins">Belum Ada Rekam Medis</h4>
                <p class="text-sm font-medium text-slate-500 mt-2 max-w-sm mx-auto">Sistem belum menemukan data pasien (Balita, Remaja, atau Lansia) yang terkait dengan NIK warga ini.</p>
            </div>
        @else
            {{-- Floating Card Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Loop Data Balita --}}
                @foreach($linkedData['balita'] as $b)
                <div class="bg-white rounded-[24px] border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:border-amber-300 transition-all duration-300 group flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-obsidian-800 to-obsidian-900 text-amber-500 flex items-center justify-center text-2xl shadow-inner shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-baby"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-blue-50 text-blue-600 border border-blue-200 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded flex items-center gap-1">Balita</span>
                        </div>
                        <h4 class="text-base font-bold text-obsidian-900 truncate">{{ $b->nama_lengkap }}</h4>
                        <div class="flex items-center gap-3 mt-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wide">
                            <span class="flex items-center gap-1"><i class="fas fa-id-card"></i> {{ $b->nik ?? '-' }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="flex items-center gap-1"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($b->tanggal_lahir)->age }} Tahun</span>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Data Remaja --}}
                @if($linkedData['remaja'])
                <div class="bg-white rounded-[24px] border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:border-amber-300 transition-all duration-300 group flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-obsidian-800 to-obsidian-900 text-amber-500 flex items-center justify-center text-2xl shadow-inner shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded flex items-center gap-1">Remaja</span>
                        </div>
                        <h4 class="text-base font-bold text-obsidian-900 truncate">{{ $linkedData['remaja']->nama_lengkap }}</h4>
                        <div class="flex items-center gap-3 mt-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wide">
                            <span class="flex items-center gap-1"><i class="fas fa-id-card"></i> {{ $linkedData['remaja']->nik ?? '-' }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="flex items-center gap-1"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($linkedData['remaja']->tanggal_lahir)->age }} Tahun</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Data Lansia --}}
                @if($linkedData['lansia'])
                <div class="bg-white rounded-[24px] border border-slate-200 p-6 shadow-sm hover:shadow-xl hover:border-amber-300 transition-all duration-300 group flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-obsidian-800 to-obsidian-900 text-amber-500 flex items-center justify-center text-2xl shadow-inner shrink-0 group-hover:scale-110 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-wheelchair"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-amber-50 text-amber-700 border border-amber-200 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded flex items-center gap-1">Lansia</span>
                        </div>
                        <h4 class="text-base font-bold text-obsidian-900 truncate">{{ $linkedData['lansia']->nama_lengkap }}</h4>
                        <div class="flex items-center gap-3 mt-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wide">
                            <span class="flex items-center gap-1"><i class="fas fa-id-card"></i> {{ $linkedData['lansia']->nik ?? '-' }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="flex items-center gap-1"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($linkedData['lansia']->tanggal_lahir)->age }} Tahun</span>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        @endif
    </div>
    @endif

</div>
@endsection