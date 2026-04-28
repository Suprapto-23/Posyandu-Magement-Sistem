@extends('layouts.kader')
@section('title', 'Detail Sesi Absensi')
@section('page-name', 'Arsip Sesi #' . $absensi->nomor_pertemuan)

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* Layout Sidebar & Content */
    .show-container { display: grid; grid-template-columns: 300px 1fr; gap: 2rem; }
    @media (max-width: 1024px) { .show-container { grid-template-columns: 1fr; } }

    /* Kartu Statistik Tegas */
    .stats-card-solid {
        background: #ffffff; border-radius: 20px; padding: 1.5rem;
        border: 2px solid #e2e8f0; display: flex; flex-direction: column; justify-content: center;
        transition: all 0.2s ease;
    }
    .stats-card-solid:hover { border-color: #cbd5e1; transform: translateY(-3px); box-shadow: 0 10px 20px -5px rgba(0,0,0,0.05); }

    /* Scrollbar minimalis untuk sidebar */
    .custom-scroll::-webkit-scrollbar { width: 6px; }
    .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-slide-up pb-12">

    {{-- TOP BAR & HEADER (TEGAS & BERSIH) --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-8 border-b border-slate-200 pb-6 mt-2">
        <div class="flex items-center gap-5">
            <a href="{{ route('kader.absensi.riwayat') }}" class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-200 transition-colors" title="Kembali ke Riwayat">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="px-2.5 py-1 rounded bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest shadow-sm">
                        {{ str_replace('_', ' ', $absensi->kategori) }}
                    </span>
                    <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($absensi->tanggal_posyandu)->translatedFormat('d F Y') }}</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">{{ $absensi->kode_absensi }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            {{-- TOMBOL KOREKSI (Satu-satunya aksi utama, Cetak dihapus) --}}
            <a href="{{ route('kader.absensi.index', ['kategori' => $absensi->kategori]) }}" class="w-full md:w-auto px-8 py-4 bg-amber-500 text-white font-black text-[12px] uppercase tracking-widest rounded-xl hover:bg-amber-600 transition-colors shadow-[0_8px_15px_-5px_rgba(245,158,11,0.4)] flex items-center justify-center gap-2">
                <i class="fas fa-edit"></i> Koreksi / Edit Presensi
            </a>
        </div>
    </div>

    <div class="show-container">
        
        {{-- LEFT SIDEBAR: NAVIGASI SESI LAIN --}}
        <aside class="flex flex-col gap-6">
            
            {{-- Box Statistik Persentase Tegas --}}
            <div class="bg-slate-900 rounded-[24px] p-8 text-white shadow-lg border border-slate-700">
                <h4 class="font-black text-[11px] text-slate-400 uppercase tracking-widest mb-1">Tingkat Kehadiran</h4>
                <div class="flex items-baseline gap-2 mb-4">
                    <span class="text-5xl font-black text-white">{{ $totalPasien > 0 ? round(($totalHadir / $totalPasien) * 100) : 0 }}</span>
                    <span class="text-xl font-bold text-slate-400">%</span>
                </div>
                <div class="w-full h-3 bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $totalPasien > 0 ? ($totalHadir / $totalPasien) * 100 : 0 }}%"></div>
                </div>
                <p class="text-slate-400 text-[11px] mt-4 leading-relaxed font-medium">Persentase ini dihitung dari rasio jumlah warga yang hadir berbanding total sasaran.</p>
            </div>

            {{-- List Sesi Lainnya --}}
            <div class="bg-white border-2 border-slate-200 rounded-[24px] p-6">
                <h3 class="font-black text-slate-800 text-[12px] uppercase tracking-widest mb-5 flex items-center gap-2">
                    <i class="fas fa-history text-slate-400"></i> Riwayat Sesi {{ ucfirst(str_replace('_', ' ', $absensi->kategori)) }}
                </h3>
                <div class="space-y-2 max-h-[400px] overflow-y-auto pr-2 custom-scroll">
                    @foreach($semuaSesi as $sesi)
                        <a href="{{ route('kader.absensi.show', $sesi->id) }}" 
                           class="flex items-center gap-4 p-3 rounded-xl border-2 transition-colors 
                           {{ $sesi->id == $absensi->id ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'bg-white border-transparent text-slate-500 hover:border-slate-200 hover:bg-slate-50' }}">
                            <div class="w-12 h-12 rounded-lg flex flex-col items-center justify-center shrink-0 {{ $sesi->id == $absensi->id ? 'bg-indigo-500 text-white' : 'bg-slate-100 text-slate-400' }}">
                                <span class="text-[8px] font-black uppercase tracking-widest leading-none mb-1">Sesi</span>
                                <span class="text-base font-black leading-none">{{ $sesi->nomor_pertemuan }}</span>
                            </div>
                            <div>
                                <p class="text-[13px] font-black leading-tight">{{ \Carbon\Carbon::parse($sesi->tanggal_posyandu)->translatedFormat('d M Y') }}</p>
                                <p class="text-[10px] font-bold opacity-70 uppercase mt-0.5">{{ $sesi->kode_absensi }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT: STATS & MANIFEST --}}
        <main class="flex flex-col gap-6">
            
            {{-- Statistik Angka (Solid & Kuat) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="stats-card-solid border-t-4 border-t-slate-400">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center justify-between">
                        Total Sasaran <i class="fas fa-users text-slate-300 text-lg"></i>
                    </p>
                    <h3 class="text-4xl font-black text-slate-800">{{ $totalPasien }}</h3>
                </div>
                <div class="stats-card-solid border-t-4 border-t-emerald-500">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center justify-between">
                        Total Hadir <i class="fas fa-user-check text-emerald-200 text-lg"></i>
                    </p>
                    <h3 class="text-4xl font-black text-emerald-600">{{ $totalHadir }}</h3>
                </div>
                <div class="stats-card-solid border-t-4 border-t-rose-500">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-1 flex items-center justify-between">
                        Total Absen <i class="fas fa-user-times text-rose-200 text-lg"></i>
                    </p>
                    <h3 class="text-4xl font-black text-rose-600">{{ $totalAbsen }}</h3>
                </div>
            </div>

            {{-- Tabel Manifest (Desain Tegas) --}}
            <div class="bg-white rounded-[24px] border-2 border-slate-200 overflow-hidden mt-2">
                
                {{-- Pencarian --}}
                <div class="px-6 py-5 bg-slate-50 border-b-2 border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <h3 class="font-black text-slate-800 text-[13px] uppercase tracking-widest">
                        Data Kehadiran Spesifik
                    </h3>
                    <div class="relative w-full sm:w-72">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" id="manifestSearch" placeholder="Cari warga / pasien..." class="w-full bg-white border border-slate-300 text-sm font-bold rounded-lg pl-10 pr-4 py-2.5 outline-none focus:border-indigo-500 transition-colors">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-100">
                                <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest border-b-2 border-slate-200 w-16">No</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest border-b-2 border-slate-200">Identitas Warga</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black text-slate-500 uppercase tracking-widest border-b-2 border-slate-200 w-36">Status</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-widest border-b-2 border-slate-200 w-1/3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="manifestTableBody">
                            @foreach($details as $index => $row)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-black text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <p class="manifest-nama font-black text-slate-800 text-[14px]">{{ $row->pasien_data->nama_lengkap ?? 'Data Terhapus' }}</p>
                                    <p class="text-[10px] font-black text-slate-400 tracking-widest uppercase mt-0.5">NIK. {{ $row->pasien_data->nik ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- OUTPUT TEGAS: Menggunakan warna Solid (Block Color) --}}
                                    @if($row->hadir)
                                        <div class="inline-flex w-full justify-center py-2 rounded border border-emerald-600 bg-emerald-500 text-white text-[11px] font-black uppercase tracking-widest shadow-sm">
                                            Hadir
                                        </div>
                                    @else
                                        <div class="inline-flex w-full justify-center py-2 rounded border border-rose-600 bg-rose-500 text-white text-[11px] font-black uppercase tracking-widest shadow-sm">
                                            Absen
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[13px] font-bold text-slate-600">{{ $row->keterangan ?? '-' }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer Log Info --}}
                <div class="px-6 py-4 bg-slate-50 border-t-2 border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Pencatat: <span class="text-slate-700">{{ $absensi->pencatat->name ?? 'Sistem' }}</span>
                    </p>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Waktu Rekam: <span class="text-slate-700">{{ $absensi->created_at->translatedFormat('d M Y, H:i:s') }} WIB</span>
                    </p>
                </div>
            </div>

        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fitur Pencarian Manifest Instan
    document.getElementById('manifestSearch')?.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#manifestTableBody tr');
        rows.forEach(row => {
            const nama = row.querySelector('.manifest-nama').textContent.toLowerCase();
            row.style.display = nama.includes(filter) ? '' : 'none';
        });
    });
</script>
@endpush