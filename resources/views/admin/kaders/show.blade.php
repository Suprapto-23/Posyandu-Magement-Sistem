@extends('layouts.admin')
@section('title', 'Detail Profil Kader')
@section('page-name', 'Profil Kader')

@section('content')
<div class="max-w-5xl mx-auto" style="animation: menuPop 0.4s ease-out forwards;">

    {{-- Hero Section (Simetri Rata Tengah) --}}
    <div class="bg-gradient-to-br from-obsidian-900 to-slate-800 rounded-[32px] p-8 md:p-12 mb-8 relative overflow-hidden shadow-xl border border-slate-700 flex flex-col items-center justify-center text-center group">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="relative z-10 w-full flex flex-col items-center">
            {{-- Avatar Besar --}}
            <div class="w-24 h-24 rounded-full bg-obsidian-900 border-2 border-amber-500 text-amber-500 flex items-center justify-center font-black text-4xl shadow-[0_0_25px_rgba(245,158,11,0.4)] mb-5">
                {{ strtoupper(substr($kader->profile->full_name ?? $kader->name, 0, 1)) }}
            </div>
            
            <h2 class="text-3xl font-black text-white font-poppins tracking-tight">
                {{ $kader->profile->full_name ?? $kader->name }}
            </h2>
            
            {{-- Badge Jabatan, Status & NIK --}}
            <div class="mt-3 flex flex-wrap items-center justify-center gap-3">
                <span class="bg-amber-500/20 border border-amber-500/50 text-amber-400 font-bold text-[11px] uppercase tracking-widest px-4 py-1.5 rounded-lg">
                    <i class="fas fa-id-badge mr-1"></i> {{ $kader->kader?->jabatan ?? 'Kader Biasa' }}
                </span>
                <span class="bg-white/10 backdrop-blur-md border border-white/20 text-slate-200 font-mono text-xs font-bold px-4 py-1.5 rounded-lg flex items-center gap-2">
                    <i class="fas fa-id-card text-amber-500"></i> NIK: {{ $kader->nik ?? $kader->profile?->nik ?? '-' }}
                </span>
                @if($kader->status === 'active')
                    <span class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 font-bold text-[11px] uppercase tracking-widest px-4 py-1.5 rounded-lg"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                @else
                    <span class="bg-rose-500/20 border border-rose-500/50 text-rose-400 font-bold text-[11px] uppercase tracking-widest px-4 py-1.5 rounded-lg"><i class="fas fa-ban mr-1"></i> Nonaktif</span>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex gap-3">
                <a href="{{ route('admin.kaders.index') }}" class="bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition-all smooth-route"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                <a href="{{ route('admin.kaders.edit', $kader->id) }}" class="bg-amber-500 hover:bg-amber-400 text-obsidian-900 text-xs font-bold px-5 py-2.5 rounded-xl transition-all shadow-[0_4px_15px_rgba(245,158,11,0.3)] smooth-route"><i class="fas fa-edit mr-1"></i> Edit Profil</a>
            </div>
        </div>
    </div>

    {{-- Grid 2 Kolom untuk Biodata & Sistem --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        
        {{-- Card 1: Biodata Pribadi --}}
        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm p-8 hover:shadow-md hover:border-slate-300 transition-all">
            <div class="flex items-center gap-4 mb-6 border-b border-slate-100 pb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 text-obsidian-900 border border-slate-200 flex items-center justify-center text-xl shadow-sm shrink-0">
                    <i class="fas fa-user"></i>
                </div>
                <h4 class="text-sm font-black text-obsidian-900 uppercase tracking-widest font-poppins">Biodata Diri</h4>
            </div>
            
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Jenis Kelamin</span>
                    <span class="text-sm font-bold text-slate-800">{{ ($kader->profile?->jenis_kelamin == 'L') ? 'Laki-Laki' : (($kader->profile?->jenis_kelamin == 'P') ? 'Perempuan' : '-') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tempat Lahir</span>
                    <span class="text-sm font-bold text-slate-800">{{ $kader->profile?->tempat_lahir ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Lahir</span>
                    <span class="text-sm font-bold text-slate-800">{{ $kader->profile?->tanggal_lahir ? \Carbon\Carbon::parse($kader->profile->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Usia</span>
                    <span class="text-sm font-black text-amber-600">{{ $kader->profile?->tanggal_lahir ? \Carbon\Carbon::parse($kader->profile->tanggal_lahir)->age . ' Tahun' : '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Informasi Kontak & Sistem --}}
        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm p-8 hover:shadow-md hover:border-slate-300 transition-all">
            <div class="flex items-center gap-4 mb-6 border-b border-slate-100 pb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center text-xl shadow-sm shrink-0">
                    <i class="fas fa-desktop"></i>
                </div>
                <h4 class="text-sm font-black text-obsidian-900 uppercase tracking-widest font-poppins">Akses & Kontak</h4>
            </div>
            
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Email (Login)</span>
                    <span class="text-sm font-bold text-slate-800 break-all">{{ $kader->email }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Telepon / WhatsApp</span>
                    <span class="text-sm font-bold text-slate-800">{{ $kader->profile?->telepon ?? '-' }}</span>
                </div>
                <div class="flex flex-col border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</span>
                    <span class="text-sm font-bold text-slate-800 leading-relaxed">{{ $kader->profile?->alamat ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Login Terakhir</span>
                    <span class="text-sm font-bold text-slate-800">{{ $kader->last_login_at ? \Carbon\Carbon::parse($kader->last_login_at)->translatedFormat('d M Y, H:i') : 'Belum Pernah' }}</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection