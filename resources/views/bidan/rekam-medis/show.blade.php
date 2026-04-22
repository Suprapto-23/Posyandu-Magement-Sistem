@extends('layouts.bidan')

@section('title', 'Electronic Medical Record')
@section('page-name', 'Detail Rekam Medis')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.4s ease-out forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
@endpush

@section('content')
@php
    $nama = $pasien->nama_lengkap ?? 'Pasien Anonim';
    $nik = $pasien->nik ?? '-';
    $tglLahir = $pasien->tanggal_lahir ? \Carbon\Carbon::parse($pasien->tanggal_lahir) : null;
    $umur = $tglLahir ? $tglLahir->age : 0;
    
    $themeColor = match($pasien_type) {
        'balita' => 'cyan',
        'ibu_hamil' => 'pink',
        'remaja' => 'indigo',
        default => 'emerald'
    };
@endphp

<div class="max-w-6xl mx-auto space-y-6 animate-slide-up pb-10">

    {{-- 1. NAVIGASI ATAS (Tombol Cetak Dihapus Sesuai Konsep RAD Laporan Terpusat) --}}
    <div class="flex items-center justify-between mb-2">
        <a href="{{ route('bidan.rekam-medis.index', ['type' => $pasien_type]) }}" class="inline-flex items-center gap-2 text-[12px] font-black text-slate-400 hover:text-cyan-600 transition-colors uppercase tracking-widest">
            <i class="fas fa-arrow-left"></i> Kembali ke Buku Induk
        </a>
    </div>

    {{-- 2. HEADER KARTU IDENTITAS --}}
    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden relative">
        <div class="absolute right-0 top-0 w-48 h-48 bg-{{ $themeColor }}-50 rounded-full blur-3xl opacity-50 -translate-y-1/2 translate-x-1/2"></div>
        
        <div class="p-6 md:p-8 flex flex-col md:flex-row items-center gap-6 relative z-10">
            <div class="w-24 h-24 rounded-[20px] bg-gradient-to-br from-{{ $themeColor }}-500 to-{{ $themeColor }}-600 text-white flex items-center justify-center text-4xl shadow-lg border-4 border-white shrink-0">
                <i class="fas fa-{{ $pasien_type == 'balita' ? 'baby' : ($pasien_type == 'ibu_hamil' ? 'female' : 'user') }}"></i>
            </div>
            
            <div class="flex-1 text-center md:text-left">
                <div class="flex flex-col md:flex-row md:items-center gap-2 mb-2 justify-center md:justify-start">
                    <h1 class="text-2xl font-black text-slate-800 font-poppins">{{ $nama }}</h1>
                    <span class="px-2.5 py-0.5 bg-{{ $themeColor }}-50 text-{{ $themeColor }}-600 text-[10px] font-black uppercase tracking-widest rounded-md border border-{{ $themeColor }}-100">{{ str_replace('_', ' ', $pasien_type) }}</span>
                </div>
                <div class="flex flex-wrap justify-center md:justify-start gap-x-6 gap-y-2 text-slate-500 text-[12px] font-bold">
                    <span><i class="fas fa-fingerprint mr-1.5 text-slate-300"></i> NIK: {{ $nik }}</span>
                    <span><i class="fas fa-birthday-cake mr-1.5 text-slate-300"></i> {{ $tglLahir ? $tglLahir->translatedFormat('d M Y') : '-' }} ({{ $umur }} Thn)</span>
                    <span class="hidden sm:inline"><i class="fas fa-map-marker-alt mr-1.5 text-slate-300"></i> {{ $pasien->alamat ?? 'Alamat tidak tercatat' }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 shrink-0">
                <div class="bg-slate-900 text-white p-4 rounded-2xl text-center min-w-[110px] shadow-md">
                    <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-1">Total Periksa</p>
                    <p class="text-xl font-black">{{ $riwayatMedis->count() }} <span class="text-[10px] font-medium opacity-60">Kali</span></p>
                </div>
                <div class="bg-cyan-600 text-white p-4 rounded-2xl text-center min-w-[110px] shadow-md">
                    <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-1">Imunisasi</p>
                    <p class="text-xl font-black">{{ $riwayatImunisasi->count() }} <span class="text-[10px] font-medium opacity-60">Log</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. ANALITIK: GRAFIK & LOG VAKSIN --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- Grafik Tren Medis --}}
        <div class="lg:col-span-8 bg-white rounded-[24px] p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-[14px] font-black text-slate-800 flex items-center gap-2">
                    <i class="fas fa-chart-line text-cyan-500"></i> Grafik Pertumbuhan
                </h3>
                <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">7 Sesi Terakhir</span>
            </div>
            
            {{-- Fallback jika belum ada data pemeriksaan --}}
            @if($riwayatMedis->isEmpty())
                <div class="h-[280px] flex flex-col items-center justify-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                    <i class="fas fa-chart-area text-4xl mb-3 opacity-50"></i>
                    <p class="text-[12px] font-bold">Grafik belum tersedia.</p>
                    <p class="text-[10px] font-medium">Lakukan pemeriksaan medis (Meja 5) terlebih dahulu.</p>
                </div>
            @else
                <div class="h-[280px]">
                    <canvas id="emrChart"></canvas>
                </div>
            @endif
        </div>

        {{-- Log Imunisasi --}}
        <div class="lg:col-span-4 bg-white rounded-[24px] p-6 border border-slate-100 shadow-sm flex flex-col">
            <h3 class="text-[14px] font-black text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-syringe text-teal-500"></i> Riwayat Imunisasi
            </h3>
            <div class="space-y-3 flex-1 overflow-y-auto pr-1 custom-scrollbar max-h-[280px]">
                @forelse($riwayatImunisasi as $imu)
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-center group hover:border-teal-200 transition-colors">
                    <div>
                        <p class="text-[12px] font-black text-slate-800">{{ $imu->vaksin }}</p>
                        <p class="text-[10px] font-bold text-slate-400 italic">{{ \Carbon\Carbon::parse($imu->tanggal_imunisasi)->translatedFormat('d M Y') }}</p>
                    </div>
                    <span class="text-[9px] font-black px-2 py-1 bg-white border border-slate-200 text-teal-600 rounded-lg uppercase shadow-sm">{{ $imu->dosis }}</span>
                </div>
                @empty
                <div class="h-full flex flex-col items-center justify-center text-center py-10 opacity-40">
                    <i class="fas fa-shield-virus text-3xl mb-2"></i>
                    <p class="text-[11px] font-bold">Belum ada riwayat vaksinasi.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 4. TIMELINE PEMERIKSAAN --}}
    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-[14px] font-black text-slate-800 font-poppins">Timeline Pemeriksaan Klinis</h3>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Diurutkan dari Terbaru</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white text-[10px] font-black text-slate-400 uppercase tracking-widest border-b">
                        <th class="py-4 px-8">Waktu & Tanggal</th>
                        <th class="py-4 px-8">Metrik Fisik</th>
                        <th class="py-4 px-8">Diagnosa & Tindakan</th>
                        <th class="py-4 px-8 text-right">Verifikator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($riwayatMedis as $med)
                    <tr class="hover:bg-slate-50/50 transition-all text-[13px] group">
                        <td class="py-5 px-8">
                            <p class="font-black text-slate-800">{{ \Carbon\Carbon::parse($med->tanggal_periksa)->translatedFormat('d F Y') }}</p>
                            <p class="text-[11px] font-bold text-slate-400 mt-0.5">EMR ID: #{{ $med->id }}</p>
                        </td>
                        <td class="py-5 px-8">
                            <div class="flex items-center gap-3">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Berat</span>
                                    <span class="font-bold text-slate-700">{{ $med->berat_badan ?? 0 }} kg</span>
                                </div>
                                <div class="w-px h-6 bg-slate-200"></div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">Tinggi</span>
                                    <span class="font-bold text-slate-700">{{ $med->tinggi_badan ?? 0 }} cm</span>
                                </div>
                                <div class="w-px h-6 bg-slate-200"></div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">IMT</span>
                                    <span class="font-black text-cyan-600">{{ $med->imt ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-8">
                            <p class="font-bold text-slate-800 mb-1 line-clamp-1">{{ $med->diagnosa ?: 'Tidak ada diagnosa spesifik' }}</p>
                            <p class="text-[11px] text-slate-500 leading-relaxed italic line-clamp-2">{{ $med->tindakan ?: 'Hanya pemeriksaan rutin.' }}</p>
                        </td>
                        <td class="py-5 px-8 text-right">
                            <div class="flex flex-col items-end">
                                <span class="text-[11px] font-black text-slate-800">{{ Str::words($med->verifikator->name ?? 'Bidan Desa', 1, '') }}</span>
                                <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest mt-1"><i class="fas fa-check-double text-[8px]"></i> Verified</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-24 text-center">
                            <i class="fas fa-clipboard-list text-5xl text-slate-100 mb-4"></i>
                            <h3 class="text-slate-400 font-bold">Belum ada riwayat medis.</h3>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = {!! json_encode($chartData) !!};
    
    // Hanya inisialisasi grafik jika data tersedia
    if(chartData && chartData.length > 0) {
        const ctx = document.getElementById('emrChart').getContext('2d');
        
        const labels = chartData.map(item => {
            const date = new Date(item.tanggal_periksa);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });
        
        // Proteksi nilai null menjadi 0
        const bbData = chartData.map(item => item.berat_badan || 0);
        const tbData = chartData.map(item => item.tinggi_badan || 0);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Berat Badan (kg)',
                        data: bbData,
                        borderColor: '#06b6d4',
                        backgroundColor: 'rgba(6, 182, 212, 0.05)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#06b6d4',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Tinggi Badan (cm)',
                        data: tbData,
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.4,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 20, font: { size: 11, weight: 'bold', family: 'Poppins' } }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: false,
                        grid: { color: '#f8fafc' },
                        ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' }, color: '#94a3b8' }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection