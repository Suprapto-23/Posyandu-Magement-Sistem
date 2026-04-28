@extends('layouts.kader')
@section('title', 'Riwayat Absensi Posyandu')
@section('page-name', 'Arsip Sesi Absensi')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* Animasi Dasar Halaman */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* Animasi Bertingkat (Staggered) untuk Kartu */
    .stagger-1 { animation-delay: 0.1s; }
    .stagger-2 { animation-delay: 0.2s; }
    .stagger-3 { animation-delay: 0.3s; }
    .stagger-4 { animation-delay: 0.4s; }
    .stagger-5 { animation-delay: 0.5s; }

    /* Custom Dropdown Styling (Konsisten untuk Semua Filter) */
    .custom-dropdown-btn { 
        appearance: none; -webkit-appearance: none;
        background-color: #ffffff; border: 2px solid #e2e8f0; 
        transition: all 0.3s ease; width: 100%; font-family: inherit;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 1.25rem center; background-size: 1rem;
    }
    .custom-dropdown-btn:focus, .custom-dropdown-btn.active { 
        border-color: #6366f1; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.15); outline: none;
    }

    /* Scrollbar Estetik untuk Custom Dropdown */
    .custom-scroll::-webkit-scrollbar { width: 6px; }
    .custom-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Kartu Riwayat Modern (Card Row) */
    .history-card {
        background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden;
    }
    .history-card:hover {
        transform: translateY(-4px); border-color: #c7d2fe;
        box-shadow: 0 15px 35px -10px rgba(79, 70, 229, 0.15); z-index: 10;
    }

    /* Desain Kalender Robek (Tear-off Calendar) */
    .calendar-block {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        border-radius: 16px; overflow: hidden; text-align: center; color: white;
        box-shadow: 0 8px 15px -5px rgba(79, 70, 229, 0.3);
    }
    .calendar-header { background: rgba(0,0,0,0.2); font-size: 10px; font-weight: 900; letter-spacing: 2px; padding: 4px 0; text-transform: uppercase; }
    .calendar-body { padding: 10px; }
     /* Keyframes Animasi SVG Kosong */
    @keyframes float-folder {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(3deg); }
    }
    @keyframes search-glass {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        50% { transform: translate(-10px, 10px) scale(1.1); }
    }
    .anim-folder { animation: float-folder 4s ease-in-out infinite; }
    .anim-glass { animation: search-glass 3s ease-in-out infinite; transform-origin: center; }
</style>
@endpush

