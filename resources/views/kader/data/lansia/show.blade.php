@extends('layouts.kader')

@section('title', 'Detail Lansia')
@section('page-name', 'Buku Medis Lansia')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-slide-up-delay-1 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 10px 40px -10px rgba(16, 185, 129, 0.05); }

    /* IMT Bar Visualizer */
    .imt-bar-track { height: 10px; border-radius: 9999px; background: linear-gradient(to right, #f59e0b 0%, #10b981 30%, #f59e0b 65%, #f43f5e 100%); position: relative; }
    .imt-bar-thumb { position: absolute; top: 50%; transform: translate(-50%, -50%); width: 20px; height: 20px; border-radius: 50%; background: white; border: 4px solid #1e293b; box-shadow: 0 4px 10px rgba(0,0,0,0.3); transition: left 1s cubic-bezier(0.4, 0, 0.2, 1); }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-24 h-24 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-emerald-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-emerald-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-folder-open text-emerald-600 text-3xl animate-pulse"></i></div>
    </div>
    <p class="text-emerald-900 font-black font-poppins tracking-widest text-[12px] animate-pulse uppercase">MEMBUKA REKAM MEDIS...</p>
</div>

<div class="max-w-7xl mx-auto relative pb-10 z-10">
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 relative z-10 animate-slide-up">
        <div class="flex items-center gap-4">
            <a href="{{ route('kader.data.lansia.index') }}" onclick="window.showLoader()" class="w-12 h-12 bg-white border border-slate-200 text-slate-500 rounded-[16px] flex items-center justify-center hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-600 transition-all shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Profil Peserta Lansia</h1>
                <p class="text-[13px] font-bold text-slate-500 mt-0.5">Buku Pemantauan Kesehatan Lansia Berkala</p>
            </div>
        </div>
        <a href="{{ route('kader.data.lansia.edit', $lansia->id) }}" onclick="window.showLoader()" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-amber-500 text-white font-black text-[13px] rounded-xl hover:bg-amber-600 shadow-[0_8px_20px_rgba(245,158,11,0.3)] transition-all hover:-translate-y-0.5 uppercase tracking-widest w-full md:w-auto">
            <i class="fas fa-pen-nib"></i> Edit Profil
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">
        
        {{-- PANEL KIRI: PROFIL IDENTITAS --}}
        <div class="lg:col-span-4 space-y-6 animate-slide-up">
            <div class="glass-card rounded-[32px] overflow-hidden sticky top-24 border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                
                <div class="bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-500 px-6 py-10 text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-black/20 rounded-full blur-2xl transform -translate-x-1/2 translate-y-1/2"></div>

                    <div class="w-28 h-28 mx-auto bg-white rounded-[28px] shadow-2xl flex items-center justify-center text-emerald-500 text-5xl font-black relative z-10 border-4 border-white/20 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                        {{ strtoupper(substr($lansia->nama_lengkap, 0, 1)) }}
                    </div>
                    
                    <h2 class="text-2xl font-black text-white mt-5 relative z-10 font-poppins tracking-tight leading-tight">{{ $lansia->nama_lengkap }}</h2>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-black/20 text-emerald-50 text-[10px] font-black rounded-xl mt-3 relative z-10 backdrop-blur-md border border-white/10 tracking-widest uppercase">
                        <i class="fas fa-fingerprint text-emerald-300"></i> NIK: {{ $lansia->nik ?? 'TIDAK ADA' }}
                    </div>
                </div>

                <div class="p-6 md:p-8 bg-white/50 backdrop-blur-sm">
                    @php
                        $lahir = \Carbon\Carbon::parse($lansia->tanggal_lahir);
                        $sekarang = \Carbon\Carbon::now();
                        $umurCetak = $lahir->diff($sekarang)->y . ' Tahun';
                    @endphp
                    
                    <div class="grid grid-cols-2 gap-4 text-center mb-8">
                        <div class="p-4 bg-white rounded-[20px] border border-slate-200 shadow-sm">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Usia Saat Ini</p>
                            <p class="text-xl font-black text-emerald-600 font-poppins">{{ $umurCetak }}</p>
                        </div>
                        <div class="p-4 bg-white rounded-[20px] border border-slate-200 shadow-sm">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Gender</p>
                            <div class="flex items-center justify-center gap-2">
                                @if($lansia->jenis_kelamin == 'L') 
                                    <div class="w-7 h-7 rounded-full bg-sky-100 text-sky-500 flex items-center justify-center text-sm"><i class="fas fa-mars"></i></div>
                                    <p class="text-xs font-black text-slate-800 uppercase tracking-widest">Pria</p>
                                @else 
                                    <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center text-sm"><i class="fas fa-venus"></i></div>
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
                        <div class="bg-slate-50 p-5 rounded-[24px] border border-slate-200 shadow-sm mb-6 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Indeks Massa Tubuh (Kader)</p>
                            <p class="text-3xl font-black {{ $col }} font-poppins">{{ $imt }} <span class="text-[10px] bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm text-slate-600 align-middle ml-2 uppercase">{{ $kat }}</span></p>
                            <div class="mt-5 px-2">
                                <div class="imt-bar-track">
                                    <div class="imt-bar-thumb" style="left: {{ $pct }}%;"></div>
                                </div>
                                <div class="flex justify-between mt-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                    <span>Kurus</span>
                                    <span>Normal</span>
                                    <span>Gemuk</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-500 flex items-center justify-center shrink-0"><i class="fas fa-wheelchair text-lg"></i></div>
                            <div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Kemandirian Fisik</span>
                                <span class="text-[13px] font-bold text-slate-800 uppercase">{{ $lansia->kemandirian ?? 'MANDIRI' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-400 flex items-center justify-center shrink-0 shadow-sm"><i class="fas fa-notes-medical text-lg"></i></div>
                            <div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Penyakit Bawaan</span>
                                <span class="text-[12px] font-bold text-slate-800 leading-tight block">{{ $lansia->penyakit_bawaan ?? 'Tidak ada / Sehat' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-sky-50 border border-sky-100 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-white text-sky-500 flex items-center justify-center shrink-0 shadow-sm"><i class="fas fa-phone-alt text-lg"></i></div>
                            <div>
                                <span class="text-[10px] font-black text-sky-400 uppercase tracking-widest block mb-0.5">Kontak Darurat / Keluarga</span>
                                <span class="text-[13px] font-black text-sky-800 font-mono tracking-wide">{{ $lansia->telepon_keluarga ?? 'Tidak Tersedia' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-slate-200/60 pt-6">
                        @if($lansia->user_id)
                            <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-4 shadow-sm">
                                <div class="w-12 h-12 bg-white text-emerald-500 rounded-full flex items-center justify-center shadow-sm shrink-0"><i class="fas fa-check-circle text-lg"></i></div>
                                <div>
                                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Akun Warga Aktif</p>
                                    <p class="text-[13px] font-bold text-emerald-800">Sinkronisasi Berhasil</p>
                                </div>
                            </div>
                        @else
                            <div class="p-5 bg-slate-50 border border-slate-200 rounded-2xl text-center shadow-sm">
                                <div class="w-12 h-12 bg-white text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm"><i class="fas fa-link-slash text-lg"></i></div>
                                <p class="text-[11px] font-black text-slate-600 uppercase tracking-widest mb-1.5">Aplikasi Belum Terhubung</p>
                                <a href="{{ route('kader.data.lansia.sync', $lansia->id) }}" onclick="return confirm('Sistem akan mencari akun Warga yang cocok dengan NIK ini. Lanjutkan?')" class="inline-flex items-center justify-center px-5 py-3 bg-white border border-slate-300 shadow-sm text-emerald-600 text-[11px] font-black rounded-xl hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700 transition-colors w-full uppercase tracking-widest">
                                    <i class="fas fa-sync-alt mr-2 animate-spin-slow"></i> Pindai NIK Lansia
                                </a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- PANEL KANAN: REKAM MEDIS & METABOLIK --}}
        <div class="lg:col-span-8 space-y-6 animate-slide-up-delay-1">
            
            <div class="glass-card rounded-[32px] overflow-hidden border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[20px] bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl shadow-sm border border-emerald-200"><i class="fas fa-heartbeat"></i></div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800 font-poppins">Riwayat Medis Metabolik</h3>
                            <p class="text-[13px] font-medium text-slate-500 mt-1">Log tensi, gula darah, asam urat, & kolesterol.</p>
                        </div>
                    </div>
                    <a href="{{ route('kader.pemeriksaan.create') }}?kategori=lansia&pasien_id={{ $lansia->id }}" onclick="window.showLoader()" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-emerald-600 text-white font-black text-[12px] rounded-xl hover:bg-emerald-700 shadow-[0_4px_15px_rgba(16,185,129,0.3)] transition-all shrink-0 uppercase tracking-widest hover:-translate-y-1 w-full sm:w-auto">
                        <i class="fas fa-plus"></i> Input Cek Medis
                    </a>
                </div>

                <div class="p-0">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left whitespace-nowrap min-w-[850px]">
                            <thead>
                                <tr class="bg-white border-b border-slate-100">
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest pl-8 w-40">Waktu Kunjungan</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Fisik (BB/TB)</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Cek Darah & Tensi</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right pr-8">Detail Berkas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($lansia->kunjungans ?? [] as $kunjungan)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-5 pl-8">
                                        <div class="flex flex-col">
                                            <span class="font-black text-slate-800 text-[14px] mb-1">{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d M Y') }}</span>
                                            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-slate-400 bg-slate-100 w-max px-2 py-0.5 rounded-md"><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('H:i') }} WIB</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($kunjungan->pemeriksaan)
                                            <div class="flex items-center gap-3">
                                                <div class="flex flex-col bg-white border border-slate-200 rounded-xl px-3 py-1.5 shadow-sm text-center">
                                                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-0.5">Berat</span>
                                                    <span class="text-[13px] font-black text-indigo-600">{{ $kunjungan->pemeriksaan->berat_badan ?? '-' }} <span class="text-[9px] text-slate-400">kg</span></span>
                                                </div>
                                                <div class="flex flex-col bg-white border border-slate-200 rounded-xl px-3 py-1.5 shadow-sm text-center">
                                                    <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest mb-0.5">Tinggi</span>
                                                    <span class="text-[13px] font-black text-emerald-600">{{ $kunjungan->pemeriksaan->tinggi_badan ?? '-' }} <span class="text-[9px] text-slate-400">cm</span></span>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-slate-300 font-medium text-[11px] italic">Tidak diukur</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($kunjungan->pemeriksaan)
                                            <div class="flex flex-wrap gap-2 max-w-[250px]">
                                                @if($kunjungan->pemeriksaan->tekanan_darah)
                                                    <span class="text-[10px] font-bold text-rose-600 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded shadow-sm" title="Tekanan Darah (Tensi)"><i class="fas fa-heartbeat"></i> {{ $kunjungan->pemeriksaan->tekanan_darah }}</span>
                                                @endif
                                                @if($kunjungan->pemeriksaan->gula_darah)
                                                    <span class="text-[10px] font-bold text-sky-600 bg-sky-50 border border-sky-100 px-2 py-0.5 rounded shadow-sm" title="Gula Darah"><i class="fas fa-cubes"></i> Gula: {{ $kunjungan->pemeriksaan->gula_darah }}</span>
                                                @endif
                                                @if($kunjungan->pemeriksaan->asam_urat)
                                                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded shadow-sm" title="Asam Urat"><i class="fas fa-bone"></i> A.Urat: {{ $kunjungan->pemeriksaan->asam_urat }}</span>
                                                @endif
                                                @if($kunjungan->pemeriksaan->kolesterol)
                                                    <span class="text-[10px] font-bold text-purple-600 bg-purple-50 border border-purple-100 px-2 py-0.5 rounded shadow-sm" title="Kolesterol"><i class="fas fa-hamburger"></i> Kol: {{ $kunjungan->pemeriksaan->kolesterol }}</span>
                                                @endif
                                                
                                                @if(!$kunjungan->pemeriksaan->tekanan_darah && !$kunjungan->pemeriksaan->gula_darah && !$kunjungan->pemeriksaan->asam_urat && !$kunjungan->pemeriksaan->kolesterol)
                                                    <span class="text-[10px] text-slate-400 italic">Hanya ukur fisik</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-300 font-medium text-[11px] italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-right pr-8">
                                        <a href="{{ route('kader.kunjungan.show', $kunjungan->id) }}" onclick="window.showLoader()" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-emerald-600 hover:border-emerald-300 hover:bg-emerald-50 transition-all shadow-sm hover:shadow-md hover:scale-105">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-5 text-3xl border border-slate-100 shadow-inner"><i class="fas fa-folder-open"></i></div>
                                        <h4 class="font-black text-slate-800 text-lg font-poppins tracking-tight">Belum Ada Riwayat Cek Medis</h4>
                                        <p class="text-[13px] font-medium text-slate-500 mt-2 max-w-md mx-auto">Klik tombol 'Input Cek Medis' untuk menambahkan data cek darah dan ukuran fisik lansia.</p>
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
        if(l) { l.classList.remove('opacity-100', 'pointer-events-auto'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); }
    };
    window.showLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { l.style.display = 'flex'; l.classList.remove('opacity-0', 'pointer-events-none'); l.classList.add('opacity-100', 'pointer-events-auto'); }
    };

    document.addEventListener('DOMContentLoaded', window.hideLoader);
    window.addEventListener('load', window.hideLoader);
    setTimeout(window.hideLoader, 2000); // Failsafe
</script>
@endpush
@endsection