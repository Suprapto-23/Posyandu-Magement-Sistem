@extends('layouts.kader')

@section('title', 'Detail Absensi')
@section('page-name', 'Ringkasan Sesi')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
    .animate-slide-up-delay-1 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16,1,0.3,1) 0.1s forwards; }
    .animate-slide-up-delay-2 { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16,1,0.3,1) 0.2s forwards; }
    @keyframes slideUpFade { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
    
    .table-container { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    
    /* Modern Custom Select untuk Jump Navigation */
    .jump-select {
        appearance: none; 
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%234f46e5' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); 
        background-position: right 1rem center; background-repeat: no-repeat; background-size: 1.2em 1.2em;
    }

    /* CSS khusus Print (Cetak Laporan) */
    @media print {
        body * { visibility: hidden; }
        .print-area, .print-area * { visibility: visible; }
        .print-area { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
        .no-print { display: none !important; }
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto pb-10 print-area">

    {{-- TOP NAVIGATION BAR (CLEAN DOCK) --}}
    <div class="bg-white border border-slate-200 rounded-[24px] p-4 flex flex-col md:flex-row items-center justify-between gap-4 mb-8 shadow-sm animate-slide-up no-print">
        
        {{-- Tombol Kembali & Identitas Sesi --}}
        <div class="flex items-center gap-4 w-full md:w-auto">
            <a href="{{ route('kader.absensi.riwayat') }}" class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-500 hover:bg-slate-800 hover:text-white transition-all border border-slate-200 group shrink-0">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h1 class="text-xl font-black text-slate-900 tracking-tight font-poppins">Laporan Sesi</h1>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Kode: {{ $absensi->kode_absensi }}</p>
            </div>
        </div>

        {{-- QUICK JUMP NAVIGATOR (Solusi dari Saran Anda) --}}
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <div class="relative w-full sm:w-64">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-indigo-500 bg-indigo-50 w-6 h-6 rounded-md flex items-center justify-center pointer-events-none">
                    <i class="far fa-calendar-alt text-xs"></i>
                </div>
                <select onchange="lompatKeSesi(this.value)" class="jump-select w-full bg-slate-50 border border-slate-200 text-slate-700 text-[12px] font-black rounded-xl pl-12 pr-10 py-3 outline-none hover:border-indigo-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all cursor-pointer">
                    <option value="" disabled>-- Lompat ke Sesi Lain --</option>
                    @foreach($semuaSesi as $sesi)
                        <option value="{{ route('kader.absensi.show', $sesi->id) }}" {{ $sesi->id == $absensi->id ? 'selected' : '' }}>
                            Sesi Ke-{{ $sesi->nomor_pertemuan }} ({{ $sesi->tanggal_posyandu->translatedFormat('d M Y') }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button onclick="window.print()" class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-3 bg-white border border-slate-200 text-slate-700 font-black text-[11px] rounded-xl hover:bg-slate-50 shadow-sm transition-all uppercase tracking-widest">
                <i class="fas fa-print text-indigo-500"></i> Cetak
            </button>
        </div>
    </div>

    {{-- HEADER KERTAS (UNTUK PRINT & DISPLAY) --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 mb-6 animate-slide-up-delay-1 px-2">
        <div>
            <h2 class="text-3xl font-black text-slate-800 font-poppins">Rekap Kehadiran</h2>
            <div class="flex items-center gap-3 mt-2">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 font-black text-[10px] uppercase tracking-widest rounded border border-indigo-100 shadow-sm">
                    {{ $absensi->kategori_label }}
                </span>
                <span class="text-[13px] font-bold text-slate-500">
                    {{ $absensi->tanggal_posyandu->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- KARTU STATISTIK (CLEAN & MODERN) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 animate-slide-up-delay-1">
        <div class="bg-white rounded-[20px] border border-slate-200 p-5 relative overflow-hidden flex flex-col justify-center shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Peserta</p>
            <p class="text-3xl font-black text-slate-800 font-poppins">{{ $totalPasien }}</p>
        </div>

        <div class="bg-emerald-50/50 rounded-[20px] border border-emerald-100 p-5 relative overflow-hidden flex flex-col justify-center shadow-sm">
            <p class="text-[10px] font-black text-emerald-600/70 uppercase tracking-widest mb-1">Hadir (Sukses)</p>
            <p class="text-3xl font-black text-emerald-600 font-poppins">{{ $totalHadir }}</p>
        </div>

        <div class="bg-rose-50/50 rounded-[20px] border border-rose-100 p-5 relative overflow-hidden flex flex-col justify-center shadow-sm">
            <p class="text-[10px] font-black text-rose-600/70 uppercase tracking-widest mb-1">Absen (Tertunda)</p>
            <p class="text-3xl font-black text-rose-600 font-poppins">{{ $totalAbsen }}</p>
        </div>

        <div class="bg-indigo-600 rounded-[20px] border border-indigo-700 p-5 relative overflow-hidden flex flex-col justify-center shadow-[0_10px_20px_rgba(79,70,229,0.2)]">
            @php $pct = $totalPasien > 0 ? round($totalHadir/$totalPasien*100) : 0; @endphp
            <div class="flex items-end justify-between mb-2 relative z-10">
                <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest">Partisipasi</p>
                <p class="text-2xl font-black text-white leading-none">{{ $pct }}%</p>
            </div>
            <div class="w-full h-1.5 bg-indigo-900/50 rounded-full overflow-hidden relative z-10">
                <div class="h-full bg-white rounded-full" style="width:{{ $pct }}%"></div>
            </div>
        </div>
    </div>

    {{-- TABEL RINCIAN (VERY CLEAN DESIGN MIRIP REFERENSI GAMBAR ANDA) --}}
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden animate-slide-up-delay-2 mb-10">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800 text-[15px] font-poppins flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-200 text-slate-400 flex items-center justify-center text-sm"><i class="fas fa-clipboard-list"></i></div>
                Rincian Data Kehadiran
            </h3>
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 border border-slate-200 px-3 py-1.5 rounded-md">Dicatat: {{ strtoupper($absensi->pencatat?->name ?? 'Sistem') }}</span>
        </div>

        <div class="table-container overflow-x-auto max-h-[65vh]">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[700px]">
                <thead class="sticky top-0 bg-white z-10 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center w-12 border-r border-slate-100/50">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-r border-slate-100/50">Identitas Warga</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center border-r border-slate-100/50">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Catatan Alasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    {{-- Urutkan yang absen di atas agar mudah ditinjau --}}
                    @foreach($details->sortByDesc('hadir') as $index => $detail)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ $detail->hadir ? '' : 'bg-rose-50/10' }}">
                        <td class="px-6 py-4 text-center text-[13px] font-black text-slate-400 border-r border-slate-100/50">
                            {{ $index + 1 }}
                        </td>
                        
                        <td class="px-6 py-4 border-r border-slate-100/50">
                            <div class="flex items-center gap-4">
                                @php
                                    $initial = strtoupper(substr($detail->pasien_data?->nama_lengkap ?? '?', 0, 1));
                                    $avatarClass = $detail->hadir ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100';
                                @endphp
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-[15px] shrink-0 border {{ $avatarClass }}">
                                    {{ $initial }}
                                </div>
                                
                                <div>
                                    <p class="font-black text-slate-800 text-[14px]">{{ $detail->pasien_data?->nama_lengkap ?? '— Data Dihapus Sistem —' }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 font-mono mt-0.5"><i class="fas fa-id-card mr-1 text-slate-300"></i> NIK: {{ $detail->pasien_data?->nik ?? 'Tdk Tersedia' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center border-r border-slate-100/50">
                            @if($detail->hadir)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white text-emerald-600 border border-emerald-200 rounded-md">
                                    <i class="fas fa-check text-[10px]"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Hadir</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-500 border border-rose-200 rounded-md">
                                    <i class="fas fa-times text-[10px]"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Absen</span>
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if($detail->keterangan)
                                <span class="text-[11px] font-bold text-slate-600 italic">
                                    {{ $detail->keterangan }}
                                </span>
                            @else
                                <span class="text-[11px] font-medium text-slate-300 italic">Tidak ada catatan medis/alasan.</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Fungsi Lompat Sesi (Quick Jump) dengan efek loading visual
    function lompatKeSesi(url) {
        document.body.style.opacity = '0.5';
        document.body.style.pointerEvents = 'none';
        window.location.href = url;
    }
</script>
@endpush
@endsection