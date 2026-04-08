@extends('layouts.kader')

@section('title', 'Detail Remaja')
@section('page-name', 'Buku Medis Remaja')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-slide-up-delay-1 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.15s forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.05); }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-24 h-24 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg"><i class="fas fa-folder-open text-indigo-600 text-3xl animate-pulse"></i></div>
    </div>
    <p class="text-indigo-900 font-black font-poppins tracking-widest text-[12px] animate-pulse uppercase">MEMBUKA REKAM MEDIS...</p>
</div>

<div class="max-w-7xl mx-auto relative pb-10 z-10">
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 relative z-10 animate-slide-up">
        <div class="flex items-center gap-4">
            <a href="{{ route('kader.data.remaja.index') }}" onclick="window.showLoader()" class="w-12 h-12 bg-white border border-slate-200 text-slate-500 rounded-[16px] flex items-center justify-center hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-600 transition-all shadow-sm group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Profil Peserta Remaja</h1>
                <p class="text-[13px] font-bold text-slate-500 mt-0.5">Buku Pemantauan Kesehatan Remaja (Kader)</p>
            </div>
        </div>
        <a href="{{ route('kader.data.remaja.edit', $remaja->id) }}" onclick="window.showLoader()" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-amber-500 text-white font-black text-[13px] rounded-xl hover:bg-amber-600 shadow-[0_8px_20px_rgba(245,158,11,0.3)] transition-all hover:-translate-y-0.5 uppercase tracking-widest w-full md:w-auto">
            <i class="fas fa-pen-nib"></i> Edit Profil
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">
        
        {{-- PANEL KIRI: PROFIL IDENTITAS --}}
        <div class="lg:col-span-4 space-y-6 animate-slide-up">
            <div class="glass-card rounded-[32px] overflow-hidden sticky top-24 border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                
                <div class="bg-gradient-to-br from-indigo-600 via-blue-600 to-sky-500 px-6 py-10 text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full blur-3xl transform translate-x-1/2 -translate-y-1/2"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-black/20 rounded-full blur-2xl transform -translate-x-1/2 translate-y-1/2"></div>

                    <div class="w-28 h-28 mx-auto bg-white rounded-[28px] shadow-2xl flex items-center justify-center text-indigo-500 text-5xl font-black relative z-10 border-4 border-white/20 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                        {{ strtoupper(substr($remaja->nama_lengkap, 0, 1)) }}
                    </div>
                    
                    <h2 class="text-2xl font-black text-white mt-5 relative z-10 font-poppins tracking-tight leading-tight">{{ $remaja->nama_lengkap }}</h2>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-black/20 text-indigo-50 text-[10px] font-black rounded-xl mt-3 relative z-10 backdrop-blur-md border border-white/10 tracking-widest uppercase">
                        <i class="fas fa-fingerprint text-indigo-300"></i> NIK: {{ $remaja->nik ?? 'TIDAK ADA' }}
                    </div>
                </div>

                <div class="p-6 md:p-8 bg-white/50 backdrop-blur-sm">
                    @php
                        $lahir = \Carbon\Carbon::parse($remaja->tanggal_lahir);
                        $sekarang = \Carbon\Carbon::now();
                        $umurCetak = $lahir->diff($sekarang)->y . ' Tahun ' . $lahir->diff($sekarang)->m . ' Bulan';
                    @endphp
                    
                    <div class="grid grid-cols-2 gap-4 text-center mb-8">
                        <div class="p-4 bg-white rounded-[20px] border border-slate-200 shadow-sm">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Usia Saat Ini</p>
                            <p class="text-sm font-black text-indigo-600 font-poppins">{{ $umurCetak }}</p>
                        </div>
                        <div class="p-4 bg-white rounded-[20px] border border-slate-200 shadow-sm">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Gender</p>
                            <div class="flex items-center justify-center gap-2">
                                @if($remaja->jenis_kelamin == 'L') 
                                    <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-500 flex items-center justify-center text-xs"><i class="fas fa-mars"></i></div>
                                    <p class="text-xs font-black text-slate-800 uppercase tracking-widest">Laki-Laki</p>
                                @else 
                                    <div class="w-6 h-6 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center text-xs"><i class="fas fa-venus"></i></div>
                                    <p class="text-xs font-black text-slate-800 uppercase tracking-widest">Perempuan</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center shrink-0"><i class="fas fa-school text-lg"></i></div>
                            <div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Info Akademik</span>
                                <span class="text-[13px] font-bold text-slate-800">{{ $remaja->sekolah ?? 'Tidak Terdata' }}</span>
                                <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded ml-1">Kls: {{ $remaja->kelas ?? '-' }}</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-slate-200 shadow-sm">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center shrink-0 shadow-sm"><i class="fas fa-user-friends text-lg"></i></div>
                            <div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Wali / Orang Tua</span>
                                <span class="text-[13px] font-black text-slate-800 block mb-0.5">{{ $remaja->nama_ortu }}</span>
                                <span class="text-[10px] font-bold text-slate-500 font-mono tracking-wide"><i class="fas fa-phone-alt text-slate-400"></i> {{ $remaja->telepon_ortu ?? 'Tidak ada kontak' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-slate-200/60 pt-6">
                        @if($remaja->user_id)
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
                                <a href="{{ route('kader.data.remaja.sync', $remaja->id) }}" onclick="return confirm('Sistem akan mencari akun Warga yang cocok dengan NIK ini. Lanjutkan?')" class="inline-flex items-center justify-center px-5 py-3 bg-white border border-slate-300 shadow-sm text-indigo-600 text-[11px] font-black rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-colors w-full uppercase tracking-widest">
                                    <i class="fas fa-sync-alt mr-2 animate-spin-slow"></i> Pindai NIK Remaja
                                </a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- PANEL KANAN: REKAM MEDIS KADER --}}
        <div class="lg:col-span-8 space-y-6 animate-slide-up-delay-1">
            
            <div class="glass-card rounded-[32px] overflow-hidden border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-6 bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-[20px] bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl shadow-sm border border-indigo-200"><i class="fas fa-clipboard-list"></i></div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800 font-poppins">Catatan Pemantauan Kader</h3>
                            <p class="text-[13px] font-medium text-slate-500 mt-1">Log antropometri, tensi, dan gula darah.</p>
                        </div>
                    </div>
                    <a href="{{ route('kader.pemeriksaan.create') }}?kategori=remaja&pasien_id={{ $remaja->id }}" onclick="window.showLoader()" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-indigo-600 text-white font-black text-[12px] rounded-xl hover:bg-indigo-700 shadow-[0_4px_15px_rgba(79,70,229,0.3)] transition-all shrink-0 uppercase tracking-widest hover:-translate-y-1 w-full sm:w-auto">
                        <i class="fas fa-plus"></i> Input Pengukuran
                    </a>
                </div>

                <div class="p-0">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left whitespace-nowrap min-w-[800px]">
                            <thead>
                                <tr class="bg-white border-b border-slate-100">
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest pl-8">Waktu & Tanggal</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Fisik Dasar (BB/TB)</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pemeriksaan Khusus</th>
                                    <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right pr-8">Detail Berkas</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($remaja->kunjungans ?? [] as $kunjungan)
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
                                            <div class="flex flex-wrap gap-2 max-w-[200px]">
                                                @if($kunjungan->pemeriksaan->tekanan_darah)
                                                    <span class="text-[10px] font-bold text-rose-600 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded">Tensi: {{ $kunjungan->pemeriksaan->tekanan_darah }}</span>
                                                @endif
                                                @if($kunjungan->pemeriksaan->gula_darah)
                                                    <span class="text-[10px] font-bold text-sky-600 bg-sky-50 border border-sky-100 px-2 py-0.5 rounded">Gula: {{ $kunjungan->pemeriksaan->gula_darah }}</span>
                                                @endif
                                                @if($kunjungan->pemeriksaan->lingkar_lengan)
                                                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded">LILA: {{ $kunjungan->pemeriksaan->lingkar_lengan }} cm</span>
                                                @endif
                                                @if(!$kunjungan->pemeriksaan->tekanan_darah && !$kunjungan->pemeriksaan->gula_darah && !$kunjungan->pemeriksaan->lingkar_lengan)
                                                    <span class="text-[10px] text-slate-400">-</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-300 font-medium text-[11px] italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-right pr-8">
                                        <a href="{{ route('kader.kunjungan.show', $kunjungan->id) }}" onclick="window.showLoader()" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-300 hover:bg-indigo-50 transition-all shadow-sm hover:shadow-md hover:scale-105">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-5 text-3xl border border-slate-100 shadow-inner"><i class="fas fa-folder-open"></i></div>
                                        <h4 class="font-black text-slate-800 text-lg font-poppins tracking-tight">Belum Ada Riwayat Pengukuran</h4>
                                        <p class="text-[13px] font-medium text-slate-500 mt-2 max-w-md mx-auto">Klik tombol 'Input Pengukuran' di atas jika remaja ini menghadiri posyandu hari ini.</p>
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
    setTimeout(window.hideLoader, 2000);
</script>
@endpush
@endsection