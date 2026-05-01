@extends('layouts.kader')

@section('title', 'Detail Lansia')
@section('page-name', 'Buku Medis Lansia')

@push('styles')
<style>
    /* ANIMASI MASUK */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-slide-up-delay-1 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* CUSTOM SCROLLBAR SAMARAN */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(16, 185, 129, 0.2); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(16, 185, 129, 0.5); }
    
    /* TABEL PRESISI DENGAN STICKY HEADER */
    .crm-table th { 
        background: #f0fdf4; 
        color: #059669; 
        font-size: 0.65rem; 
        font-weight: 900; 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
        padding: 1.25rem 1.5rem; 
        border-bottom: 1px solid #dcfce7; 
    }
    .crm-table th:first-child { border-top-left-radius: 24px; }
    .crm-table th:last-child { border-top-right-radius: 24px; }
    .crm-table td { padding: 1.25rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; transition: all 0.2s ease; }

    /* IMT Bar Visualizer */
    .imt-bar-track { height: 8px; border-radius: 9999px; background: linear-gradient(to right, #f59e0b 0%, #10b981 30%, #f59e0b 65%, #f43f5e 100%); position: relative; }
    .imt-bar-thumb { position: absolute; top: 50%; transform: translate(-50%, -50%); width: 16px; height: 16px; border-radius: 50%; background: white; border: 4px solid #1e293b; box-shadow: 0 4px 10px rgba(0,0,0,0.3); transition: left 1s cubic-bezier(0.4, 0, 0.2, 1); }
</style>
@endpush

@section('content')
{{-- PRELOADER --}}
<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-24 h-24 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-emerald-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-emerald-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-folder-open text-emerald-600 text-3xl animate-pulse"></i></div>
    </div>
    <p class="text-emerald-900 font-black font-poppins tracking-widest text-[12px] animate-pulse uppercase">MEMBUKA REKAM MEDIS...</p>
</div>

<div class="max-w-7xl mx-auto relative pb-12 z-10">
    
    {{-- AURA BACKGROUND KONSISTEN --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-400/10 rounded-full blur-[80px] pointer-events-none z-0"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-teal-400/10 rounded-full blur-[80px] pointer-events-none z-0"></div>

    {{-- HEADER PAGE --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 relative z-10 animate-slide-up">
        <div class="flex items-center gap-4">
            <a href="{{ route('kader.data.lansia.index') }}" onclick="window.showLoader()" class="w-14 h-14 bg-white border border-slate-200 text-slate-500 rounded-[16px] flex items-center justify-center hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform text-lg"></i>
            </a>
            <div>
                <div class="inline-flex items-center gap-1.5 text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1 bg-emerald-50 px-2.5 py-0.5 rounded border border-emerald-100"><i class="fas fa-book-medical"></i> Pemantauan Kesehatan Lansia</div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Profil Peserta Lansia</h1>
            </div>
        </div>
        <a href="{{ route('kader.data.lansia.edit', $lansia->id) }}" onclick="window.showLoader()" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-amber-500 text-white font-black text-[12px] rounded-full hover:bg-amber-600 shadow-[0_8px_20px_rgba(245,158,11,0.3)] transition-all hover:-translate-y-1 uppercase tracking-widest w-full md:w-auto">
            <i class="fas fa-pen-nib"></i> Edit Master Data
        </a>
    </div>

    {{-- GRID UTAMA PRESISI (12 KOLOM) --}}
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 relative z-10">
        
        {{-- ========================================== --}}
        {{-- PANEL KIRI: PROFIL IDENTITAS (4 KOLOM)     --}}
        {{-- ========================================== --}}
        <div class="xl:col-span-4 animate-slide-up">
            {{-- FIX: Tanpa class 'sticky' agar terscroll sejajar secara harmonis --}}
            <div class="bg-white rounded-[32px] overflow-hidden shadow-[0_10px_40px_-10px_rgba(16,185,129,0.08)] border border-emerald-100/50 flex flex-col">
                
                {{-- Header Avatar --}}
                <div class="bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-500 px-6 py-8 text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/20 rounded-full blur-2xl transform -translate-x-1/2 translate-y-1/2"></div>

                    <div class="w-24 h-24 mx-auto bg-white rounded-[24px] shadow-2xl flex items-center justify-center text-emerald-500 text-4xl font-black relative z-10 border-4 border-white/20 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                        {{ strtoupper(substr($lansia->nama_lengkap, 0, 1)) }}
                    </div>
                    
                    <h2 class="text-xl font-black text-white mt-4 relative z-10 font-poppins tracking-tight">{{ $lansia->nama_lengkap }}</h2>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-black/20 text-emerald-50 text-[10px] font-black rounded-full mt-2 relative z-10 backdrop-blur-md border border-white/10 tracking-widest uppercase">
                        <i class="fas fa-fingerprint text-emerald-300"></i> NIK: {{ $lansia->nik ?? 'TIDAK ADA' }}
                    </div>
                </div>

                {{-- Informasi Klinis & Pribadi --}}
                <div class="p-8 bg-white flex-1 flex flex-col">
                    
                    @php
                        $lahir = \Carbon\Carbon::parse($lansia->tanggal_lahir);
                        $sekarang = \Carbon\Carbon::now();
                        $umurCetak = $lahir->diff($sekarang)->y . ' Tahun';
                    @endphp
                    
                    <div class="grid grid-cols-2 gap-4 text-center mb-6">
                        <div class="p-4 bg-slate-50 rounded-[20px] border border-slate-100 shadow-sm">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Usia Saat Ini</p>
                            <p class="text-sm font-black text-emerald-600 font-poppins">{{ $umurCetak }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-[20px] border border-slate-100 shadow-sm">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Gender</p>
                            <div class="flex items-center justify-center gap-2">
                                @if($lansia->jenis_kelamin == 'L') 
                                    <div class="w-6 h-6 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center text-xs"><i class="fas fa-mars"></i></div>
                                    <p class="text-xs font-black text-slate-800 uppercase tracking-widest">Pria</p>
                                @else 
                                    <div class="w-6 h-6 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center text-xs"><i class="fas fa-venus"></i></div>
                                    <p class="text-xs font-black text-slate-800 uppercase tracking-widest">Wanita</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- IMT VISUALIZER --}}
                    @if($lansia->imt)
                        @php
                            $imt = $lansia->imt;
                            $pct = min(100, max(0, (($imt - 15) / 20) * 100)); // Rentang IMT 15 - 35
                            $kat = $imt < 18.5 ? 'Kurus' : ($imt < 25 ? 'Normal' : ($imt < 27 ? 'Gemuk' : 'Obesitas'));
                            $col = $imt < 18.5 ? 'text-amber-500' : ($imt < 25 ? 'text-emerald-500' : 'text-rose-500');
                        @endphp
                        <div class="bg-emerald-50/50 p-5 rounded-[24px] border border-emerald-100 shadow-sm mb-6 text-center">
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Indeks Massa Tubuh (Awal)</p>
                            <p class="text-3xl font-black {{ $col }} font-poppins">{{ $imt }} <span class="text-[9px] bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm text-slate-600 align-middle ml-2 uppercase">{{ $kat }}</span></p>
                            <div class="mt-4 px-2">
                                <div class="imt-bar-track">
                                    <div class="imt-bar-thumb" style="left: {{ $pct }}%;"></div>
                                </div>
                                <div class="flex justify-between mt-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                    <span>Kurus</span>
                                    <span>Ideal</span>
                                    <span>Gemuk</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-4 mb-6">
                        <div class="flex items-center gap-4 p-5 rounded-[20px] bg-white border border-slate-100 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-500 flex items-center justify-center shrink-0"><i class="fas fa-wheelchair"></i></div>
                            <div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Kemandirian Fisik</span>
                                <span class="text-[12px] font-bold text-slate-800 uppercase">{{ $lansia->kemandirian ?? 'MANDIRI' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 p-5 rounded-[20px] bg-white border border-slate-100 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-400 flex items-center justify-center shrink-0 shadow-sm"><i class="fas fa-notes-medical"></i></div>
                            <div>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Penyakit Bawaan</span>
                                <span class="text-[12px] font-bold text-slate-800 leading-tight block">{{ $lansia->penyakit_bawaan ?? 'Sehat / Tidak ada' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 p-5 rounded-[20px] bg-sky-50 border border-sky-100 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-white text-sky-500 flex items-center justify-center shrink-0 shadow-sm"><i class="fas fa-phone-alt"></i></div>
                            <div>
                                <span class="text-[9px] font-black text-sky-400 uppercase tracking-widest block mb-0.5">Kontak Darurat</span>
                                <span class="text-[12px] font-black text-sky-800 font-mono tracking-wide">{{ $lansia->telepon_keluarga ?? 'Tidak Tersedia' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto border-t border-slate-100 pt-6">
                        @if($lansia->user_id)
                            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-[20px] flex items-center gap-4 shadow-sm">
                                <div class="w-10 h-10 bg-white text-emerald-500 rounded-full flex items-center justify-center shadow-sm shrink-0"><i class="fas fa-check-circle"></i></div>
                                <div>
                                    <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Akun Warga Aktif</p>
                                    <p class="text-[12px] font-bold text-emerald-800">Sinkronisasi Berhasil</p>
                                </div>
                            </div>
                        @else
                            <div class="p-5 bg-slate-50 border border-slate-100 rounded-[20px] text-center shadow-sm">
                                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Akun Belum Terhubung</p>
                                <form action="{{ route('kader.data.lansia.sync', $lansia->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Sistem akan mencari akun Warga yang cocok dengan NIK ini. Lanjutkan?')" class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-slate-200 shadow-sm text-emerald-600 text-[10px] font-black rounded-xl hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 transition-all w-full uppercase tracking-widest">
                                        <i class="fas fa-sync-alt mr-2"></i> Pindai NIK Lansia
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- PANEL KANAN: REKAM MEDIS & LAYANAN (8 COL) --}}
        {{-- ========================================== --}}
        <div class="xl:col-span-8 flex flex-col gap-8 animate-slide-up-delay-1">
            
            {{-- WIDGET STATISTIK (SEJAJAR RATA ATAS) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.03)] p-6 rounded-[24px] flex items-center gap-4">
                    <div class="w-14 h-14 rounded-[16px] bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0"><i class="fas fa-clipboard-list"></i></div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Kunjungan</p>
                        <p class="text-lg font-black text-slate-800 font-poppins">{{ count($lansia->kunjungans) }} Kali</p>
                    </div>
                </div>
                
                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.03)] p-6 rounded-[24px] flex items-center gap-4">
                    <div class="w-14 h-14 rounded-[16px] bg-teal-50 text-teal-600 flex items-center justify-center text-xl shrink-0"><i class="fas fa-tint"></i></div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Golongan Darah</p>
                        <p class="text-xl font-black text-slate-800 font-poppins">{{ $lansia->golongan_darah ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="bg-white border border-slate-100 shadow-[0_4px_20px_-5px_rgba(0,0,0,0.03)] p-6 rounded-[24px] flex items-center gap-4">
                    <div class="w-14 h-14 rounded-[16px] bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl shrink-0"><i class="fas fa-clock"></i></div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Pembaruan Profil</p>
                        <p class="text-[12px] font-black text-slate-800 font-poppins mt-0.5">{{ $lansia->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- TABEL RIWAYAT PEMERIKSAAN METABOLIK (DENGAN SCROLL INTERNAL) --}}
            <div class="bg-white rounded-[32px] overflow-hidden border border-slate-100 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.03)] flex-1 flex flex-col">
                <div class="p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[20px] bg-white text-emerald-600 flex items-center justify-center text-2xl shadow-sm border border-slate-200"><i class="fas fa-heartbeat"></i></div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800 font-poppins">Riwayat Medis Metabolik</h3>
                            <p class="text-[12px] font-medium text-slate-500 mt-1">Log tensi, gula darah, asam urat, & kolesterol.</p>
                        </div>
                    </div>
                    <a href="{{ route('kader.pemeriksaan.create') }}?kategori=lansia&pasien_id={{ $lansia->id }}" onclick="window.showLoader()" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-emerald-600 text-white font-black text-[11px] rounded-full hover:bg-emerald-700 shadow-[0_4px_15px_rgba(16,185,129,0.3)] transition-all shrink-0 uppercase tracking-widest hover:-translate-y-1 w-full sm:w-auto">
                        <i class="fas fa-plus"></i> Input Cek Medis
                    </a>
                </div>

                {{-- WADAH TABEL YANG DIBATASI TINGGINYA (Scroll Vertical) --}}
                <div class="p-2 flex-1 bg-white">
                    <div class="overflow-y-auto overflow-x-auto custom-scrollbar max-h-[450px] px-2 pb-2 rounded-2xl">
                        <table class="w-full text-left whitespace-nowrap min-w-[850px] crm-table relative">
                            {{-- Header tabel dibuat menempel (sticky) saat di-scroll --}}
                            <thead class="sticky top-0 z-20 shadow-sm">
                                <tr>
                                    <th class="pl-6 w-40">Waktu Kunjungan</th>
                                    <th>Fisik (BB/TB)</th>
                                    <th>Cek Darah & Tensi</th>
                                    <th class="text-right pr-6">Detail Berkas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lansia->kunjungans ?? [] as $kunjungan)
                                <tr class="hover:bg-emerald-50/40 transition-colors group">
                                    <td class="pl-6">
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-800 text-[13px] mb-1">{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d M Y') }}</span>
                                            <span class="text-[9px] font-bold text-slate-400 bg-white border border-slate-100 w-max px-2 py-0.5 rounded shadow-sm"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('H:i') }} WIB</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($kunjungan->pemeriksaan)
                                            <div class="flex items-center gap-3">
                                                <div class="flex flex-col bg-white border border-slate-100 rounded-xl px-3 py-1.5 shadow-sm text-center">
                                                    <span class="text-[8px] text-slate-400 font-black uppercase tracking-widest mb-0.5">Berat</span>
                                                    <span class="text-[12px] font-black text-indigo-600">{{ $kunjungan->pemeriksaan->berat_badan ?? '-' }}<span class="text-[9px] text-slate-400">kg</span></span>
                                                </div>
                                                <div class="flex flex-col bg-white border border-slate-100 rounded-xl px-3 py-1.5 shadow-sm text-center">
                                                    <span class="text-[8px] text-slate-400 font-black uppercase tracking-widest mb-0.5">Tinggi</span>
                                                    <span class="text-[12px] font-black text-emerald-600">{{ $kunjungan->pemeriksaan->tinggi_badan ?? '-' }}<span class="text-[9px] text-slate-400">cm</span></span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-400 italic">Tidak diukur</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($kunjungan->pemeriksaan)
                                            <div class="flex flex-wrap gap-2 max-w-[280px]">
                                                @if($kunjungan->pemeriksaan->tekanan_darah)
                                                    <span class="text-[9px] font-bold text-rose-600 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded uppercase tracking-widest shadow-sm" title="Tekanan Darah (Tensi)"><i class="fas fa-heartbeat"></i> Tensi: {{ $kunjungan->pemeriksaan->tekanan_darah }}</span>
                                                @endif
                                                @if($kunjungan->pemeriksaan->gula_darah)
                                                    <span class="text-[9px] font-bold text-sky-600 bg-sky-50 border border-sky-100 px-2 py-0.5 rounded uppercase tracking-widest shadow-sm" title="Gula Darah"><i class="fas fa-cubes"></i> Gula: {{ $kunjungan->pemeriksaan->gula_darah }}</span>
                                                @endif
                                                @if($kunjungan->pemeriksaan->asam_urat)
                                                    <span class="text-[9px] font-bold text-amber-600 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded uppercase tracking-widest shadow-sm" title="Asam Urat"><i class="fas fa-bone"></i> A.Urat: {{ $kunjungan->pemeriksaan->asam_urat }}</span>
                                                @endif
                                                @if($kunjungan->pemeriksaan->kolesterol)
                                                    <span class="text-[9px] font-bold text-purple-600 bg-purple-50 border border-purple-100 px-2 py-0.5 rounded uppercase tracking-widest shadow-sm" title="Kolesterol"><i class="fas fa-hamburger"></i> Kol: {{ $kunjungan->pemeriksaan->kolesterol }}</span>
                                                @endif
                                                
                                                @if(!$kunjungan->pemeriksaan->tekanan_darah && !$kunjungan->pemeriksaan->gula_darah && !$kunjungan->pemeriksaan->asam_urat && !$kunjungan->pemeriksaan->kolesterol)
                                                    <span class="text-[10px] text-slate-400 font-medium">-</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-[10px] font-bold text-slate-400 italic">-</span>
                                        @endif
                                    </td>
                                    <td class="text-right pr-6">
                                        <a href="{{ route('kader.kunjungan.show', $kunjungan->id) }}" onclick="window.showLoader()" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-emerald-600 hover:border-emerald-300 hover:bg-emerald-50 transition-all shadow-sm hover:shadow-md hover:scale-105">
                                            <i class="fas fa-chevron-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-24 text-center border-none">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl border border-slate-100 shadow-inner"><i class="fas fa-clipboard-list"></i></div>
                                        <h4 class="font-black text-slate-800 text-lg font-poppins tracking-tight">Belum Ada Riwayat Cek Medis</h4>
                                        <p class="text-[12px] font-medium text-slate-500 mt-1 max-w-sm mx-auto">Klik tombol 'Input Cek Medis' untuk menambahkan data cek darah dan ukuran fisik lansia.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    window.hideLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { 
            l.classList.remove('opacity-100', 'pointer-events-auto'); 
            l.classList.add('opacity-0', 'pointer-events-none'); 
            setTimeout(() => l.style.display = 'none', 300); 
        }
    };

    window.showLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { 
            l.style.display = 'flex'; 
            l.classList.remove('opacity-0', 'pointer-events-none'); 
            l.classList.add('opacity-100', 'pointer-events-auto'); 
        }
    };

    document.addEventListener('DOMContentLoaded', window.hideLoader);
    window.addEventListener('pageshow', window.hideLoader);
    
    // Failsafe darurat
    setTimeout(window.hideLoader, 2500); 
</script>
@endpush
@endsection