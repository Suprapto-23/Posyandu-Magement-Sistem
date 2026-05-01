@extends('layouts.kader')
@section('title', 'Detail Sesi Absensi')
@section('page-name', 'Arsip Sesi #' . $absensi->nomor_pertemuan)

@push('styles')
<style>
    /* =================================================================
       NEXUS SAAS DESIGN SYSTEM (SYMMETRICAL DETAIL PAGE)
       ================================================================= */
    
    /* Animasi Masuk Halus */
    .animate-fade-in { opacity: 0; animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

    /* Input Pencarian Kapsul */
    .search-input {
        background-color: #f8fafc; border: 1px solid #e2e8f0; color: #334155; font-size: 0.85rem; font-weight: 600;
        border-radius: 12px; padding: 0.65rem 1rem 0.65rem 2.5rem; width: 100%; outline: none; transition: all 0.2s ease;
    }
    .search-input:focus { background-color: #ffffff; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }

    /* Tabel Nexus Presisi */
    .nexus-table { width: 100%; border-collapse: separate; border-spacing: 0; text-align: left; }
    .nexus-table th { background: #f8fafc; color: #64748b; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-y: 1px solid #e2e8f0; white-space: nowrap; position: sticky; top: 0; z-index: 10; }
    .nexus-table td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s; }
    .nexus-table tr:last-child td { border-bottom: none; }
    .nexus-table tr:hover td { background-color: #f8fafc; }

    /* Scrollbar Estetik & Rapi */
    .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endpush

@section('content')
<div class="max-w-[1150px] mx-auto animate-fade-in pb-16 mt-2 relative z-10">

    {{-- AURA BACKGROUND --}}
    <div class="fixed top-0 right-0 w-[400px] h-[400px] bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 left-0 w-[300px] h-[300px] bg-violet-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>

    {{-- ========================================================
         1. HEADER (RAPI & MENYATU)
         ======================================================== --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 bg-white/60 backdrop-blur-xl p-6 rounded-[28px] border border-white shadow-sm relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-50 rounded-bl-full blur-2xl pointer-events-none z-0"></div>

        <div class="flex items-center gap-5 relative z-10 w-full md:w-auto">
            <a href="{{ route('kader.absensi.riwayat') }}" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm shrink-0" title="Kembali ke Arsip">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                    <span class="px-2.5 py-0.5 rounded-md bg-indigo-100 text-indigo-700 text-[9px] font-bold uppercase tracking-widest border border-indigo-200">
                        {{ str_replace('_', ' ', $absensi->kategori) }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($absensi->tanggal_posyandu)->translatedFormat('d F Y') }}</span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight font-poppins truncate">{{ $absensi->kode_absensi }}</h1>
            </div>
        </div>

        <div class="relative z-10 shrink-0 w-full md:w-auto">
            <a href="{{ route('kader.absensi.index', ['kategori' => $absensi->kategori]) }}" class="w-full md:w-auto px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold text-[11px] uppercase tracking-widest rounded-xl hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-colors shadow-sm flex items-center justify-center gap-2">
                <i class="fas fa-pen text-[12px]"></i> Koreksi Data
            </a>
        </div>
    </div>

    {{-- ========================================================
         2. GRID UTAMA (SIMETRIS & STRETCH)
         ======================================================== --}}
    {{-- Container CSS Grid secara bawaan menarik tinggi anak-anaknya agar sama (stretch) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-stretch">
        
        {{-- ========================================== --}}
        {{-- PANEL KIRI (SIDEBAR - 4 KOLOM) --}}
        {{-- ========================================== --}}
        <aside class="lg:col-span-4 flex flex-col gap-6 h-full">
            
            {{-- Kartu Persentase (Dark Mode - Fixed Height) --}}
            <div class="bg-slate-900 rounded-[24px] p-6 text-white shadow-lg border border-slate-800 relative overflow-hidden shrink-0">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-emerald-500/20 rounded-full blur-2xl"></div>

                <h4 class="font-bold text-[11px] text-slate-400 uppercase tracking-widest mb-1 relative z-10">Tingkat Kehadiran</h4>
                <div class="flex items-baseline gap-1.5 mb-4 relative z-10">
                    <span class="text-5xl font-black text-white font-poppins tracking-tighter">{{ $totalPasien > 0 ? round(($totalHadir / $totalPasien) * 100) : 0 }}</span>
                    <span class="text-lg font-bold text-slate-400">%</span>
                </div>
                
                <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden relative z-10 border border-slate-700">
                    <div class="h-full bg-emerald-400 rounded-full transition-all duration-1000 ease-out" style="width: {{ $totalPasien > 0 ? ($totalHadir / $totalPasien) * 100 : 0 }}%"></div>
                </div>
                
                <p class="text-slate-400 text-[11px] mt-4 leading-relaxed font-medium relative z-10">Rasio warga yang hadir berbanding total seluruh sasaran terdaftar.</p>
            </div>

            {{-- Kartu Riwayat Navigasi Cepat (Flex-1: STRETCH OTOMATIS KE BAWAH) --}}
            <div class="bg-white border border-slate-200 rounded-[24px] p-5 shadow-sm flex flex-col flex-1 overflow-hidden">
                <h3 class="font-bold text-slate-800 text-[11px] uppercase tracking-widest mb-4 flex items-center gap-2 pb-3 border-b border-slate-100 shrink-0">
                    <i class="fas fa-history text-indigo-400"></i> Riwayat {{ ucfirst(str_replace('_', ' ', $absensi->kategori)) }}
                </h3>
                
                {{-- Daftar Riwayat (Bisa di-scroll jika isinya sangat banyak) --}}
                <div class="space-y-1 overflow-y-auto pr-1 custom-scroll flex-1">
                    @foreach($semuaSesi as $sesi)
                        <a href="{{ route('kader.absensi.show', $sesi->id) }}" 
                           class="flex items-center gap-3 p-2.5 rounded-[16px] transition-colors border 
                           {{ $sesi->id == $absensi->id ? 'bg-indigo-50 border-indigo-100 shadow-sm' : 'bg-transparent border-transparent hover:bg-slate-50' }}">
                            <div class="w-10 h-10 rounded-[12px] flex items-center justify-center shrink-0 {{ $sesi->id == $absensi->id ? 'bg-indigo-500 text-white shadow-md' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                <span class="text-[13px] font-bold font-poppins">#{{ $sesi->nomor_pertemuan }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[12px] font-bold text-slate-800 truncate {{ $sesi->id == $absensi->id ? 'text-indigo-700' : '' }}">{{ \Carbon\Carbon::parse($sesi->tanggal_posyandu)->translatedFormat('d M Y') }}</p>
                                <p class="text-[10px] font-medium text-slate-400 uppercase tracking-widest truncate mt-0.5">{{ $sesi->kode_absensi }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- ========================================== --}}
        {{-- PANEL KANAN (KONTEN UTAMA - 8 KOLOM) --}}
        {{-- ========================================== --}}
        <main class="lg:col-span-8 flex flex-col gap-6 h-full">
            
            {{-- 3 Mini Stat Cards (Shrink-0 agar tingginya tetap stabil) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 shrink-0">
                <div class="bg-white border border-slate-200 rounded-[20px] p-5 flex items-center justify-between shadow-sm hover:border-slate-300 transition-colors">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Sasaran</p>
                        <h3 class="text-2xl font-black text-slate-800 font-poppins leading-none">{{ $totalPasien }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400"><i class="fas fa-users"></i></div>
                </div>
                <div class="bg-white border border-slate-200 rounded-[20px] p-5 flex items-center justify-between shadow-sm hover:border-emerald-200 transition-colors">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Hadir</p>
                        <h3 class="text-2xl font-black text-emerald-500 font-poppins leading-none">{{ $totalHadir }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500"><i class="fas fa-user-check"></i></div>
                </div>
                <div class="bg-white border border-slate-200 rounded-[20px] p-5 flex items-center justify-between shadow-sm hover:border-rose-200 transition-colors">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Absen</p>
                        <h3 class="text-2xl font-black text-rose-500 font-poppins leading-none">{{ $totalAbsen }}</h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-500"><i class="fas fa-user-times"></i></div>
                </div>
            </div>

            {{-- KARTU TABEL MANIFEST (Flex-1: STRETCH OTOMATIS KE BAWAH) --}}
            {{-- Kotak ini akan memanjang ke bawah persis menyamai kotak "Riwayat" di sebelahnya --}}
            <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm flex-1 flex flex-col overflow-hidden">
                
                {{-- Header & Search Bar (Shrink-0) --}}
                <div class="px-6 py-5 bg-white flex flex-col sm:flex-row items-center justify-between gap-4 shrink-0">
                    <div>
                        <h3 class="font-bold text-slate-800 text-[13px] uppercase tracking-widest">Rincian Daftar Hadir</h3>
                        <p class="text-[11px] font-medium text-slate-500 mt-0.5">Rekapitulasi per individu.</p>
                    </div>
                    <div class="relative w-full sm:w-[260px]">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[12px]"></i>
                        <input type="text" id="manifestSearch" placeholder="Cari nama atau NIK..." class="search-input">
                    </div>
                </div>

                {{-- Area Tabel yang bisa di-scroll secara internal --}}
                <div class="overflow-x-auto overflow-y-auto custom-scroll flex-1 min-h-[300px]">
                    <table class="nexus-table">
                        <thead>
                            <tr>
                                <th class="w-16 pl-6 text-center">No</th>
                                <th>Identitas Peserta</th>
                                <th class="w-32 text-center">Status</th>
                                <th class="w-[35%]">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="manifestTableBody">
                            @foreach($details as $index => $row)
                            <tr>
                                <td class="pl-6 text-center">
                                    <span class="text-[12px] font-bold text-slate-400 font-mono">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <p class="manifest-nama font-semibold text-slate-800 text-[13px] mb-0.5 truncate">{{ $row->pasien_data->nama_lengkap ?? 'Data Terhapus' }}</p>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <i class="far fa-address-card text-slate-300 text-[11px]"></i> 
                                        <p class="text-[10px] font-medium text-slate-500 font-mono tracking-widest">{{ $row->pasien_data->nik ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="text-center">
                                    {{-- KAPSUL STATUS (PRESISI) --}}
                                    @if($row->hadir)
                                        <div class="inline-flex items-center justify-center w-[80px] py-1.5 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-bold uppercase tracking-widest">
                                            Hadir
                                        </div>
                                    @else
                                        <div class="inline-flex items-center justify-center w-[80px] py-1.5 rounded-full bg-rose-50 border border-rose-100 text-rose-600 text-[10px] font-bold uppercase tracking-widest">
                                            Absen
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <p class="text-[12px] font-medium text-slate-600 truncate max-w-[200px]" title="{{ $row->keterangan }}">{{ $row->keterangan ?: '-' }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer Info Rekam Jejak (Shrink-0) --}}
                <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left shrink-0">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                        Pencatat: <span class="text-slate-800">{{ $absensi->pencatat->name ?? 'Sistem' }}</span>
                    </p>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                        Waktu Rekam: <span class="text-slate-800 font-mono">{{ $absensi->updated_at->translatedFormat('d M Y, H:i:s') }} WIB</span>
                    </p>
                </div>

            </div>

        </main>
    </div>
</div>

@push('scripts')
<script>
    // Fitur Pencarian Manifest Instan (Ringan & Cepat)
    const searchInput = document.getElementById('manifestSearch');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#manifestTableBody tr');
            
            rows.forEach(row => {
                const textData = row.textContent.toLowerCase();
                row.style.display = textData.includes(filter) ? '' : 'none';
            });
        });
    }
</script>
@endpush
@endsection