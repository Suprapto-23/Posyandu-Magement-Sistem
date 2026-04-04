@extends('layouts.bidan')
@section('title', 'Command Center Klinis')
@section('page-name', 'Dashboard Klinis')

@section('content')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(25px); } to { opacity: 1; transform: translateY(0); } }
    
    .klinik-card { 
        background: #ffffff; 
        border: 1px solid #e2e8f0; 
        border-radius: 24px; 
        box-shadow: 0 4px 20px -10px rgba(0,0,0,0.03); 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    }
    .klinik-card:hover { 
        box-shadow: 0 25px 50px -12px rgba(6, 182, 212, 0.15); 
        border-color: rgba(6, 182, 212, 0.3); 
        transform: translateY(-4px); 
    }

    .pulse-alert { animation: pulseRed 2s infinite; }
    @keyframes pulseRed { 0%, 100% { box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.4) } 50% { box-shadow: 0 0 0 6px rgba(244, 63, 94, 0) } }
</style>

<div class="max-w-[1500px] mx-auto relative pb-8">
    
    {{-- 1. HEADER BANNER (Medical Clean) --}}
    <div class="bg-white rounded-[32px] p-8 md:p-10 mb-8 border border-slate-200 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.04)] flex flex-col lg:flex-row items-center justify-between gap-8 animate-slide-up relative overflow-hidden">
        
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-cyan-500/10 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="relative z-10 w-full lg:w-auto flex-1 text-center lg:text-left">
            <h1 class="text-3xl md:text-[38px] font-black text-slate-900 tracking-tight font-poppins mb-2">
                Halo Bidan <span class="text-cyan-600">{{ Str::words(Auth::user()->name ?? 'Hebat', 1, '') }}</span> 👋
            </h1>
            <p class="text-slate-500 font-medium text-[14px] md:text-[15px] max-w-2xl leading-relaxed mb-6 mx-auto lg:mx-0">
                Pusat validasi data klinis Posyandu. Pantau antrian meja 5 dan analisis grafik kesehatan warga secara akurat.
            </p>

            <div class="flex flex-wrap justify-center lg:justify-start items-center gap-3">
                <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-[14px] bg-slate-50 border border-slate-100 text-slate-700 text-[13px] font-bold">
                    <i class="far fa-calendar-alt text-cyan-500"></i> {{ now()->translatedFormat('l, d F Y') }}
                </div>
                <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-[14px] bg-slate-50 border border-slate-100 text-slate-700 text-[13px] font-bold">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div> DB Tersinkronisasi
                </div>
            </div>
        </div>

        <div class="hidden lg:flex w-40 h-40 rounded-[24px] bg-cyan-50 border border-cyan-100 flex-col items-center justify-center shrink-0 shadow-inner relative z-10">
            <i class="fas fa-user-md text-6xl text-cyan-500 mb-2"></i>
            <span class="text-[10px] font-black uppercase tracking-widest text-cyan-700">Verifikator</span>
        </div>
    </div>

    {{-- 2. METRIC CARDS (Triase & Beban Kerja) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8 animate-slide-up" style="animation-delay: 0.1s;">
        
        {{-- Antrian Pending (Penting!) --}}
        <div class="klinik-card p-6 relative overflow-hidden group flex flex-col justify-between border-rose-200/60 bg-rose-50/30">
            <div class="flex justify-between items-start mb-5">
                <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl border border-rose-200 shrink-0 {{ $stats['menunggu_validasi'] > 0 ? 'pulse-alert' : '' }}">
                    <i class="fas fa-procedures"></i>
                </div>
                @if($stats['menunggu_validasi'] > 0)
                <span class="bg-rose-500 text-white px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest animate-pulse">Perlu Aksi</span>
                @endif
            </div>
            <div>
                <h3 class="text-4xl font-black text-rose-600 font-poppins leading-none mb-1.5">{{ $stats['menunggu_validasi'] }}</h3>
                <p class="text-[12px] font-bold text-slate-500 uppercase tracking-widest">Menunggu Validasi</p>
            </div>
        </div>

        {{-- Selesai Hari Ini --}}
        <div class="klinik-card p-6 relative flex flex-col justify-between">
            <div class="flex justify-between items-start mb-5">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl border border-emerald-100 shrink-0">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
            <div>
                <h3 class="text-4xl font-black text-slate-800 font-poppins leading-none mb-1.5">{{ $stats['selesai_divalidasi'] }}</h3>
                <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest">Selesai Hari Ini</p>
            </div>
        </div>

        {{-- Total Database --}}
        <div class="klinik-card p-6 relative flex flex-col justify-between">
            <div class="flex justify-between items-start mb-5">
                <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl border border-cyan-100 shrink-0">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div>
                <h3 class="text-4xl font-black text-slate-800 font-poppins leading-none mb-1.5">{{ $stats['total_pasien'] }}</h3>
                <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest">Total Pasien Terdaftar</p>
            </div>
        </div>

        {{-- Jadwal Aktif --}}
        <div class="klinik-card p-6 relative flex flex-col justify-between">
            <div class="flex justify-between items-start mb-5">
                <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-500 flex items-center justify-center text-xl border border-violet-100 shrink-0">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div>
                <h3 class="text-4xl font-black text-slate-800 font-poppins leading-none mb-1.5">{{ $stats['jadwal_hari_ini'] }}</h3>
                <p class="text-[12px] font-bold text-slate-400 uppercase tracking-widest">Jadwal Aktif Hari Ini</p>
            </div>
        </div>

    </div>

    {{-- 3. MAIN SECTION: Antrian & Chart --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-8 animate-slide-up" style="animation-delay: 0.2s;">
        
        {{-- DAFTAR ANTRIAN LIVE (Sangat Penting untuk Bidan) --}}
        <div class="lg:col-span-2 klinik-card p-6 sm:p-8 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-[18px] font-black text-slate-800 font-poppins leading-none">Antrian Validasi Medis (Meja 5)</h3>
                    <p class="text-[12px] font-medium text-slate-400 mt-1.5">Data terinput oleh Kader, menunggu verifikasi Anda.</p>
                </div>
                <a href="{{ route('bidan.pemeriksaan.index') }}" class="px-4 py-2 bg-cyan-50 hover:bg-cyan-100 text-cyan-700 text-[11px] font-black uppercase tracking-widest rounded-xl transition-colors">Lihat Semua</a>
            </div>

            <div class="flex-1 overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[500px]">
                    <thead class="bg-slate-50/80 border-y border-slate-100">
                        <tr>
                            <th class="py-3 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Warga / Kategori</th>
                            <th class="py-3 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Fisik Dasar (Kader)</th>
                            <th class="py-3 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Input</th>
                            <th class="py-3 px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($antrianLive as $antri)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3 px-4">
                                @php
                                    $nama = $antri->balita->nama_lengkap ?? $antri->remaja->nama_lengkap ?? $antri->lansia->nama_lengkap ?? 'Ibu Hamil';
                                @endphp
                                <p class="text-[13px] font-bold text-slate-800 truncate max-w-[150px]">{{ $nama }}</p>
                                <span class="text-[10px] font-black text-cyan-600 uppercase tracking-wider">{{ $antri->kategori_pasien }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-block px-2 py-1 bg-white border border-slate-200 text-slate-600 text-[11px] font-bold rounded-md shadow-sm">
                                    BB: {{ $antri->berat_badan ?? '-' }}kg | TB: {{ $antri->tinggi_badan ?? '-' }}cm
                                </span>
                            </td>
                            <td class="py-3 px-4 text-[12px] font-medium text-slate-500">
                                {{ $antri->created_at->diffForHumans() }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                {{-- Tombol Verifikasi yang akan memicu halaman Validasi (Nanti kita buat) --}}
                                <a href="#" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-500 text-rose-600 hover:text-white border border-rose-200 hover:border-rose-500 text-[11px] font-bold rounded-lg transition-all">
                                    <i class="fas fa-stethoscope"></i> Periksa
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="inline-flex w-12 h-12 bg-slate-50 border border-slate-100 rounded-full items-center justify-center text-slate-300 mb-3"><i class="fas fa-check-double text-xl"></i></div>
                                <p class="text-[13px] font-bold text-slate-500">Tidak ada antrian validasi saat ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ALERT RISIKO & GRAFIK KECIL --}}
        <div class="flex flex-col gap-6">
            
            {{-- Alert Risiko --}}
            <div class="klinik-card p-6 border-l-4 border-l-amber-400 bg-amber-50/30">
                <h3 class="text-[15px] font-black text-slate-800 font-poppins mb-4 flex items-center gap-2"><i class="fas fa-exclamation-triangle text-amber-500"></i> Pantauan Risiko Tinggi</h3>
                
                <div class="space-y-3">
                    <div class="bg-white p-3 rounded-xl border border-slate-100 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-500 flex items-center justify-center"><i class="fas fa-baby"></i></div>
                            <span class="text-[12px] font-bold text-slate-700">Balita Stunting</span>
                        </div>
                        <span class="text-[16px] font-black text-rose-600">{{ $alertRisiko['balita_stunting'] }}</span>
                    </div>
                    <div class="bg-white p-3 rounded-xl border border-slate-100 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-500 flex items-center justify-center"><i class="fas fa-wheelchair"></i></div>
                            <span class="text-[12px] font-bold text-slate-700">Lansia Hipertensi</span>
                        </div>
                        <span class="text-[16px] font-black text-sky-600">{{ $alertRisiko['lansia_hipertensi'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Line Chart Validasi --}}
            <div class="klinik-card p-6 flex-1 flex flex-col">
                <h3 class="text-[15px] font-black text-slate-800 font-poppins mb-1">Tren Layanan Klinis</h3>
                <p class="text-[11px] font-medium text-slate-400 mb-4">Grafik 7 hari pelayanan</p>
                <div class="relative w-full flex-1" style="min-h: 150px;">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    const formattedDates = {!! json_encode($chartLabels ?? []) !!};
    const rawData = {!! json_encode($chartData ?? []) !!};

    const lc = document.getElementById('lineChart');
    if(lc && formattedDates.length > 0) {
        const ctx = lc.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(6, 182, 212, 0.3)'); 
        gradient.addColorStop(1, 'rgba(6, 182, 212, 0.0)'); 

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: formattedDates,
                datasets: [{
                    label: 'Pasien Divalidasi', data: rawData, borderColor: '#06b6d4', backgroundColor: gradient,
                    borderWidth: 3, fill: true, tension: 0.4, pointBackgroundColor: '#ffffff', pointBorderColor: '#06b6d4', pointBorderWidth: 2, pointRadius: 0, pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, 
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a', padding: 10, cornerRadius: 8, titleFont: {family: "'Inter', sans-serif", size: 10}, bodyFont: {family: "'Inter', sans-serif", size: 12, weight: 'bold'}, displayColors: false } },
                scales: { y: { beginAtZero: true, border: { display: false }, grid: { color: '#f1f5f9', drawTicks: false }, ticks: { stepSize: 1, color: '#94a3b8', font: {size: 9} } }, x: { border: { display: false }, grid: { display: false }, ticks: { color: '#94a3b8', font: {size: 9} } } },
                interaction: { mode: 'index', intersect: false }
            }
        });
    }
});
</script>
@endpush