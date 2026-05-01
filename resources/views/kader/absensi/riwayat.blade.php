@extends('layouts.kader')
@section('title', 'Riwayat Absensi Posyandu')
@section('page-name', 'Arsip Sesi Absensi')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* =================================================================
       NEXUS SAAS DESIGN SYSTEM (CLEAN & MINIMAL)
       ================================================================= */
    
    /* Animasi Masuk */
    .animate-fade-in { opacity: 0; animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .stagger-1 { animation-delay: 0.05s; } .stagger-2 { animation-delay: 0.1s; } .stagger-3 { animation-delay: 0.15s; }

    /* Dropdown Native (Super Rapi & Anti-Bug) */
    .nexus-select {
        appearance: none; -webkit-appearance: none;
        background-color: #ffffff; border: 1px solid #e2e8f0; color: #475569;
        font-family: 'Inter', sans-serif; font-size: 0.85rem; font-weight: 600;
        border-radius: 12px; padding: 0.65rem 2.25rem 0.65rem 1rem; width: 100%; cursor: pointer;
        transition: all 0.2s ease; box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1rem;
    }
    .nexus-select:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); outline: none; }
    .nexus-select:hover { border-color: #cbd5e1; }

    /* Desain List Row Terpadu (Sleek List) */
    .history-row {
        background: #ffffff; border: 1px solid #f1f5f9; border-radius: 16px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .history-row:hover {
        border-color: #cbd5e1; box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.05);
        transform: translateY(-2px); z-index: 10; position: relative;
    }

    /* Progress Bar (Tipis & Elegan) */
    .progress-track { width: 100%; height: 5px; background-color: #f1f5f9; border-radius: 99px; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 99px; transition: width 1s ease-out; }

    /* =================================================================
       SWEETALERT 2 - REDESIGN TOTAL (ISOLASI TOAST VS MODAL)
       ================================================================= */
    
    /* 1. Backdrop Gelap HANYA untuk Popup Konfirmasi Hapus */
    body.swal2-shown:not(.swal2-toast-shown) .swal2-container { 
        z-index: 10000 !important; backdrop-filter: blur(4px) !important; background: rgba(15, 23, 42, 0.4) !important; 
    }
    
    /* 2. Modal Konfirmasi Hapus (Popup Tengah) */
    .nexus-modal { border-radius: 24px !important; padding: 2rem 1.5rem !important; background: #ffffff !important; width: 24em !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.2) !important; border: 1px solid #f1f5f9 !important; }
    .nexus-modal .swal2-title { font-family: 'Inter', sans-serif !important; font-weight: 800 !important; font-size: 1.15rem !important; color: #0f172a !important; margin-bottom: 0.25rem !important; }
    .nexus-modal .swal2-html-container { font-family: 'Inter', sans-serif !important; color: #64748b !important; font-size: 0.85rem !important; line-height: 1.5 !important; }
    
    /* Tombol Konfirmasi */
    .btn-swal-danger { background: #f43f5e !important; color: white !important; border-radius: 100px !important; padding: 10px 20px !important; font-weight: 700 !important; font-size: 11px !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; border: none !important; margin-right: 8px !important; transition: 0.2s !important; }
    .btn-swal-danger:hover { background: #e11d48 !important; }
    .btn-swal-cancel { background: #f1f5f9 !important; color: #475569 !important; border-radius: 100px !important; padding: 10px 20px !important; font-weight: 700 !important; font-size: 11px !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; border: none !important; transition: 0.2s !important; }
    .btn-swal-cancel:hover { background: #e2e8f0 !important; }

    /* 3. TOAST (NOTIFIKASI POJOK KANAN ATAS - SUPER KECIL & PRESISI) */
    div:where(.swal2-container).swal2-top-end { pointer-events: none !important; }
    div:where(.swal2-container).swal2-top-end > .swal2-toast {
        pointer-events: auto !important;
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        padding: 8px 16px !important; /* Padding diminimalkan */
        box-shadow: 0 4px 15px rgba(0,0,0,0.08) !important;
        width: auto !important;
        display: flex !important;
        align-items: center !important;
        margin-top: 1rem !important;
        margin-right: 1rem !important;
    }
    /* Memperkecil Ikon Bawaan SweetAlert */
    div:where(.swal2-container).swal2-top-end .swal2-icon {
        transform: scale(0.55) !important; /* Mengecilkan ikon hingga 55% */
        margin: 0 8px 0 -4px !important;
    }
    /* Format Teks Toast */
    div:where(.swal2-container).swal2-top-end .swal2-title {
        font-family: 'Inter', sans-serif !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #334155 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-[1050px] mx-auto animate-fade-in pb-16 mt-2 relative z-10">

    {{-- AURA BACKGROUND SANGAT LEMBUT --}}
    <div class="fixed top-0 right-0 w-[400px] h-[400px] bg-indigo-400/5 rounded-full blur-[80px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 left-0 w-[300px] h-[300px] bg-sky-400/5 rounded-full blur-[80px] pointer-events-none -z-10"></div>

    {{-- 1. HEADER (Minimalis SaaS) --}}
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shrink-0 border border-indigo-100">
                <i class="fas fa-folder-open"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight font-poppins mb-0.5">Arsip Sesi Presensi</h1>
                <p class="text-slate-500 font-medium text-[13px]">Kelola log kehadiran warga Posyandu.</p>
            </div>
        </div>
    </div>

    {{-- 2. PANEL FILTER (Satu Baris, Memakai Native Select) --}}
    @php
        $reqKategori = request('kategori', '');
        $reqBulanStr = request('bulan');
        $selTahun = $reqBulanStr ? substr($reqBulanStr, 0, 4) : '';
        $selBulan = $reqBulanStr ? substr($reqBulanStr, 5, 2) : '';
        $tahunSaatIni = date('Y');

        $mapBulan = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
    @endphp

    <div class="bg-white p-4 rounded-[20px] border border-slate-200 shadow-sm mb-6 flex flex-col xl:flex-row items-center gap-3 relative z-20">
        <form id="filterForm" action="{{ route('kader.absensi.riwayat') }}" method="GET" class="w-full flex flex-col md:flex-row items-center gap-3">
            <input type="hidden" name="bulan" id="hiddenBulan" value="{{ request('bulan') }}">

            <div class="w-full md:w-[35%]">
                <select name="kategori" class="nexus-select">
                    <option value="">Semua Kategori Sasaran</option>
                    <option value="bayi" {{ $reqKategori == 'bayi' ? 'selected' : '' }}>Bayi (0-11 Bulan)</option>
                    <option value="balita" {{ $reqKategori == 'balita' ? 'selected' : '' }}>Balita (1-5 Tahun)</option>
                    <option value="ibu_hamil" {{ $reqKategori == 'ibu_hamil' ? 'selected' : '' }}>Ibu Hamil</option>
                    <option value="remaja" {{ $reqKategori == 'remaja' ? 'selected' : '' }}>Remaja</option>
                    <option value="lansia" {{ $reqKategori == 'lansia' ? 'selected' : '' }}>Lansia</option>
                </select>
            </div>

            <div class="w-full md:w-[25%]">
                <select id="valBulan" class="nexus-select">
                    <option value="">Pilih Bulan</option>
                    @foreach($mapBulan as $val => $label)
                        <option value="{{ $val }}" {{ $selBulan == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-[20%]">
                <select id="valTahun" class="nexus-select">
                    <option value="">Tahun</option>
                    @for($y = $tahunSaatIni; $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $selTahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="w-full md:w-[20%] flex items-center gap-2">
                <button type="submit" class="flex-1 bg-slate-800 text-white font-bold text-[11px] uppercase tracking-widest rounded-xl py-3 hover:bg-indigo-600 transition-colors shadow-sm">
                    Cari
                </button>
                @if(request('kategori') || request('bulan'))
                    <a href="{{ route('kader.absensi.riwayat') }}" class="w-10 h-10 shrink-0 bg-white text-slate-400 rounded-xl flex items-center justify-center hover:bg-slate-100 hover:text-slate-700 transition-colors border border-slate-200" title="Reset">
                        <i class="fas fa-undo text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- 3. DAFTAR RIWAYAT (Sleek List - Super Rapih) --}}
    @if(count($riwayat) > 0)
        <div class="space-y-3 relative z-10">
            @foreach($riwayat as $index => $item)
                @php
                    $totalPasien = $item->details->count();
                    $totalHadir  = $item->details->where('hadir', true)->count();
                    $persentase  = $totalPasien > 0 ? round(($totalHadir / $totalPasien) * 100) : 0;
                    
                    $color = 'emerald'; 
                    if($persentase < 50) $color = 'rose'; 
                    elseif($persentase < 75) $color = 'amber'; 

                    $tgl = \Carbon\Carbon::parse($item->tanggal_posyandu);
                    $staggerClass = 'stagger-' . (($index % 4) + 1);
                @endphp

                <div class="history-row {{ $staggerClass }} animate-fade-in p-4 md:p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-5" style="opacity: 0;">
                    
                    {{-- Blok Informasi (Kiri) --}}
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-[12px] flex flex-col items-center justify-center shrink-0 text-slate-600">
                            <span class="text-[15px] font-bold leading-none font-poppins">{{ $tgl->format('d') }}</span>
                            <span class="text-[9px] font-bold uppercase tracking-widest mt-0.5">{{ $tgl->translatedFormat('M y') }}</span>
                        </div>
                        
                        <div class="min-w-0">
                            <h4 class="text-[14px] font-semibold text-slate-800 font-poppins truncate mb-1" title="{{ $item->kode_absensi }}">{{ $item->kode_absensi }}</h4>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-widest border border-slate-200 text-slate-500 bg-white">
                                    {{ str_replace('_', ' ', $item->kategori) }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-widest border border-indigo-100 text-indigo-600 bg-indigo-50/50">
                                    Pertemuan #{{ $item->nomor_pertemuan }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium ml-1"><i class="far fa-clock"></i> {{ $item->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Bar Tengah --}}
                    <div class="w-full lg:w-[260px] shrink-0">
                        <div class="flex justify-between items-end mb-1.5">
                            <p class="text-[11px] font-medium text-slate-500">Tingkat Kehadiran <span class="font-bold text-slate-700 ml-1">{{ $totalHadir }}/{{ $totalPasien }}</span></p>
                            <p class="text-[12px] font-bold text-{{ $color }}-500">{{ $persentase }}%</p>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill bg-{{ $color }}-400" style="width: {{ $persentase }}%;"></div>
                        </div>
                    </div>

                    {{-- Aksi Kanan --}}
                    <div class="flex items-center justify-end gap-2 shrink-0 lg:ml-2">
                        <a href="{{ route('kader.absensi.show', $item->id) }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:border-indigo-300 hover:text-indigo-600 font-bold text-[10px] uppercase tracking-widest rounded-[10px] transition-colors flex items-center gap-2 shadow-sm">
                            Detail
                        </a>
                        <form action="{{ route('kader.absensi.destroy', $item->id) }}" method="POST" class="delete-form m-0">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-delete w-8 h-8 bg-white border border-slate-200 text-slate-400 hover:bg-rose-50 hover:text-rose-500 hover:border-rose-200 rounded-[10px] transition-colors flex items-center justify-center shadow-sm" title="Hapus Riwayat">
                                <i class="fas fa-trash-alt text-[12px]"></i>
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        {{-- EMPTY STATE MODERN --}}
        <div class="text-center py-16 px-4 bg-white rounded-[20px] border border-slate-200 shadow-sm animate-fade-in relative z-10">
            <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center text-slate-300 text-xl mx-auto mb-3">
                <i class="fas fa-folder-open"></i>
            </div>
            <h4 class="text-[14px] font-bold text-slate-700 uppercase tracking-widest mb-1">Arsip Tidak Ditemukan</h4>
            <p class="text-[12px] text-slate-500 font-medium">Sistem tidak menemukan log kehadiran yang cocok dengan saringan (filter) Anda.</p>
        </div>
    @endif

    {{-- PAGINASI --}}
    <div class="mt-8">
        {{ $riwayat->links() }}
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // === VALIDASI FILTER ===
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        let m = document.getElementById('valBulan').value;
        let y = document.getElementById('valTahun').value;
        
        if (m && y) {
            document.getElementById('hiddenBulan').value = y + '-' + m;
        } else if (m || y) {
            e.preventDefault();
            Swal.fire({
                title: 'Data Tidak Lengkap',
                html: 'Harap pilih <b>Bulan</b> dan <b>Tahun</b> sekaligus.',
                icon: 'info',
                confirmButtonText: 'Mengerti',
                buttonsStyling: false,
                backdrop: true, 
                customClass: { popup: 'nexus-modal', confirmButton: 'btn-swal-cancel' }
            });
        } else {
            document.getElementById('hiddenBulan').value = '';
        }
    });

    // === HAPUS DENGAN SWEETALERT (Aman & Elegan) ===
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Hapus Permanen?',
                html: 'Arsip presensi pada sesi tersebut beserta detail kehadirannya akan dihapus dari sistem.',
                icon: 'warning', 
                showCancelButton: true,
                buttonsStyling: false,
                reverseButtons: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal', 
                backdrop: true, 
                customClass: { 
                    popup: 'nexus-modal', 
                    confirmButton: 'btn-swal-danger',
                    cancelButton: 'btn-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // === NOTIFIKASI TOAST SUKSES (Mungil, Bersih, Anti-Bug Latar Hitam) ===
    @if(session('success'))
        const Toast = Swal.mixin({
            toast: true, 
            position: 'top-end', 
            showConfirmButton: false, 
            timer: 3000,
            backdrop: false, /* INI ADALAH KUNCI PENGHILANG BUG ABU-ABU */
            customClass: { popup: 'nexus-toast' }
        });
        
        Toast.fire({ 
            icon: 'success', 
            title: "{{ session('success') }}" 
        });
    @endif
</script>
@endpush
@endsection