@section('content')
<div class="max-w-[1200px] mx-auto animate-slide-up pb-12">

    {{-- HEADER EKSKLUSIF --}}
    <div class="flex items-center gap-5 mb-8 mt-2">
        <div class="w-16 h-16 rounded-[24px] bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center text-3xl shadow-lg shadow-indigo-200 shrink-0">
            <i class="fas fa-folder-open"></i>
        </div>
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins mb-1">Arsip Sesi Presensi</h1>
            <p class="text-slate-500 font-medium text-[14px]">Telusuri dan kelola seluruh riwayat pendataan kehadiran warga Posyandu.</p>
        </div>
    </div>

    {{-- PANEL FILTER PENCARIAN KONSISTEN (SEMUA CUSTOM DROPDOWN) --}}
    @php
        // Ekstrak Data Filter
        $reqKategori = request('kategori', '');
        $reqBulanStr = request('bulan');
        $selTahun = $reqBulanStr ? substr($reqBulanStr, 0, 4) : '';
        $selBulan = $reqBulanStr ? substr($reqBulanStr, 5, 2) : '';
        $tahunSaatIni = date('Y');

        // Mapping Data untuk Tampilan Default
        $mapKategori = ['' => '-- Semua Kategori --', 'bayi' => 'Bayi (0-11 Bulan)', 'balita' => 'Balita (1-5 Tahun)', 'ibu_hamil' => 'Ibu Hamil', 'remaja' => 'Remaja', 'lansia' => 'Lansia'];
        $mapBulan = ['' => '-- Bulan --', '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
    @endphp

    <div class="bg-white rounded-[32px] border border-slate-200 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.05)] p-6 md:p-8 mb-10 relative z-20">
        {{-- Background Blur Terisolasi agar dropdown aman --}}
        <div class="absolute inset-0 rounded-[32px] overflow-hidden pointer-events-none -z-10">
            <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-50 rounded-full blur-[80px] transform translate-x-1/2 -translate-y-1/2"></div>
        </div>
        
        <h3 class="font-black text-slate-800 text-[13px] uppercase tracking-widest mb-5 flex items-center gap-2 relative z-10">
            <i class="fas fa-filter text-indigo-500"></i> Saring Data Spesifik
        </h3>

        <form id="filterForm" action="{{ route('kader.absensi.riwayat') }}" method="GET" class="flex flex-col md:flex-row items-end gap-5 relative z-10">
            
            {{-- Input Hidden untuk Nilai Form --}}
            <input type="hidden" name="kategori" id="valKategori" value="{{ $reqKategori }}">
            <input type="hidden" id="valBulan" value="{{ $selBulan }}">
            <input type="hidden" id="valTahun" value="{{ $selTahun }}">
            <input type="hidden" name="bulan" id="hiddenBulan" value="{{ request('bulan') }}">

            {{-- 1. KATEGORI (CUSTOM DROPDOWN) --}}
            <div class="w-full md:w-[35%] relative">
                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Kategori Sasaran</label>
                <button type="button" id="btnKategori" class="custom-dropdown-btn flex items-center justify-between text-sm font-bold text-slate-700 rounded-[16px] pl-5 pr-10 py-4 cursor-pointer w-full text-left">
                    <span id="dispKategori">{{ $mapKategori[$reqKategori] ?? '-- Semua Kategori --' }}</span>
                </button>
                <div id="menuKategori" class="custom-dropdown-menu hidden absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-[16px] shadow-[0_20px_50px_-10px_rgba(0,0,0,0.15)] z-[100] max-h-[240px] overflow-y-auto custom-scroll">
                    <ul class="py-2">
                        @foreach($mapKategori as $val => $label)
                        <li class="dropdown-option px-5 py-3 hover:bg-indigo-50 hover:text-indigo-600 cursor-pointer text-sm font-bold transition-colors {{ $reqKategori == $val ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600' }}" data-target="valKategori" data-display="dispKategori" data-value="{{ $val }}">{{ $label }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- 2. BULAN (CUSTOM DROPDOWN) --}}
            <div class="w-full md:w-[25%] relative">
                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Pilih Bulan</label>
                <button type="button" id="btnBulan" class="custom-dropdown-btn flex items-center justify-between text-sm font-bold text-slate-700 rounded-[16px] pl-5 pr-10 py-4 cursor-pointer w-full text-left">
                    <span id="dispBulan">{{ $mapBulan[$selBulan] ?? '-- Bulan --' }}</span>
                </button>
                <div id="menuBulan" class="custom-dropdown-menu hidden absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-[16px] shadow-[0_20px_50px_-10px_rgba(0,0,0,0.15)] z-[100] max-h-[240px] overflow-y-auto custom-scroll">
                    <ul class="py-2">
                        @foreach($mapBulan as $val => $label)
                        <li class="dropdown-option px-5 py-3 hover:bg-indigo-50 hover:text-indigo-600 cursor-pointer text-sm font-bold transition-colors {{ $selBulan == $val ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600' }}" data-target="valBulan" data-display="dispBulan" data-value="{{ $val }}">{{ $label }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- 3. TAHUN (CUSTOM DROPDOWN) --}}
            <div class="w-full md:w-[20%] relative">
                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Pilih Tahun</label>
                <button type="button" id="btnTahun" class="custom-dropdown-btn flex items-center justify-between text-sm font-bold text-slate-700 rounded-[16px] pl-5 pr-10 py-4 cursor-pointer w-full text-left">
                    <span id="dispTahun">{{ $selTahun ?: '-- Tahun --' }}</span>
                </button>
                <div id="menuTahun" class="custom-dropdown-menu hidden absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-[16px] shadow-[0_20px_50px_-10px_rgba(0,0,0,0.15)] z-[100] max-h-[240px] overflow-y-auto custom-scroll">
                    <ul class="py-2">
                        <li class="dropdown-option px-5 py-3 hover:bg-indigo-50 hover:text-indigo-600 cursor-pointer text-sm font-bold transition-colors {{ $selTahun == '' ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600' }}" data-target="valTahun" data-display="dispTahun" data-value="">-- Tahun --</li>
                        {{-- Logika Tahun: Hanya dari tahun saat ini mundur ke 2000 --}}
                        @for($y = $tahunSaatIni; $y >= 2000; $y--)
                            <li class="dropdown-option px-5 py-3 hover:bg-indigo-50 hover:text-indigo-600 cursor-pointer text-sm font-bold transition-colors {{ $selTahun == $y ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600' }}" data-target="valTahun" data-display="dispTahun" data-value="{{ $y }}">{{ $y }}</li>
                        @endfor
                    </ul>
                </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="w-full md:w-[20%] flex items-center gap-3">
                <button type="submit" class="flex-1 bg-slate-900 text-white font-black text-[13px] uppercase tracking-widest rounded-[16px] py-4 hover:bg-indigo-600 transition-colors shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Cari
                </button>
                @if(request('kategori') || request('bulan'))
                    <a href="{{ route('kader.absensi.riwayat') }}" class="w-[52px] h-[52px] shrink-0 bg-rose-50 text-rose-500 font-black rounded-[16px] flex items-center justify-center hover:bg-rose-500 hover:text-white transition-colors border border-rose-100" title="Bersihkan Filter">
                        <i class="fas fa-times text-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- DAFTAR RIWAYAT SESI --}}
    <div class="space-y-4 relative z-0">
        @forelse($riwayat as $index => $item)
            @php
                $totalPasien = $item->details->count();
                $totalHadir  = $item->details->where('hadir', true)->count();
                $persentase  = $totalPasien > 0 ? round(($totalHadir / $totalPasien) * 100) : 0;
                
                $color = 'emerald'; 
                if($persentase < 50) $color = 'rose'; 
                elseif($persentase < 75) $color = 'amber'; 

                $tgl = \Carbon\Carbon::parse($item->tanggal_posyandu);
                $staggerClass = 'stagger-' . (($index % 5) + 1);
            @endphp

            <div class="history-card {{ $staggerClass }} animate-slide-up p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-6" style="opacity: 0;">
                
                <div class="flex items-center gap-5 sm:gap-6 w-full lg:w-5/12">
                    <div class="calendar-block w-20 shrink-0">
                        <div class="calendar-header">{{ $tgl->translatedFormat('M Y') }}</div>
                        <div class="calendar-body bg-white text-slate-800">
                            <span class="block text-2xl font-black leading-none mb-1">{{ $tgl->format('d') }}</span>
                            <span class="block text-[10px] font-black text-slate-400 uppercase">{{ $tgl->translatedFormat('l') }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-2.5 py-1 rounded bg-slate-800 text-white text-[10px] font-black uppercase tracking-widest shadow-sm">
                                {{ str_replace('_', ' ', $item->kategori) }}
                            </span>
                            <span class="px-2.5 py-1 rounded bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-black uppercase tracking-widest">
                                Sesi #{{ $item->nomor_pertemuan }}
                            </span>
                        </div>
                        <h4 class="text-[16px] font-black text-slate-800 font-poppins mb-1">{{ $item->kode_absensi }}</h4>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest"><i class="far fa-clock mr-1"></i> Direkam pukul {{ $item->created_at->format('H:i') }} WIB</p>
                    </div>
                </div>

                <div class="w-full lg:w-4/12 px-2">
                    <div class="flex justify-between items-end mb-2">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Tingkat Kehadiran</p>
                            <p class="text-[14px] font-black text-slate-700">{{ $totalHadir }} dari {{ $totalPasien }} Hadir</p>
                        </div>
                        <div class="text-2xl font-black text-{{ $color }}-500">{{ $persentase }}%</div>
                    </div>
                    <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                        <div class="h-full bg-{{ $color }}-500 rounded-full" style="width: {{ $persentase }}%;"></div>
                    </div>
                </div>

                <div class="w-full lg:w-3/12 flex items-center justify-start lg:justify-end gap-3 mt-2 lg:mt-0">
                    <a href="{{ route('kader.absensi.show', $item->id) }}" class="flex-1 lg:flex-none px-6 py-3.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-100 font-black text-[11px] uppercase tracking-widest rounded-[16px] transition-colors flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-external-link-alt"></i> Detail
                    </a>
                    <form action="{{ route('kader.absensi.destroy', $item->id) }}" method="POST" class="delete-form flex-none">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn-delete w-[48px] h-[48px] bg-white border-2 border-rose-100 text-rose-500 hover:bg-rose-500 hover:text-white hover:border-rose-500 font-black rounded-[16px] transition-colors flex items-center justify-center shadow-sm" title="Hapus Data Ini">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
       @empty
            {{-- ANIMASI SVG MURNI (ANTI ERROR, 100% PASTI MUNCUL DI LOCALHOST/HOSTING) --}}
            <div class="text-center py-16 px-4 bg-white rounded-[32px] border-2 border-dashed border-slate-200 animate-slide-up overflow-hidden">
                <div class="relative w-48 h-48 mx-auto mb-2 flex items-center justify-center">
                    {{-- Awan Latar --}}
                    <div class="absolute inset-0 bg-indigo-50 rounded-full blur-2xl opacity-60"></div>
                    
                    {{-- Gambar Folder Melayang --}}
                    <svg class="anim-folder w-32 h-32 relative z-10 drop-shadow-xl text-indigo-100" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h5.454a3 3 0 0 1 2.121.879l1.65 1.65a3 3 0 0 0 2.122.879H19.5a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25H3.375A2.25 2.25 0 0 1 1.125 20.625V7.125Z" fill="#818CF8"/>
                        <path d="M3.375 7.5h5.454c.4 0 .783.159 1.06.442l1.65 1.65c.278.283.66.442 1.061.442H19.5a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-.75.75H3.375a.75.75 0 0 1-.75-.75v-9c0-.414.336-.75.75-.75Z" fill="#E0E7FF"/>
                    </svg>
                    
                    {{-- Gambar Kaca Pembesar Melayang --}}
                    <svg class="anim-glass absolute w-16 h-16 right-4 bottom-4 z-20 drop-shadow-md text-amber-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"></path>
                    </svg>
                </div>
                
                <h4 class="text-[17px] font-black text-slate-800 uppercase tracking-widest mb-2 relative z-10">Ups! Datanya Hilang?</h4>
                <p class="text-[14px] text-slate-500 font-medium max-w-sm mx-auto relative z-10">Kami sudah mencari ke seluruh sudut sistem, tapi tidak ada riwayat kehadiran yang sesuai dengan filter Anda.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $riwayat->links() }}
    </div>

