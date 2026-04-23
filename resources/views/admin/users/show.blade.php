@extends('layouts.admin')
@section('title', 'Detail Warga')
@section('page-name', 'Detail Profil Warga')

@section('content')
<style>
    .animate-pop-in { animation: popIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes popIn { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
</style>

<div class="max-w-5xl mx-auto animate-pop-in">

    {{-- Hero Section Clean --}}
    <div class="bg-gradient-to-br from-blue-600 to-sky-400 rounded-[2.5rem] p-8 md:p-12 mb-8 relative overflow-hidden shadow-lg border border-white/20 flex flex-col items-center justify-center text-center group">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="relative z-10 w-full flex flex-col items-center">
            {{-- Avatar Besar Premium --}}
            <div class="w-28 h-28 rounded-[2rem] bg-white border-4 border-white/50 text-blue-500 flex items-center justify-center font-black text-5xl shadow-xl mb-5 group-hover:scale-105 transition-transform duration-500">
                {{ strtoupper(substr($user->profile->full_name ?? $user->name, 0, 1)) }}
            </div>
            
            <h2 class="text-3xl md:text-4xl font-black text-white font-poppins tracking-tight text-shadow-sm">
                {{ $user->profile->full_name ?? $user->name }}
            </h2>
            
            {{-- Badge Status & NIK --}}
            <div class="mt-4 flex flex-wrap items-center justify-center gap-3">
                <span class="bg-white/20 backdrop-blur-md border border-white/30 text-white font-bold text-[11px] uppercase tracking-widest px-4 py-1.5 rounded-full shadow-sm">
                    <i class="fas fa-users mr-1"></i> Warga Posyandu
                </span>
                <span class="bg-white/20 backdrop-blur-md border border-white/30 text-white font-mono text-[11px] font-bold px-4 py-1.5 rounded-full flex items-center gap-2 shadow-sm">
                    <i class="fas fa-id-card"></i> NIK: {{ $user->nik ?? $user->profile?->nik ?? '-' }}
                </span>
                @if($user->status === 'active')
                    <span class="bg-emerald-400/90 backdrop-blur-md text-white font-bold text-[11px] uppercase tracking-widest px-4 py-1.5 rounded-full shadow-sm border border-emerald-300/50"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                @else
                    <span class="bg-rose-500/90 backdrop-blur-md text-white font-bold text-[11px] uppercase tracking-widest px-4 py-1.5 rounded-full shadow-sm border border-rose-400/50"><i class="fas fa-ban mr-1"></i> Nonaktif</span>
                @endif
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex gap-3">
                <a href="{{ route('admin.users.index') }}" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 text-white text-[11px] uppercase tracking-widest font-bold px-6 py-3 rounded-xl transition-all smooth-route"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-white hover:bg-slate-50 text-blue-600 text-[11px] uppercase tracking-widest font-bold px-6 py-3 rounded-xl transition-all shadow-lg hover:-translate-y-0.5 smooth-route"><i class="fas fa-edit mr-1"></i> Edit Profil</a>
            </div>
        </div>
    </div>

    {{-- Grid 2 Kolom untuk Biodata & Kontak --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        
        {{-- Card 1: Biodata Pribadi --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 hover:shadow-md hover:border-slate-200 transition-all">
            <div class="flex items-center gap-4 mb-6 border-b border-slate-50 pb-4">
                <div class="w-12 h-12 rounded-[1rem] bg-blue-50 text-blue-500 flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-user"></i>
                </div>
                <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest font-poppins">Biodata Pribadi</h4>
            </div>
            
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Jenis Kelamin</span>
                    <span class="text-[13px] font-black text-slate-700">{{ ($user->profile?->jenis_kelamin == 'L') ? 'Laki-Laki' : (($user->profile?->jenis_kelamin == 'P') ? 'Perempuan' : '-') }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tempat Lahir</span>
                    <span class="text-[13px] font-black text-slate-700">{{ $user->profile?->tempat_lahir ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tanggal Lahir</span>
                    <span class="text-[13px] font-black text-slate-700">{{ $user->profile?->tanggal_lahir ? \Carbon\Carbon::parse($user->profile->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Usia</span>
                    <span class="text-[13px] font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded">{{ $user->profile?->tanggal_lahir ? \Carbon\Carbon::parse($user->profile->tanggal_lahir)->age . ' Tahun' : '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Informasi Kontak --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 hover:shadow-md hover:border-slate-200 transition-all">
            <div class="flex items-center gap-4 mb-6 border-b border-slate-50 pb-4">
                <div class="w-12 h-12 rounded-[1rem] bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shrink-0">
                    <i class="fas fa-desktop"></i>
                </div>
                <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest font-poppins">Akses & Sistem</h4>
            </div>
            
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">ID / Email Sistem</span>
                    <span class="text-[13px] font-black text-slate-700 break-all">{{ $user->email }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Telepon / WhatsApp</span>
                    <span class="text-[13px] font-black text-slate-700">{{ $user->profile?->telepon ?? '-' }}</span>
                </div>
                <div class="flex flex-col border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</span>
                    <span class="text-[13px] font-black text-slate-700 leading-relaxed">{{ $user->profile?->alamat ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between border-b border-slate-50 pb-3 gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Akun Dibuat</span>
                    <span class="text-[13px] font-black text-slate-500">{{ $user->created_at->translatedFormat('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Section: Data Pasien Terhubung (Medical Data Links) --}}
    @if(isset($linkedData))
    <div class="mb-10">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6 px-2 border-b border-slate-200 pb-4">
            <div>
                <h3 class="text-lg font-black text-slate-800 font-poppins flex items-center gap-3">
                    <div class="w-8 h-8 bg-sky-100 text-sky-500 rounded-lg flex items-center justify-center text-sm"><i class="fas fa-link"></i></div>
                    Entitas Pasien Terhubung
                </h3>
                <p class="text-[12px] font-medium text-slate-500 mt-2">Data rekam medis berikut terhubung otomatis berdasarkan NIK warga.</p>
            </div>
        </div>

        @if($linkedData['balita']->isEmpty() && empty($linkedData['remaja']) && empty($linkedData['lansia']))
            {{-- Empty State Premium --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-200 border-dashed p-12 text-center shadow-sm">
                <div class="w-20 h-20 mx-auto bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-4 border border-slate-100">
                    <i class="fas fa-folder-open text-3xl"></i>
                </div>
                <h4 class="text-base font-black text-slate-800 font-poppins">Belum Ada Rekam Medis</h4>
                <p class="text-sm font-medium text-slate-500 mt-2 max-w-sm mx-auto">Sistem tidak menemukan data pasien (Balita, Remaja, Lansia) yang terkait dengan NIK warga ini.</p>
            </div>
        @else
            {{-- Floating Card Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                {{-- Data Balita (Sky Blue) --}}
                @foreach($linkedData['balita'] as $b)
                <div class="bg-white rounded-[2rem] border border-sky-100 p-6 shadow-sm hover:shadow-xl hover:shadow-sky-100 hover:-translate-y-1 transition-all duration-300 group flex items-center gap-5 overflow-hidden relative">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-sky-50 rounded-bl-full -mr-4 -mt-4 opacity-50 transition-transform group-hover:scale-110 pointer-events-none"></div>
                    <div class="relative z-10 w-14 h-14 rounded-2xl bg-sky-50 text-sky-500 flex items-center justify-center text-2xl shrink-0 border border-sky-100 group-hover:bg-sky-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-baby"></i>
                    </div>
                    <div class="relative z-10 flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-sky-50 text-sky-600 border border-sky-100 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded">Balita</span>
                        </div>
                        <h4 class="text-[15px] font-black text-slate-800 truncate">{{ $b->nama_lengkap }}</h4>
                        <div class="flex items-center gap-3 mt-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wide">
                            <span class="flex items-center gap-1"><i class="fas fa-id-card"></i> {{ $b->nik ?? '-' }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="flex items-center gap-1"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($b->tanggal_lahir)->age }} Tahun</span>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Data Remaja (Indigo) --}}
                @if($linkedData['remaja'])
                <div class="bg-white rounded-[2rem] border border-indigo-100 p-6 shadow-sm hover:shadow-xl hover:shadow-indigo-100 hover:-translate-y-1 transition-all duration-300 group flex items-center gap-5 overflow-hidden relative">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 rounded-bl-full -mr-4 -mt-4 opacity-50 transition-transform group-hover:scale-110 pointer-events-none"></div>
                    <div class="relative z-10 w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-2xl shrink-0 border border-indigo-100 group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="relative z-10 flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded">Remaja</span>
                        </div>
                        <h4 class="text-[15px] font-black text-slate-800 truncate">{{ $linkedData['remaja']->nama_lengkap }}</h4>
                        <div class="flex items-center gap-3 mt-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wide">
                            <span class="flex items-center gap-1"><i class="fas fa-id-card"></i> {{ $linkedData['remaja']->nik ?? '-' }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span class="flex items-center gap-1"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($linkedData['remaja']->tanggal_lahir)->age }} Tahun</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Data Lansia (Orange) --}}
                @if($linkedData['lansia'])
                <div class="bg-white rounded-[2rem] border border-orange-100 p-6 shadow-sm hover:shadow-xl hover:shadow-orange-100 hover:-translate-y-1 transition-all duration-300 group flex items-center gap-5 overflow-hidden relative">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-orange-50 rounded-bl-full -mr-4 -mt-4 opacity-50 transition-transform group-hover:scale-110 pointer-events-none"></div>
                    <div class="relative z-10 w-14 h-14 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl shrink-0 border border-orange-100 group-hover:bg-orange-500 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-wheelchair"></i>
                    </div>
                    <div class="relative z-10 flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-orange-50 text-orange-600 border border-orange-100 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded">Lansia</span>
                        </div>
                        <h4 class="text-[15px] font-black text-slate-800 truncate">{{ $linkedData['lansia']->nama_lengkap }}</h4>
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