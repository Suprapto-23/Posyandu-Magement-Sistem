@extends('layouts.user')

@section('content')
@php
    // Kalkulasi statistik cepat
    $totalVaksin = $riwayatImunisasi->count();
    $anakTerlindungi = $riwayatImunisasi->pluck('kunjungan.pasien_id')->unique()->count();
@endphp

<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen space-y-6">
    
    <div class="relative bg-gradient-to-r from-emerald-500 to-teal-400 rounded-[2rem] p-8 md:p-10 shadow-[0_10px_30px_-10px_rgba(16,185,129,0.4)] overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        
        <div class="absolute -right-10 -bottom-10 opacity-20 pointer-events-none">
            <i class="fas fa-shield-virus text-[12rem] text-white"></i>
        </div>
        
        <div class="relative z-10 text-white w-full md:w-2/3">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest mb-4 border border-white/30 shadow-sm">
                <i class="fas fa-check-circle text-emerald-200"></i>
                Data Resmi Posyandu
            </div>
            
            <h1 class="text-3xl md:text-4xl font-black mb-3 tracking-tight">Buku Imunisasi Digital</h1>
            <p class="text-emerald-50 font-medium text-sm md:text-base leading-relaxed opacity-90 max-w-xl">
                Catatan perlindungan kekebalan tubuh anak Anda. Semua data di bawah ini telah divalidasi dan disahkan secara medis oleh Bidan.
            </p>
        </div>

        <div class="relative z-10 shrink-0 hidden md:block">
            <a href="{{ route('user.balita.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-emerald-600 font-bold rounded-xl shadow-lg hover:scale-105 transition-transform">
                <i class="fas fa-baby-carriage"></i> Lihat KMS Anak
            </a>
        </div>
    </div>

    @if($totalVaksin > 0)
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white p-5 md:p-6 rounded-3xl border border-emerald-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-syringe"></i>
            </div>
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Total Vaksin</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $totalVaksin }} <span class="text-xs text-slate-500 font-medium">Dosis</span></h3>
            </div>
        </div>
        <div class="bg-white p-5 md:p-6 rounded-3xl border border-teal-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-teal-50 text-teal-500 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-child"></i>
            </div>
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Terlindungi</p>
                <h3 class="text-2xl font-black text-slate-800">{{ $anakTerlindungi }} <span class="text-xs text-slate-500 font-medium">Anak</span></h3>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 lg:p-8 relative">
        <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-4">
            <h2 class="text-[13px] font-black text-slate-800 uppercase tracking-widest"><i class="fas fa-history text-emerald-500 mr-2"></i> Riwayat Pemberian Vaksin</h2>
        </div>

        <div class="relative">
            @forelse($riwayatImunisasi as $imun)
                @php
                    $anak = $imun->kunjungan->pasien ?? null;
                    $namaAnak = $anak ? $anak->nama_lengkap : 'Data Anak';
                    $iconAnak = ($anak && $anak->jenis_kelamin == 'L') ? 'fa-child text-blue-500' : 'fa-child text-pink-500';
                    $bgColor = ($anak && $anak->jenis_kelamin == 'L') ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-pink-50 text-pink-700 border-pink-100';
                @endphp

                <div class="mb-6 last:mb-0 relative pl-8 md:pl-12 py-2 group">
                    
                    <div class="absolute left-[11px] md:left-[19px] top-0 bottom-0 w-[2px] bg-emerald-100 group-last:bg-transparent group-last:bottom-auto group-last:h-full"></div>
                    
                    <div class="absolute left-0 md:left-2 top-6 w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center ring-4 ring-white shadow-md z-10">
                        <i class="fas fa-check text-[10px]"></i>
                    </div>
                    
                    <div class="bg-white border border-slate-200 hover:border-emerald-300 rounded-2xl p-5 transition-all shadow-sm hover:shadow-md flex flex-col lg:flex-row gap-5 lg:items-center relative overflow-hidden">
                        
                        <i class="fas fa-award absolute -right-4 -bottom-4 text-7xl text-emerald-50 opacity-50 pointer-events-none"></i>
                        
                        <div class="lg:w-1/3 shrink-0 border-b lg:border-b-0 lg:border-r border-slate-100 pb-4 lg:pb-0 lg:pr-6 relative z-10 flex flex-col justify-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Diberikan Pada</p>
                            <h4 class="text-sm font-black text-slate-800">{{ \Carbon\Carbon::parse($imun->tanggal_imunisasi)->translatedFormat('l, d F Y') }}</h4>
                            
                            <div class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border {{ $bgColor }}">
                                <i class="fas {{ $iconAnak }} text-[11px]"></i>
                                <span class="text-[11px] font-bold truncate max-w-[150px]">{{ $namaAnak }}</span>
                            </div>
                        </div>

                        <div class="flex-1 relative z-10">
                            <h3 class="text-lg md:text-xl font-black text-emerald-600 mb-2">{{ $imun->jenis_imunisasi ?? $imun->nama_vaksin ?? 'Vaksin Imunisasi' }}</h3>
                            
                            @if($imun->keterangan)
                                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 inline-block w-full">
                                    <p class="text-xs font-medium text-slate-600 leading-relaxed">
                                        <i class="fas fa-quote-left text-slate-300 mr-1"></i> {{ $imun->keterangan }}
                                    </p>
                                </div>
                            @else
                                <p class="text-xs font-medium text-slate-400 italic">Telah diberikan sesuai jadwal. Tidak ada catatan tambahan.</p>
                            @endif
                        </div>

                        <div class="shrink-0 relative z-10 self-start lg:self-center mt-2 lg:mt-0">
                            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-xl">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-emerald-600">Disahkan Oleh</p>
                                    <p class="text-xs font-bold text-slate-800">Bidan Posyandu</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="py-16 flex flex-col items-center justify-center text-center">
                    <div class="w-20 h-20 bg-emerald-50 text-emerald-400 rounded-full flex items-center justify-center text-4xl shadow-sm mb-5 relative">
                        <i class="fas fa-shield-virus relative z-10"></i>
                        <span class="absolute top-0 right-0 w-5 h-5 bg-rose-500 rounded-full border-2 border-white text-white flex items-center justify-center text-[10px] font-black">!</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-700 mb-2">Belum Ada Rekam Imunisasi</h3>
                    <p class="text-sm font-medium text-slate-500 max-w-md leading-relaxed">
                        Kami tidak menemukan catatan imunisasi yang divalidasi oleh Bidan. Pastikan Anda rutin membawa anak ke Posyandu untuk mendapatkan imunisasi dasar lengkap.
                    </p>
                    <a href="{{ route('user.jadwal.index') }}" class="mt-6 text-xs font-bold text-white bg-emerald-500 hover:bg-emerald-600 px-6 py-3 rounded-xl transition-colors shadow-sm">
                        Cek Jadwal Posyandu Terdekat
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <div class="md:hidden pt-4">
        <a href="{{ route('user.balita.index') }}" class="flex items-center justify-center gap-2 w-full px-6 py-3.5 bg-white border border-slate-200 text-emerald-600 text-sm font-bold rounded-xl shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke KMS Anak
        </a>
    </div>

</div>
@endsection