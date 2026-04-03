@extends('layouts.kader')

@section('title', 'Detail Remaja')
@section('page-name', 'Detail Profil')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.05); }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-sm z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-16 h-16 mb-4">
        <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="absolute inset-0 flex items-center justify-center text-indigo-500"><i class="fas fa-user-graduate text-lg animate-pulse"></i></div>
    </div>
</div>

<div class="max-w-[1200px] mx-auto animate-slide-up">
    
    {{-- HEADER KEMBALI & EDIT --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('kader.data.remaja.index') }}" class="w-10 h-10 bg-white border border-slate-200 text-slate-500 rounded-xl flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Detail Rekam Medis</h1>
                <p class="text-sm font-bold text-slate-500">ID Register: <span class="text-indigo-500 font-mono">{{ $remaja->kode_remaja }}</span></p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('kader.data.remaja.edit', $remaja->id) }}" class="px-4 py-2 bg-amber-50 text-amber-600 font-bold text-sm rounded-xl border border-amber-200 hover:bg-amber-100 transition-colors shadow-sm">
                <i class="fas fa-edit mr-1"></i> Edit Profil
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- ========================================== --}}
        {{-- KARTU PROFIL REMAJA (KIRI)                 --}}
        {{-- ========================================== --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-card rounded-[2rem] p-6 text-center relative overflow-hidden border-t-4 border-t-indigo-500">
                <div class="w-24 h-24 rounded-full mx-auto mb-4 flex items-center justify-center text-4xl font-black shadow-inner {{ $remaja->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-500' : 'bg-pink-100 text-pink-500' }}">
                    {{ substr($remaja->nama_lengkap, 0, 1) }}
                </div>
                <h2 class="text-xl font-black text-slate-800">{{ $remaja->nama_lengkap }}</h2>
                <p class="text-sm font-bold text-slate-500 mb-4"><i class="far fa-id-card"></i> {{ $remaja->nik ?? 'TIDAK ADA NIK' }}</p>
                
                <div class="flex justify-center gap-2 mb-6">
                    <span class="px-3 py-1 rounded-lg text-xs font-black {{ $remaja->jenis_kelamin == 'L' ? 'bg-blue-50 text-blue-600 border border-blue-100' : 'bg-pink-50 text-pink-600 border border-pink-100' }}">
                        {{ $remaja->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}
                    </span>
                    <span class="px-3 py-1 rounded-lg text-xs font-black bg-indigo-50 text-indigo-600 border border-indigo-100">
                        {{ \Carbon\Carbon::parse($remaja->tanggal_lahir)->age }} TAHUN
                    </span>
                </div>

                <div class="space-y-3 text-left">
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Kelahiran</p>
                        <p class="text-sm font-bold text-slate-700"><i class="fas fa-map-marker-alt text-rose-400 w-4 text-center mr-1"></i> {{ $remaja->tempat_lahir }}, {{ \Carbon\Carbon::parse($remaja->tanggal_lahir)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Pendidikan</p>
                        <p class="text-sm font-bold text-slate-700"><i class="fas fa-school text-indigo-400 w-4 text-center mr-1"></i> {{ $remaja->sekolah ?? 'Tidak Sekolah' }} {!! $remaja->kelas ? "<span class='text-slate-400 font-medium'>(Kls {$remaja->kelas})</span>" : '' !!}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <p class="text-[10px] font-black uppercase text-slate-400 mb-1">Orang Tua & Kontak</p>
                        <p class="text-sm font-bold text-slate-700"><i class="fas fa-user-friends text-emerald-400 w-4 text-center mr-1"></i> {{ $remaja->nama_ortu }}</p>
                        @if($remaja->telepon_ortu)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $remaja->telepon_ortu) }}" target="_blank" class="text-xs font-black text-emerald-600 mt-2 inline-flex items-center gap-1 hover:underline bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100"><i class="fab fa-whatsapp"></i> {{ $remaja->telepon_ortu }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- RIWAYAT PEMERIKSAAN KESEHATAN (KANAN)      --}}
        {{-- ========================================== --}}
        <div class="lg:col-span-2">
            <div class="glass-card rounded-[2rem] p-6 h-full border-t-4 border-t-emerald-500 flex flex-col">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4 shrink-0">
                    <h3 class="text-lg font-black text-slate-800"><i class="fas fa-heartbeat text-rose-500 mr-2"></i> Riwayat Pemeriksaan</h3>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-lg border border-emerald-200 uppercase tracking-widest flex items-center gap-1.5 shadow-sm">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Data Real-Time
                    </span>
                </div>

                {{-- Area Scrollable untuk Kartu Kunjungan --}}
                <div class="flex-1 overflow-y-auto custom-scrollbar pr-2 space-y-4 max-h-[600px]">
                    
                    {{-- LOOPING DATA PEMERIKSAAN DARI MODEL --}}
                    @forelse($remaja->pemeriksaans as $periksa)
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all duration-300 group relative overflow-hidden shadow-sm hover:shadow-md">
                            
                            {{-- Aksen Garis Kiri saat Hover --}}
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-white border border-slate-200 text-emerald-500 flex items-center justify-center font-black shadow-sm shrink-0">
                                        <i class="fas fa-stethoscope"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800">{{ \Carbon\Carbon::parse($periksa->tanggal_kunjungan ?? $periksa->created_at)->translatedFormat('d F Y') }}</p>
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Cek Rutin Bulanan</p>
                                    </div>
                                </div>
                                
                                {{-- Tombol Detail / Edit Pemeriksaan (Aktifkan rute sesuai sistem Anda) --}}
                                {{-- <a href="{{ route('kader.pemeriksaan.show', $periksa->id) }}" class="px-4 py-2 bg-white text-indigo-600 border border-slate-200 text-xs font-bold rounded-xl hover:bg-indigo-50 hover:border-indigo-200 transition-colors shadow-sm text-center">
                                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                </a> --}}
                            </div>

                            {{-- Grid Hasil Pengukuran --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-center">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1"><i class="fas fa-weight text-indigo-400"></i> Berat Badan</p>
                                    <p class="text-sm font-black text-slate-700">{{ $periksa->berat_badan ?? $periksa->bb ?? '-' }} <span class="text-xs text-slate-400 font-bold">kg</span></p>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-center">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1"><i class="fas fa-ruler-vertical text-emerald-400"></i> Tinggi Badan</p>
                                    <p class="text-sm font-black text-slate-700">{{ $periksa->tinggi_badan ?? $periksa->tb ?? '-' }} <span class="text-xs text-slate-400 font-bold">cm</span></p>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-center">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1"><i class="fas fa-heart text-rose-400"></i> Tensi Darah</p>
                                    <p class="text-sm font-black text-rose-600">{{ $periksa->tekanan_darah ?? $periksa->tensi ?? '- / -' }} <span class="text-xs text-slate-400 font-bold">mmHg</span></p>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100 shadow-sm flex flex-col justify-center">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center gap-1"><i class="fas fa-child text-amber-400"></i> Lingkar Lengan</p>
                                    <p class="text-sm font-black text-slate-700">{{ $periksa->lila ?? $periksa->lingkar_lengan ?? '-' }} <span class="text-xs text-slate-400 font-bold">cm</span></p>
                                </div>
                            </div>
                            
                            {{-- Catatan Tambahan (Bila ada field catatan/keluhan di tabel pemeriksaan) --}}
                            @if(!empty($periksa->keluhan) || !empty($periksa->catatan))
                            <div class="mt-3 bg-amber-50/50 p-3 rounded-xl border border-amber-100">
                                <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1"><i class="fas fa-notes-medical"></i> Catatan Medis</p>
                                <p class="text-xs font-bold text-slate-600">{{ $periksa->keluhan ?? $periksa->catatan }}</p>
                            </div>
                            @endif

                        </div>
                    @empty
                        {{-- STATE JIKA BELUM ADA DATA PEMERIKSAAN SAMA SEKALI --}}
                        <div class="flex flex-col items-center justify-center py-16 text-center h-full">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-4xl shadow-inner border border-slate-100">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <h4 class="font-black text-slate-800 text-xl">Rekam Medis Kosong</h4>
                            <p class="text-sm font-medium text-slate-500 mt-2 max-w-sm mx-auto leading-relaxed">Saat ini remaja atas nama <strong>{{ $remaja->nama_lengkap }}</strong> belum memiliki catatan riwayat kesehatan di Posyandu.</p>
                            
                            {{-- Ganti URL ini dengan rute tambah pemeriksaan/absensi Anda --}}
                            <a href="#" class="mt-6 px-6 py-3 bg-indigo-600 text-white font-black text-sm rounded-xl hover:bg-indigo-700 shadow-sm hover:shadow-md transition-all inline-flex items-center gap-2 hover:-translate-y-0.5">
                                <i class="fas fa-plus"></i> Input Pemeriksaan Baru
                            </a>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Mematikan layar loading animasi setelah halaman ter-load sempurna
    window.onload = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { 
            l.classList.remove('opacity-100','pointer-events-auto'); 
            l.classList.add('opacity-0','pointer-events-none'); 
            setTimeout(()=> l.style.display = 'none', 300); 
        }
    };
</script>
@endsection