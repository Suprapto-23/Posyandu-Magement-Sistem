@extends('layouts.kader')

@section('title', 'Data Bayi & Balita')
@section('page-name', 'Database Bayi & Balita')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

    .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.08); }
    
    /* Custom Checkbox Modern */
    .checkbox-modern {
        appearance: none; width: 22px; height: 22px; border: 2px solid #cbd5e1; border-radius: 6px; 
        background: #f8fafc; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); position: relative; display: inline-block;
    }
    .checkbox-modern:hover { border-color: #818cf8; background: #e0e7ff; }
    .checkbox-modern:checked { background: #6366f1; border-color: #6366f1; transform: scale(1.05); }
    .checkbox-modern:checked::after {
        content: '\f00c'; font-family: 'Font Awesome 5 Free'; font-weight: 900; position: absolute; 
        color: white; font-size: 11px; top: 50%; left: 50%; transform: translate(-50%, -50%);
    }

    /* Tab Switcher Floating iOS Style */
    .tab-pill { padding: 0.6rem 1.5rem; border-radius: 9999px; font-weight: 800; font-size: 0.8rem; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); text-align: center; text-transform: uppercase; letter-spacing: 0.05em; }
    .tab-inactive { color: #64748b; background: transparent; }
    .tab-inactive:hover { color: #334155; background: #e2e8f0; }
    .tab-bayi-active { background: linear-gradient(135deg, #0ea5e9, #3b82f6); color: white; box-shadow: 0 4px 15px -3px rgba(59, 130, 246, 0.4); }
    .tab-balita-active { background: linear-gradient(135deg, #f43f5e, #e11d48); color: white; box-shadow: 0 4px 15px -3px rgba(225, 29, 72, 0.4); }
    
    .tab-content { display: none; opacity: 0; transform: translateY(10px); transition: all 0.4s ease; }
    .tab-content.active { display: block; opacity: 1; transform: translateY(0); }
    
    /* Table Scrollbar */
    .table-container { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-20 h-20 mb-4">
        <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="absolute inset-0 flex items-center justify-center text-indigo-500"><i class="fas fa-baby text-2xl animate-pulse"></i></div>
    </div>
    <p class="text-sm font-black tracking-widest text-indigo-500 uppercase animate-pulse">Memuat Database...</p>
</div>

<div class="max-w-[1400px] mx-auto animate-slide-up">
    
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center text-3xl shadow-[0_8px_20px_rgba(79,70,229,0.3)] shrink-0 group hover:scale-105 transition-transform">
                <i class="fas fa-baby-carriage group-hover:-rotate-12 transition-transform"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight mb-1">Database Anak</h1>
                <p class="text-sm font-bold text-slate-500">Kelola master data Bayi & Balita, riwayat kelahiran, dan sinkronisasi akun warga.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <a href="{{ route('kader.import.index') }}" class="flex-1 md:flex-none justify-center flex items-center gap-2 px-5 py-3 bg-white text-emerald-600 font-black text-[13px] rounded-xl hover:bg-emerald-50 transition-all border border-emerald-200 shadow-sm hover:shadow-emerald-100 uppercase tracking-widest">
                <i class="fas fa-file-excel text-lg"></i> Import
            </a>
            <a href="{{ route('kader.data.balita.create') }}" class="flex-1 md:flex-none justify-center flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-black text-[13px] rounded-xl hover:from-indigo-500 hover:to-violet-500 shadow-[0_8px_15px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all uppercase tracking-widest">
                <i class="fas fa-plus-circle text-lg"></i> Registrasi Baru
            </a>
        </div>
    </div>

    {{-- KENDALI NAVIGASI (SWITCHER & SEARCH) --}}
    <div class="glass-card rounded-[24px] p-3 mb-6 flex flex-col xl:flex-row items-center gap-4 justify-between relative z-20">
        
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto">
            {{-- Floating Tab --}}
            <div class="bg-slate-100/80 p-1.5 rounded-full flex w-full sm:w-max border border-slate-200/50 shadow-inner">
                <button id="tab-btn-bayi" onclick="switchTab('bayi')" class="tab-pill tab-bayi-active flex-1 sm:flex-none">
                    Bayi ({{ $bayis->count() }})
                </button>
                <button id="tab-btn-balita" onclick="switchTab('balita')" class="tab-pill tab-inactive flex-1 sm:flex-none">
                    Balita ({{ $balitas->count() }})
                </button>
            </div>
            
            {{-- Tombol Bulk Delete (Muncul otomatis pakai JS) --}}
            <form action="{{ route('kader.data.balita.bulk-delete') }}" method="POST" id="bulkDeleteForm" class="hidden w-full sm:w-auto">
                @csrf
                <div id="bulkDeleteInputs"></div>
                <button type="button" onclick="confirmBulkDelete()" class="w-full sm:w-auto px-6 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 font-black text-[13px] uppercase tracking-widest rounded-full hover:bg-rose-500 hover:text-white shadow-sm hover:shadow-[0_4px_12px_rgba(225,29,72,0.3)] transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-trash-alt"></i> Hapus (<span id="bulkCount">0</span>)
                </button>
            </form>
        </div>
        
        {{-- Live Search Cerdas --}}
        <div class="w-full xl:w-80 relative group">
            <input type="text" id="liveSearchInput" placeholder="Ketik Nama / NIK Anak..." 
                   class="w-full bg-white border-2 border-slate-200 rounded-full py-3 pl-12 pr-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 shadow-sm placeholder:text-slate-400">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center bg-slate-100 rounded-full group-focus-within:bg-indigo-100 transition-colors">
                <i class="fas fa-search text-xs text-slate-400 group-focus-within:text-indigo-500"></i>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TAB 1: KELOMPOK BAYI (0-11 Bulan) --}}
    {{-- ======================================================== --}}
    <div id="panel-bayi" class="tab-content active">
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="table-container overflow-x-auto max-h-[65vh]">
                <table class="w-full text-left border-collapse whitespace-nowrap min-w-[1000px]">
                    <thead class="sticky top-0 z-10 bg-slate-50/90 backdrop-blur-sm border-b border-slate-200 shadow-sm">
                        <tr class="text-[10px] uppercase tracking-widest text-slate-500 font-black">
                            <th class="px-5 py-4 text-center w-12 border-r border-slate-200/50"><input type="checkbox" class="checkbox-modern select-all-bayi" onclick="toggleSelectAll(this, 'bayi')"></th>
                            <th class="px-5 py-4 border-r border-slate-200/50">Identitas Bayi</th>
                            <th class="px-5 py-4 border-r border-slate-200/50">Fisik Kelahiran</th>
                            <th class="px-5 py-4 border-r border-slate-200/50">Data Keluarga</th>
                            <th class="px-5 py-4 text-center border-r border-slate-200/50">Usia & Sinkronisasi</th>
                            <th class="px-5 py-4 text-center">Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="tableBodyBayi">
                        @forelse($bayis as $item)
                        @php 
                            $diff = \Carbon\Carbon::parse($item->tanggal_lahir)->diff(now());
                            $totalBln = ($diff->y * 12) + $diff->m;
                            $strUmur = $totalBln == 0 ? $diff->d . ' Hari' : $totalBln . ' Bulan';
                            $fullUmurTitle = $diff->y . ' Tahun, ' . $diff->m . ' Bulan, ' . $diff->d . ' Hari';
                            $avatarColor = $item->jenis_kelamin == 'L' ? 'bg-sky-100 text-sky-600' : 'bg-pink-100 text-pink-600';
                            $jkBadge = $item->jenis_kelamin == 'L' ? 'bg-sky-50 text-sky-600 border-sky-200' : 'bg-pink-50 text-pink-600 border-pink-200';
                        @endphp
                        <tr class="hover:bg-sky-50/40 transition-colors pasien-row" data-search="{{ strtolower($item->nama_lengkap . ' ' . $item->nik . ' ' . $item->nama_ibu) }}">
                            <td class="px-5 py-4 text-center border-r border-slate-200/50"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="checkbox-modern row-checkbox bayi-checkbox" onchange="checkBulkStatus()"></td>
                            
                            {{-- Identitas --}}
                            <td class="px-5 py-4 border-r border-slate-200/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-lg shrink-0 {{ $avatarColor }} border border-white shadow-sm">
                                        {{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-black text-slate-800">{{ $item->nama_lengkap }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[9px] font-black tracking-widest px-2 py-0.5 rounded border {{ $jkBadge }}">
                                                {{ $item->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}
                                            </span>
                                            <span class="text-[10px] font-bold text-slate-500 font-mono tracking-wide bg-slate-100 px-2 py-0.5 rounded"><i class="far fa-id-card text-slate-400"></i> {{ $item->nik ?? '0000000000000000' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Fisik Kelahiran --}}
                            <td class="px-5 py-4 border-r border-slate-200/50">
                                <p class="text-[11px] font-bold text-slate-700 mb-1.5"><i class="fas fa-map-marker-alt text-slate-400 w-3 text-center"></i> {{ $item->tempat_lahir }}, {{ \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d M Y') }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded shadow-sm">BB: <span class="text-indigo-600 font-black">{{ $item->berat_lahir }} kg</span></span>
                                    <span class="text-[10px] font-bold text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded shadow-sm">TB: <span class="text-emerald-600 font-black">{{ $item->panjang_lahir }} cm</span></span>
                                </div>
                            </td>

                            {{-- Data Orang Tua --}}
                            <td class="px-5 py-4 border-r border-slate-200/50">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded bg-rose-50 flex items-center justify-center text-rose-400 text-[10px]"><i class="fas fa-female"></i></div>
                                        <p class="text-xs font-bold text-slate-800">{{ $item->nama_ibu ?? '—' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded bg-sky-50 flex items-center justify-center text-sky-400 text-[10px]"><i class="fas fa-male"></i></div>
                                        <p class="text-[11px] font-semibold text-slate-500">{{ $item->nama_ayah ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Usia & Akun --}}
                            <td class="px-5 py-4 text-center border-r border-slate-200/50">
                                <span title="{{ $fullUmurTitle }}" class="inline-flex items-center gap-1 mb-2 px-3 py-1 rounded-lg text-[10px] font-black bg-slate-800 text-white shadow-sm cursor-help transition-transform hover:scale-105 tracking-widest uppercase">
                                    <i class="far fa-clock text-slate-300"></i> {{ $strUmur }}
                                </span>
                                <div class="block">
                                    @if($item->user_id)
                                        <span class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200 shadow-sm"><i class="fas fa-link"></i> Terhubung Akun Ibu</span>
                                    @else
                                        <a href="{{ route('kader.data.balita.sync', $item->id) }}" class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-amber-600 bg-white border border-amber-300 px-2.5 py-1 rounded-md hover:bg-amber-50 hover:border-amber-400 transition-all shadow-sm"><i class="fas fa-satellite-dish animate-pulse"></i> Deteksi Akun Warga</a>
                                    @endif
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kader.data.balita.show', $item->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-indigo-500 hover:bg-indigo-50 hover:text-indigo-600 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Buku KIA Digital"><i class="fas fa-book-medical"></i></a>
                                    <a href="{{ route('kader.data.balita.edit', $item->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-800 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Edit Master Data"><i class="fas fa-edit"></i></a>
                                    
                                    <form action="{{ route('kader.data.balita.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmSingleDelete('{{ $item->id }}', '{{ addslashes($item->nama_lengkap) }}')" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-rose-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Hapus Permanen"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-inner border border-slate-100"><i class="fas fa-clipboard-list"></i></div>
                                <h3 class="font-black text-slate-800 text-lg">Database Bayi Kosong</h3>
                                <p class="text-sm text-slate-500 mt-1">Gunakan tombol Registrasi Baru atau Import Excel untuk menambahkan data.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TAB 2: KELOMPOK BALITA (12-59 Bulan) --}}
    {{-- ======================================================== --}}
    <div id="panel-balita" class="tab-content">
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="table-container overflow-x-auto max-h-[65vh]">
                <table class="w-full text-left border-collapse whitespace-nowrap min-w-[1000px]">
                    <thead class="sticky top-0 z-10 bg-slate-50/90 backdrop-blur-sm border-b border-slate-200 shadow-sm">
                        <tr class="text-[10px] uppercase tracking-widest text-slate-500 font-black">
                            <th class="px-5 py-4 text-center w-12 border-r border-slate-200/50"><input type="checkbox" class="checkbox-modern select-all-balita" onclick="toggleSelectAll(this, 'balita')"></th>
                            <th class="px-5 py-4 border-r border-slate-200/50">Identitas Balita</th>
                            <th class="px-5 py-4 border-r border-slate-200/50">Fisik Kelahiran</th>
                            <th class="px-5 py-4 border-r border-slate-200/50">Data Keluarga</th>
                            <th class="px-5 py-4 text-center border-r border-slate-200/50">Usia & Sinkronisasi</th>
                            <th class="px-5 py-4 text-center">Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" id="tableBodyBalita">
                        @forelse($balitas as $item)
                        @php
                            $diff = \Carbon\Carbon::parse($item->tanggal_lahir)->diff(now());
                            if ($diff->y > 0 && $diff->m > 0) $strUmur = $diff->y . ' Thn ' . $diff->m . ' Bln';
                            elseif ($diff->y > 0 && $diff->m == 0) $strUmur = $diff->y . ' Tahun';
                            elseif ($diff->y == 0 && $diff->m > 0) $strUmur = $diff->m . ' Bulan';
                            else $strUmur = $diff->d . ' Hari';
                            
                            $fullUmurTitle = $diff->y . ' Tahun, ' . $diff->m . ' Bulan, ' . $diff->d . ' Hari';
                            $avatarColor = $item->jenis_kelamin == 'L' ? 'bg-sky-100 text-sky-600' : 'bg-pink-100 text-pink-600';
                            $jkBadge = $item->jenis_kelamin == 'L' ? 'bg-sky-50 text-sky-600 border-sky-200' : 'bg-pink-50 text-pink-600 border-pink-200';
                        @endphp
                        <tr class="hover:bg-rose-50/40 transition-colors pasien-row" data-search="{{ strtolower($item->nama_lengkap . ' ' . $item->nik . ' ' . $item->nama_ibu) }}">
                            <td class="px-5 py-4 text-center border-r border-slate-200/50"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="checkbox-modern row-checkbox balita-checkbox" onchange="checkBulkStatus()"></td>
                            
                            {{-- Identitas --}}
                            <td class="px-5 py-4 border-r border-slate-200/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-lg shrink-0 {{ $avatarColor }} border border-white shadow-sm">
                                        {{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-black text-slate-800">{{ $item->nama_lengkap }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[9px] font-black tracking-widest px-2 py-0.5 rounded border {{ $jkBadge }}">
                                                {{ $item->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}
                                            </span>
                                            <span class="text-[10px] font-bold text-slate-500 font-mono tracking-wide bg-slate-100 px-2 py-0.5 rounded"><i class="far fa-id-card text-slate-400"></i> {{ $item->nik ?? '0000000000000000' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Fisik Kelahiran --}}
                            <td class="px-5 py-4 border-r border-slate-200/50">
                                <p class="text-[11px] font-bold text-slate-700 mb-1.5"><i class="fas fa-map-marker-alt text-slate-400 w-3 text-center"></i> {{ $item->tempat_lahir }}, {{ \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d M Y') }}</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded shadow-sm">BB: <span class="text-indigo-600 font-black">{{ $item->berat_lahir }} kg</span></span>
                                    <span class="text-[10px] font-bold text-slate-500 bg-white border border-slate-200 px-2 py-1 rounded shadow-sm">TB: <span class="text-emerald-600 font-black">{{ $item->panjang_lahir }} cm</span></span>
                                </div>
                            </td>

                            {{-- Data Orang Tua --}}
                            <td class="px-5 py-4 border-r border-slate-200/50">
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded bg-rose-50 flex items-center justify-center text-rose-400 text-[10px]"><i class="fas fa-female"></i></div>
                                        <p class="text-xs font-bold text-slate-800">{{ $item->nama_ibu ?? '—' }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded bg-sky-50 flex items-center justify-center text-sky-400 text-[10px]"><i class="fas fa-male"></i></div>
                                        <p class="text-[11px] font-semibold text-slate-500">{{ $item->nama_ayah ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Usia & Akun --}}
                            <td class="px-5 py-4 text-center border-r border-slate-200/50">
                                <span title="{{ $fullUmurTitle }}" class="inline-flex items-center gap-1 mb-2 px-3 py-1 rounded-lg text-[10px] font-black bg-rose-100 text-rose-700 shadow-sm cursor-help transition-transform hover:scale-105 tracking-widest uppercase">
                                    <i class="far fa-clock opacity-70"></i> {{ $strUmur }}
                                </span>
                                <div class="block">
                                    @if($item->user_id)
                                        <span class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200 shadow-sm"><i class="fas fa-link"></i> Terhubung Akun Ibu</span>
                                    @else
                                        <a href="{{ route('kader.data.balita.sync', $item->id) }}" class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-amber-600 bg-white border border-amber-300 px-2.5 py-1 rounded-md hover:bg-amber-50 hover:border-amber-400 transition-all shadow-sm"><i class="fas fa-satellite-dish animate-pulse"></i> Deteksi Akun Warga</a>
                                    @endif
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kader.data.balita.show', $item->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-indigo-500 hover:bg-indigo-50 hover:text-indigo-600 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Buku KIA Digital"><i class="fas fa-book-medical"></i></a>
                                    <a href="{{ route('kader.data.balita.edit', $item->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-800 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Edit Master Data"><i class="fas fa-edit"></i></a>
                                    
                                    <form action="{{ route('kader.data.balita.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmSingleDelete('{{ $item->id }}', '{{ addslashes($item->nama_lengkap) }}')" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-rose-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Hapus Permanen"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-inner border border-slate-100"><i class="fas fa-clipboard-list"></i></div>
                                <h3 class="font-black text-slate-800 text-lg">Database Balita Kosong</h3>
                                <p class="text-sm text-slate-500 mt-1">Gunakan tombol Registrasi Baru atau Import Excel untuk menambahkan data.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. Matikan Loader
    window.onload = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100','pointer-events-auto'); l.classList.add('opacity-0','pointer-events-none'); setTimeout(()=> l.style.display = 'none', 300); }
    };

    // 2. TAB SWITCHER (Mempertahankan Pencarian)
    function switchTab(tab) {
        const panels = ['bayi', 'balita'];
        panels.forEach(p => document.getElementById('panel-' + p)?.classList.remove('active'));
        
        document.getElementById('tab-btn-bayi').className = 'tab-pill tab-inactive flex-1 sm:flex-none';
        document.getElementById('tab-btn-balita').className = 'tab-pill tab-inactive flex-1 sm:flex-none';

        document.getElementById('panel-' + tab).classList.add('active');
        document.getElementById('tab-btn-' + tab).className = `tab-pill tab-${tab}-active flex-1 sm:flex-none`;
        
        sessionStorage.setItem('activeTabBalita', tab);
        
        // Reset checkbox when switching tabs
        document.querySelectorAll('.checkbox-modern').forEach(cb => cb.checked = false);
        checkBulkStatus();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const saved = sessionStorage.getItem('activeTabBalita');
        if (saved && (saved === 'bayi' || saved === 'balita')) switchTab(saved);

        // 3. FITUR BARU: LIVE SEARCH JAVASCRIPT
        const searchInput = document.getElementById('liveSearchInput');
        if(searchInput) {
            searchInput.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('.pasien-row');
                
                rows.forEach(row => {
                    const dataSearch = row.getAttribute('data-search');
                    if (dataSearch.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                        // Hapus centang jika baris disembunyikan agar tidak terhapus tak sengaja
                        const cb = row.querySelector('.row-checkbox');
                        if(cb && cb.checked) { cb.checked = false; checkBulkStatus(); }
                    }
                });
            });
        }
    });

    // 4. SISTEM NOTIFIKASI SWEETALERT
    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
        timerProgressBar: true, didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
    });

    @if(session('success'))
        Toast.fire({ icon: 'success', title: 'Berhasil!', text: "{!! addslashes(session('success')) !!}" });
    @endif
    @if(session('error'))
        Toast.fire({ icon: 'error', title: 'Oops...', text: "{!! addslashes(session('error')) !!}" });
    @endif

    // 5. KONFIRMASI HAPUS (Single & Bulk)
    function confirmSingleDelete(id, name) {
        Swal.fire({
            title: 'Hapus ' + name + '?',
            html: "Data master, rekam medis fisik, dan absensi anak ini akan <b>hilang permanen!</b>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus Permanen',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-3xl border border-slate-100' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function toggleSelectAll(source, type) {
        // Hanya centang baris yang sedang tidak disembunyikan oleh Live Search
        const checkboxes = document.querySelectorAll(`.${type}-checkbox`);
        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            if(row.style.display !== 'none') {
                cb.checked = source.checked;
            }
        });
        checkBulkStatus();
    }

    function checkBulkStatus() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const bulkForm = document.getElementById('bulkDeleteForm');
        const bulkCountSpan = document.getElementById('bulkCount');
        
        if (checkedBoxes.length > 0) {
            bulkForm.classList.remove('hidden');
            bulkForm.classList.add('animate-slide-up');
            bulkCountSpan.innerText = checkedBoxes.length;
        } else {
            bulkForm.classList.add('hidden');
            bulkForm.classList.remove('animate-slide-up');
        }
    }

    function confirmBulkDelete() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if(checkedBoxes.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checkedBoxes.length + ' Anak Terpilih?',
            html: "Peringatan Sistem: Aksi ini akan menghapus data masal beserta seluruh log kunjungan dan pemeriksaan anak-anak tersebut.",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fas fa-skull-crossbones"></i> Eksekusi Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-3xl border-4 border-rose-100 shadow-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                const inputContainer = document.getElementById('bulkDeleteInputs');
                inputContainer.innerHTML = ''; 
                
                checkedBoxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = cb.value;
                    inputContainer.appendChild(input);
                });
                
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    }
</script>
@endsection