@extends('layouts.kader')

@section('title', 'Detail Pemeriksaan')
@section('page-name', 'Laporan Medis')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .animate-fade-in { opacity: 0; animation: fadeIn 0.8s ease-out forwards; }
    
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.12);
        border-color: rgba(99, 102, 241, 0.3);
    }

    .blob-bg {
        position: absolute; filter: blur(80px); z-index: 0; opacity: 0.3;
        animation: floatBlob 12s infinite alternate ease-in-out;
        pointer-events: none;
    }
    @keyframes floatBlob {
        0% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(20px, -20px) scale(1.1); }
        100% { transform: translate(-10px, 10px) scale(0.9); }
    }

    .pulse-dot { animation: pulseRing 2s infinite; }
    @keyframes pulseRing { 
        0%, 100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4) } 
        50% { box-shadow: 0 0 0 6px rgba(255, 255, 255, 0) } 
    }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-200 opacity-100">
    <div class="relative w-20 h-20 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm">
            <i class="fas fa-file-medical text-indigo-600 text-2xl animate-pulse"></i>
        </div>
    </div>
    <p class="text-indigo-800 font-black font-poppins tracking-widest text-[11px] animate-pulse uppercase" id="loaderText">MEMUAT REKAM MEDIS...</p>
</div>

<div class="max-w-[1200px] mx-auto relative pb-10">
    
    <div class="blob-bg bg-indigo-300 w-96 h-96 rounded-full top-0 left-0 hidden md:block"></div>
    <div class="blob-bg bg-emerald-200 w-80 h-80 rounded-full bottom-0 right-10 hidden md:block" style="animation-delay: -3s;"></div>

    <div class="relative z-10 animate-slide-up">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('kader.pemeriksaan.index') }}" class="smooth-route w-12 h-12 bg-white border border-slate-200 text-slate-500 rounded-[16px] flex items-center justify-center hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm group">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Laporan Pemeriksaan</h1>
                    <p class="text-[13px] font-bold text-slate-500 mt-0.5">Detail pengukuran fisik dan diagnosa klinis.</p>
                </div>
            </div>
            
            @if($pemeriksaan->status_verifikasi == 'pending')
            <a href="{{ route('kader.pemeriksaan.edit', $pemeriksaan->id) }}" class="smooth-route inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-amber-500 text-white font-black text-[13px] rounded-xl hover:bg-amber-600 shadow-[0_8px_20px_rgba(245,158,11,0.3)] transition-all hover:-translate-y-1 uppercase tracking-widest w-full sm:w-auto">
                <i class="fas fa-edit text-lg"></i> Ubah Data
            </a>
            @endif
        </div>

        @if($pemeriksaan->status_verifikasi == 'pending')
        <div class="bg-gradient-to-r from-amber-50 to-orange-50/50 border border-amber-200 rounded-[24px] p-6 sm:p-8 mb-8 shadow-sm flex flex-col sm:flex-row items-center gap-6 relative overflow-hidden animate-fade-in" style="animation-delay: 0.1s;">
            <div class="absolute right-0 top-0 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="w-16 h-16 bg-white text-amber-500 rounded-2xl flex items-center justify-center text-3xl shrink-0 shadow-sm border border-amber-100 relative z-10 animate-pulse">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="text-center sm:text-left relative z-10">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-widest rounded-lg mb-2">
                    Status: Pending
                </div>
                <h4 class="font-black text-amber-900 text-xl font-poppins mb-1">Menunggu Verifikasi Bidan</h4>
                <p class="text-[13px] font-medium text-amber-700 max-w-xl">Data ukur fisik telah tersimpan dengan aman. Silakan tunggu Bidan Posyandu untuk memberikan diagnosa, status gizi, dan resep tindakan medis.</p>
            </div>
        </div>
        @elseif($pemeriksaan->status_verifikasi == 'verified')
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50/50 border border-emerald-200 rounded-[24px] p-6 sm:p-8 mb-8 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 relative overflow-hidden animate-fade-in" style="animation-delay: 0.1s;">
            <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="flex flex-col sm:flex-row items-center gap-6 relative z-10 text-center sm:text-left">
                <div class="w-16 h-16 bg-white text-emerald-500 rounded-2xl flex items-center justify-center text-3xl shrink-0 shadow-sm border border-emerald-100">
                    <i class="fas fa-check-double"></i>
                </div>
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-lg mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 pulse-dot"></span> Status: Selesai
                    </div>
                    <h4 class="font-black text-emerald-900 text-xl font-poppins mb-1">Telah Diverifikasi Bidan</h4>
                    <p class="text-[13px] font-medium text-emerald-700 max-w-xl">Hasil pemeriksaan medis sudah final, dikunci, dan notifikasi telah berhasil dikirimkan ke perangkat Warga.</p>
                </div>
            </div>
            <div class="text-center sm:text-right bg-white/60 p-4 rounded-xl border border-emerald-100 relative z-10 w-full sm:w-auto">
                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1"><i class="fas fa-user-md mr-1"></i> Diverifikasi Oleh</p>
                <p class="text-[15px] font-black text-emerald-900 font-poppins">{{ $pemeriksaan->verifikator->name ?? 'Bidan Posyandu' }}</p>
                <p class="text-[10px] font-bold text-emerald-600 mt-1">{{ \Carbon\Carbon::parse($pemeriksaan->verified_at)->format('d M Y, H:i') }}</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-4 space-y-6">
                <div class="glass-card rounded-[32px] overflow-hidden sticky top-28">
                    
                    <div class="p-8 text-center bg-gradient-to-br from-slate-50 to-indigo-50/30 border-b border-slate-100 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl"></div>
                        <div class="w-24 h-24 bg-white text-indigo-600 rounded-[24px] mx-auto flex items-center justify-center text-4xl font-black mb-5 shadow-[0_8px_20px_rgba(79,70,229,0.15)] border-2 border-white transform rotate-3 hover:rotate-0 transition-transform">
                            {{ strtoupper(substr($pemeriksaan->nama_pasien, 0, 1)) }}
                        </div>
                        <h3 class="font-black text-slate-800 text-xl font-poppins tracking-tight">{{ $pemeriksaan->nama_pasien }}</h3>
                        <div class="mt-3 flex items-center justify-center gap-2">
                            <span class="inline-flex items-center px-3 py-1.5 bg-slate-800 text-white text-[10px] font-black uppercase rounded-lg tracking-widest shadow-sm">
                                <i class="fas fa-tag mr-1.5"></i> {{ $pemeriksaan->kategori_pasien }}
                            </span>
                        </div>
                    </div>

                    <div class="p-8 space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center border border-indigo-100 shrink-0"><i class="fas fa-calendar-alt"></i></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Kunjungan</p>
                                <p class="text-[14px] font-bold text-slate-800">{{ \Carbon\Carbon::parse($pemeriksaan->tanggal_periksa)->translatedFormat('l, d F Y') }}</p>
                                <p class="text-[11px] font-bold text-slate-400 mt-0.5"><i class="far fa-clock"></i> Tercatat pk {{ $pemeriksaan->created_at->format('H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center border border-rose-100 shrink-0"><i class="fas fa-comment-medical"></i></div>
                            <div class="w-full">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Keluhan Awal</p>
                                <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl w-full relative">
                                    <i class="fas fa-quote-left absolute top-3 left-3 text-slate-200 text-xl"></i>
                                    <p class="text-[13px] font-semibold text-slate-700 italic relative z-10 pl-6 leading-relaxed">{{ $pemeriksaan->keluhan ?? 'Tidak ada keluhan klinis yang disampaikan.' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100 shrink-0"><i class="fas fa-user-nurse"></i></div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pemeriksa (Kader)</p>
                                <p class="text-[14px] font-bold text-slate-800">{{ $pemeriksaan->pemeriksa->name ?? 'Kader Posyandu' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 flex flex-col gap-6">
                
                <div class="glass-card rounded-[32px] overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 flex items-center gap-4 bg-slate-50/50">
                        <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center shadow-sm border border-sky-200"><i class="fas fa-ruler-combined text-lg"></i></div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 font-poppins">Hasil Pengukuran Fisik</h3>
                            <p class="text-[11px] font-bold text-slate-500 mt-0.5 uppercase tracking-widest">Data Antropometri Kader</p>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="p-5 bg-white rounded-[20px] border border-slate-200 shadow-sm hover:border-sky-300 hover:shadow-md transition-all text-center group">
                                <i class="fas fa-weight text-slate-300 text-2xl mb-3 group-hover:text-sky-400 transition-colors"></i>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Berat B.</p>
                                <p class="text-2xl font-black text-slate-800 font-poppins">{{ $pemeriksaan->berat_badan ?? '-' }}<span class="text-xs text-slate-500 font-bold ml-1">kg</span></p>
                            </div>
                            
                            <div class="p-5 bg-white rounded-[20px] border border-slate-200 shadow-sm hover:border-sky-300 hover:shadow-md transition-all text-center group">
                                <i class="fas fa-ruler-vertical text-slate-300 text-2xl mb-3 group-hover:text-sky-400 transition-colors"></i>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tinggi B.</p>
                                <p class="text-2xl font-black text-slate-800 font-poppins">{{ $pemeriksaan->tinggi_badan ?? '-' }}<span class="text-xs text-slate-500 font-bold ml-1">cm</span></p>
                            </div>

                            @if(in_array($pemeriksaan->kategori_pasien, ['remaja', 'lansia']))
                            <div class="p-5 bg-rose-50/50 rounded-[20px] border border-rose-100 shadow-sm hover:border-rose-300 hover:shadow-md transition-all text-center col-span-2 sm:col-span-2 group">
                                <i class="fas fa-heartbeat text-rose-300 text-2xl mb-3 group-hover:text-rose-500 transition-colors"></i>
                                <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1">Tekanan Darah (Tensi)</p>
                                <p class="text-2xl font-black text-rose-700 font-poppins">{{ $pemeriksaan->tekanan_darah ?? '-' }}<span class="text-xs text-rose-500 font-bold ml-1">mmHg</span></p>
                            </div>
                            @endif

                            @if($pemeriksaan->kategori_pasien == 'balita')
                            <div class="p-5 bg-white rounded-[20px] border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow-md transition-all text-center group">
                                <i class="fas fa-child text-slate-300 text-2xl mb-3 group-hover:text-indigo-400 transition-colors"></i>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">L. Kepala</p>
                                <p class="text-2xl font-black text-slate-800 font-poppins">{{ $pemeriksaan->lingkar_kepala ?? '-' }}<span class="text-xs text-slate-500 font-bold ml-1">cm</span></p>
                            </div>
                            <div class="p-5 bg-white rounded-[20px] border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow-md transition-all text-center group">
                                <i class="fas fa-child text-slate-300 text-2xl mb-3 group-hover:text-indigo-400 transition-colors"></i>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">L. Lengan</p>
                                <p class="text-2xl font-black text-slate-800 font-poppins">{{ $pemeriksaan->lingkar_lengan ?? '-' }}<span class="text-xs text-slate-500 font-bold ml-1">cm</span></p>
                            </div>
                            @endif
                        </div>

                        @if($pemeriksaan->kategori_pasien == 'lansia')
                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div class="p-4 bg-white border border-slate-200 rounded-[20px] text-center shadow-sm">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Gula Darah</p>
                                <p class="text-lg font-black text-slate-800 font-poppins">{{ $pemeriksaan->gula_darah ?? '-' }}</p>
                            </div>
                            <div class="p-4 bg-white border border-slate-200 rounded-[20px] text-center shadow-sm">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Asam Urat</p>
                                <p class="text-lg font-black text-slate-800 font-poppins">{{ $pemeriksaan->asam_urat ?? '-' }}</p>
                            </div>
                            <div class="p-4 bg-white border border-slate-200 rounded-[20px] text-center shadow-sm">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kolesterol</p>
                                <p class="text-lg font-black text-slate-800 font-poppins">{{ $pemeriksaan->kolesterol ?? '-' }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="glass-card rounded-[32px] overflow-hidden {{ $pemeriksaan->status_verifikasi == 'pending' ? 'bg-slate-50' : 'bg-indigo-50/20 border-indigo-100' }}">
                    <div class="px-8 py-6 border-b flex items-center gap-4 {{ $pemeriksaan->status_verifikasi == 'pending' ? 'border-slate-200 bg-slate-100/50' : 'border-indigo-100 bg-white' }}">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm {{ $pemeriksaan->status_verifikasi == 'pending' ? 'bg-slate-200 text-slate-500' : 'bg-indigo-600 text-white border border-indigo-500' }}"><i class="fas fa-user-md text-lg"></i></div>
                        <div>
                            <h3 class="text-lg font-black font-poppins {{ $pemeriksaan->status_verifikasi == 'pending' ? 'text-slate-600' : 'text-indigo-900' }}">Catatan Medis (Bidan)</h3>
                            <p class="text-[11px] font-bold mt-0.5 uppercase tracking-widest {{ $pemeriksaan->status_verifikasi == 'pending' ? 'text-slate-400' : 'text-indigo-400' }}">Analisis & Tindakan Lanjut</p>
                        </div>
                    </div>
                    
                    @if($pemeriksaan->status_verifikasi == 'verified')
                    <div class="p-8 space-y-6">
                        
                        <div class="flex items-center justify-between p-5 bg-white border border-indigo-100 rounded-[20px] shadow-sm hover:shadow-md transition-shadow">
                            <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest flex items-center gap-2"><i class="fas fa-clipboard-check text-indigo-400"></i> Status Gizi / Fisik</span>
                            <span class="px-5 py-2 bg-indigo-100 text-indigo-700 font-black rounded-xl uppercase tracking-widest border border-indigo-200 shadow-inner">{{ $pemeriksaan->status_gizi ?? 'Normal' }}</span>
                        </div>

                        <div>
                            <p class="text-[11px] font-black text-indigo-500 uppercase tracking-widest mb-2.5 flex items-center gap-2"><i class="fas fa-notes-medical"></i> Diagnosa Bidan</p>
                            <div class="p-6 bg-white border border-indigo-100 rounded-[24px] shadow-sm relative overflow-hidden">
                                <div class="absolute right-0 top-0 w-20 h-20 bg-indigo-500/5 rounded-bl-full pointer-events-none"></div>
                                <p class="text-[14px] font-semibold text-slate-800 leading-relaxed relative z-10">{{ $pemeriksaan->diagnosa ?? 'Belum ada catatan diagnosa.' }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[11px] font-black text-emerald-500 uppercase tracking-widest mb-2.5 flex items-center gap-2"><i class="fas fa-pills"></i> Tindakan / Resep Edukasi</p>
                            <div class="p-6 bg-emerald-50/30 border border-emerald-100 rounded-[24px] shadow-sm relative overflow-hidden">
                                <div class="absolute right-0 top-0 w-20 h-20 bg-emerald-500/5 rounded-bl-full pointer-events-none"></div>
                                <p class="text-[14px] font-semibold text-slate-800 leading-relaxed relative z-10">{{ $pemeriksaan->tindakan ?? 'Belum ada tindakan atau resep.' }}</p>
                            </div>
                        </div>

                    </div>
                    @else
                    <div class="p-16 text-center relative overflow-hidden">
                        <div class="absolute inset-0 bg-slate-100/50" style="background-image: repeating-linear-gradient(45deg, #f1f5f9 25%, transparent 25%, transparent 75%, #f1f5f9 75%, #f1f5f9), repeating-linear-gradient(45deg, #f1f5f9 25%, transparent 25%, transparent 75%, #f1f5f9 75%, #f1f5f9); background-position: 0 0, 10px 10px; background-size: 20px 20px;"></div>
                        <div class="relative z-10">
                            <div class="w-20 h-20 bg-white rounded-[24px] flex items-center justify-center text-slate-300 mx-auto mb-5 shadow-[0_4px_15px_rgba(0,0,0,0.05)] border border-slate-200 transform rotate-3"><i class="fas fa-lock text-3xl"></i></div>
                            <h4 class="text-lg font-black text-slate-700 font-poppins mb-2">Area Otoritas Bidan</h4>
                            <p class="text-[13px] font-medium text-slate-500 max-w-sm mx-auto leading-relaxed">Kolom ini terkunci. Akan terisi secara otomatis setelah Bidan Posyandu memvalidasi data ukur dan memberikan diagnosanya.</p>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const showLoader = (text = 'MEMUAT SISTEM...') => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            document.getElementById('loaderText').innerText = text;
            loader.style.display = 'flex';
            loader.offsetHeight; 
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100');
        }
    };

    window.addEventListener('pageshow', () => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loader.style.display = 'none', 300);
        }
    });

    document.querySelectorAll('.smooth-route').forEach(link => {
        link.addEventListener('click', function(e) {
            if(!this.classList.contains('target-blank') && this.target !== '_blank' && !e.ctrlKey) {
                showLoader('MEMUAT HALAMAN...');
            }
        });
    });
</script>
@endpush
@endsection