</div>

@push('scripts')
{{-- Script Lottie Player Bawaan (Paling Kompatibel dengan Localhost) --}}
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // === LOGIKA SEMUA CUSTOM DROPDOWN ===
    function setupCustomDropdown(btnId, menuId) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        
        // Toggle menu saat tombol diklik
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            // Tutup semua menu lain dulu
            document.querySelectorAll('.custom-dropdown-menu').forEach(m => {
                if(m !== menu) m.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
            btn.classList.toggle('border-indigo-500'); // Efek focus buatan
        });
    }

    setupCustomDropdown('btnKategori', 'menuKategori');
    setupCustomDropdown('btnBulan', 'menuBulan');
    setupCustomDropdown('btnTahun', 'menuTahun');

    // Tutup menu jika user klik di luar kotak
    document.addEventListener('click', () => {
        document.querySelectorAll('.custom-dropdown-menu').forEach(m => m.classList.add('hidden'));
        document.querySelectorAll('.custom-dropdown-btn').forEach(b => b.classList.remove('border-indigo-500'));
    });

    // Proses klik pada opsi dropdown
    document.querySelectorAll('.dropdown-option').forEach(option => {
        option.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const targetInputId = this.getAttribute('data-target');
            const displaySpanId = this.getAttribute('data-display');
            const val = this.getAttribute('data-value');
            const text = this.innerText;
            
            // Set nilai input hidden dan ganti teks di tombol
            document.getElementById(targetInputId).value = val;
            document.getElementById(displaySpanId).innerText = text;
            
            // Tutup menu
            this.closest('.custom-dropdown-menu').classList.add('hidden');
            document.getElementById('btn' + targetInputId.replace('val','')).classList.remove('border-indigo-500');

            // Hapus warna aktif dari saudara-saudaranya, lalu berikan ke opsi ini
            const siblings = this.closest('ul').querySelectorAll('.dropdown-option');
            siblings.forEach(sib => {
                sib.classList.remove('bg-indigo-50', 'text-indigo-600');
                sib.classList.add('text-slate-600');
            });
            this.classList.remove('text-slate-600');
            this.classList.add('bg-indigo-50', 'text-indigo-600');
        });
    });

    // === LOGIKA SUBMIT PENCARIAN (GABUNG BULAN & TAHUN) ===
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        let m = document.getElementById('valBulan').value;
        let y = document.getElementById('valTahun').value;
        
        if (m && y) {
            document.getElementById('hiddenBulan').value = y + '-' + m;
        } else if (m || y) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning', title: 'Filter Tidak Lengkap',
                text: 'Harap pilih Bulan DAN Tahun sekaligus untuk melakukan pencarian spesifik.',
                confirmButtonColor: '#4f46e5', customClass: { popup: 'rounded-[24px]' }
            });
        } else {
            document.getElementById('hiddenBulan').value = '';
        }
    });

    // === FITUR HAPUS SWEETALERT ===
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Hapus Arsip Sesi?',
                html: '<p class="text-sm text-slate-500">Anda akan menghapus secara permanen data kehadiran pada pertemuan ini. Data yang dihapus tidak dapat dikembalikan.</p>',
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#f43f5e', cancelButtonColor: '#94a3b8',  
                confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus',
                cancelButtonText: 'Batal', customClass: { popup: 'rounded-[24px]' },
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Notifikasi Selesai
    @if(session('success'))
        Swal.fire({
            icon: 'success', title: 'Selesai!', text: "{{ session('success') }}",
            confirmButtonColor: '#10b981', timer: 3000, showConfirmButton: false,
            customClass: { popup: 'rounded-[24px]' }
        });
    @endif
</script>
@endpush
@endsection