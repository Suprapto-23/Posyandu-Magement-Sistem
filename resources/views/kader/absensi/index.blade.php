@extends('layouts.kader')

@section('title', 'Absensi Posyandu')
@section('page-name', 'Presensi Warga')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
    @keyframes slideUpFade { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .kat-tab { cursor:pointer; padding:0.75rem 1.5rem; border-radius:1rem; font-size:0.75rem; font-weight:900; letter-spacing:0.05em; text-transform:uppercase; border:2px solid #f1f5f9; background:#f8fafc; color:#64748b; transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display:inline-flex; align-items:center; justify-content:center; gap:0.5rem; white-space:nowrap; }
    .kat-tab:hover { border-color:#e2e8f0; background: #ffffff; color:#334155; transform: translateY(-2px); }
    .kat-tab.active-bayi      { background:#0ea5e9; color:white; border-color:#0ea5e9; box-shadow:0 10px 25px -5px rgba(14,165,233,0.4); transform: translateY(-2px); }
    .kat-tab.active-balita    { background:#8b5cf6; color:white; border-color:#8b5cf6; box-shadow:0 10px 25px -5px rgba(139,92,246,0.4); transform: translateY(-2px); }
    .kat-tab.active-remaja    { background:#f59e0b; color:white; border-color:#f59e0b; box-shadow:0 10px 25px -5px rgba(245,158,11,0.4); transform: translateY(-2px); }
    .kat-tab.active-lansia    { background:#10b981; color:white; border-color:#10b981; box-shadow:0 10px 25px -5px rgba(16,185,129,0.4); transform: translateY(-2px); }
    .kat-tab.active-ibu_hamil { background:#ec4899; color:white; border-color:#ec4899; box-shadow:0 10px 25px -5px rgba(236,72,153,0.4); transform: translateY(-2px); }

    .status-group { display: flex; background: #f1f5f9; border-radius: 0.75rem; padding: 4px; overflow: hidden; border: 1px solid #e2e8f0;}
    .status-label { flex: 1; text-align: center; padding: 0.6rem 0; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; cursor: pointer; transition: all 0.3s ease; color: #64748b; user-select: none; border-radius: 0.5rem; letter-spacing: 0.05em;}
    .status-label:hover { background: #e2e8f0; color: #334155; }
    
    .status-input:checked + .status-label.btn-hadir { background: #10b981; color: white; box-shadow: 0 2px 10px rgba(16,185,129,0.3); }
    .status-input:checked + .status-label.btn-absen { background: #f43f5e; color: white; box-shadow: 0 2px 10px rgba(244,63,94,0.3); }
    
    .ket-wrapper { display: grid; grid-template-rows: 0fr; transition: grid-template-rows 0.4s ease-out; }
    .ket-wrapper.open { grid-template-rows: 1fr; }
    .ket-inner { overflow: hidden; }
    
    .floating-dock { transition: transform 0.5s cubic-bezier(0.16,1,0.3,1), opacity 0.5s ease; transform: translateY(150%) scale(0.9); opacity: 0; }
    .floating-dock.show { transform: translateY(0) scale(1); opacity: 1; }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto animate-slide-up pb-40">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 text-center sm:text-left">
        <div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight font-poppins">Presensi Kehadiran</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Lakukan pencatatan kehadiran warga di Meja 1 Posyandu.</p>
        </div>
        <a href="{{ route('kader.absensi.riwayat') }}" class="inline-flex justify-center items-center gap-2 px-6 py-3.5 bg-white border border-slate-200 text-slate-600 font-extrabold text-sm rounded-xl hover:bg-slate-50 hover:text-indigo-600 shadow-[0_4px_15px_rgba(0,0,0,0.03)] transition-all w-full sm:w-auto uppercase tracking-widest">
            <i class="fas fa-history text-indigo-500"></i> Riwayat Arsip
        </a>
    </div>

    {{-- TAB KATEGORI (HORIZONTAL SCROLL) --}}
    <div class="flex overflow-x-auto pb-6 mb-2 gap-3 no-scrollbar">
        @php
            $katConfig = [
                'bayi'      => ['label'=>'Bayi', 'icon'=>'fa-baby', 'count' => $statsPerKategori['bayi']['total_pertemuan'] ?? 0],
                'balita'    => ['label'=>'Balita', 'icon'=>'fa-child', 'count' => $statsPerKategori['balita']['total_pertemuan'] ?? 0],
                'remaja'    => ['label'=>'Remaja', 'icon'=>'fa-user-graduate', 'count' => $statsPerKategori['remaja']['total_pertemuan'] ?? 0],
                'lansia'    => ['label'=>'Lansia', 'icon'=>'fa-user-clock', 'count' => $statsPerKategori['lansia']['total_pertemuan'] ?? 0],
                'ibu_hamil' => ['label'=>'Ibu Hamil', 'icon'=>'fa-female', 'count' => $statsPerKategori['ibu_hamil']['total_pertemuan'] ?? 0],
            ];
        @endphp
        @foreach($katConfig as $kat => $cfg)
        <a href="{{ route('kader.absensi.index') }}?kategori={{ $kat }}" class="kat-tab shrink-0 {{ $kategori === $kat ? 'active-'.$kat : '' }}">
            <i class="fas {{ $cfg['icon'] }} text-lg"></i> 
            <div class="text-left leading-none">
                <span class="block">{{ $cfg['label'] }}</span>
                <span class="text-[9px] font-bold opacity-80">{{ $cfg['count'] }} Sesi Tersimpan</span>
            </div>
        </a>
        @endforeach
    </div>

    {{-- NOTIFIKASI RESUME SESI (PENTING UNTUK UX) --}}
    @if($sesiHariIni)
    <div class="mb-8 bg-indigo-50 border border-indigo-200 rounded-[20px] p-4 sm:p-5 flex items-start gap-4 shadow-sm relative overflow-hidden">
        <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-500/10 rounded-bl-full blur-2xl"></div>
        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg shrink-0 border border-indigo-200 z-10"><i class="fas fa-pencil-alt"></i></div>
        <div class="z-10">
            <h4 class="text-sm font-black text-indigo-900 uppercase tracking-widest mb-1">Mode Melanjutkan Sesi</h4>
            <p class="text-xs font-medium text-indigo-700 leading-relaxed max-w-2xl">Anda sudah pernah menekan tombol Simpan pada hari ini. Data di bawah adalah status kehadiran terakhir yang Anda rekam. Anda bisa mengubah yang Absen menjadi Hadir jika ada warga yang baru datang menyusul.</p>
        </div>
    </div>
    @endif

    {{-- KONTROL SESI & FILTER --}}
    <div class="bg-white border border-slate-200/80 rounded-[24px] p-5 mb-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-5 relative z-10">
        <div class="flex items-center gap-4 w-full md:w-auto border-b md:border-b-0 border-slate-100 pb-4 md:pb-0">
            <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-500 border border-slate-200 flex items-center justify-center text-xl shrink-0"><i class="fas fa-calendar-day"></i></div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Jadwal Sesi Hari Ini</p>
                <p class="text-sm font-black text-slate-800">Pertemuan ke-{{ $pertemuanBerikutnya }} • <span class="text-indigo-600">{{ now()->translatedFormat('d F Y') }}</span></p>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            @if(!$pasiens->isEmpty())
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="button" onclick="tandaiHadirSemua()" class="flex-1 sm:flex-none px-4 py-3 bg-emerald-50 text-emerald-600 border border-emerald-100 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-emerald-500 hover:text-white transition-colors" title="Pintas: Tandai Hadir Semua">
                    <i class="fas fa-check-double"></i> Hadir Semua
                </button>
                <button type="button" onclick="resetPilihan()" class="flex-1 sm:flex-none px-4 py-3 bg-slate-50 text-slate-500 border border-slate-200 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-colors" title="Bersihkan Semua Pilihan">
                    <i class="fas fa-undo"></i> Reset Form
                </button>
            </div>
            @endif
            <div class="relative w-full sm:w-72">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" id="liveSearch" placeholder="Cari nama warga..." class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 bg-slate-50 transition-all">
            </div>
        </div>
    </div>

    {{-- LIST WARGA (KARTU MODERN) --}}
    <form action="{{ route('kader.absensi.store') }}" method="POST" id="formAbsensi">
        @csrf
        <input type="hidden" name="kategori" value="{{ $kategori }}">

        @if($pasiens->isEmpty())
            <div class="bg-white rounded-[32px] border border-slate-200 p-16 text-center shadow-sm">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-5 text-4xl shadow-inner border border-slate-100"><i class="fas fa-users-slash"></i></div>
                <p class="font-black text-slate-800 text-xl font-poppins">Master Data Kosong</p>
                <p class="text-sm font-medium text-slate-500 mt-2 max-w-sm mx-auto">Tidak ada warga yang terdaftar pada kategori <b>{{ strtoupper(str_replace('_', ' ', $kategori)) }}</b>. Silakan tambah data di menu Buku Induk.</p>
            </div>
        @else
            <div class="flex flex-col gap-4" id="pasienList">
                @foreach($pasiens as $index => $p)
                @php 
                    $statusHadir = isset($absensiData[$p->id]) ? $absensiData[$p->id]['hadir'] : null;
                    $alasan = $absensiData[$p->id]['keterangan'] ?? '';
                    
                    // State Visual Awal Saat Halaman Dimuat
                    $rowState = 'border-slate-200 bg-white'; 
                    if ($statusHadir === true) $rowState = 'border-emerald-300 bg-emerald-50/50 shadow-[0_4px_15px_rgba(16,185,129,0.05)]';
                    if ($statusHadir === false) $rowState = 'border-rose-300 bg-rose-50/50 shadow-[0_4px_15px_rgba(244,63,94,0.05)]';
                @endphp
                
                {{-- KARTU WARGA --}}
                <div class="pasien-card {{ $rowState }} rounded-[24px] p-5 sm:p-6 transition-all duration-300 flex flex-col sm:flex-row sm:items-start justify-between gap-6 border-2" data-search="{{ strtolower($p->nama_lengkap . ' ' . $p->nik) }}" id="card_{{ $p->id }}">
                    
                    {{-- Avatar & Identitas --}}
                    <div class="flex items-center gap-4 flex-1 mt-1">
                        <div class="w-12 h-12 rounded-[14px] bg-slate-100 text-slate-400 flex items-center justify-center font-black text-lg shrink-0 border border-slate-200 shadow-inner">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="font-black text-slate-800 text-[15px] leading-tight mb-1">{{ $p->nama_lengkap }}</p>
                            <p class="text-[11px] font-bold text-slate-500 font-mono">
                                @if($kategori === 'bayi' || $kategori === 'balita') <i class="fas fa-female text-slate-300 mr-1"></i> Ibu: {{ Str::limit($p->nama_ibu ?? '-', 20) }}
                                @elseif($kategori === 'ibu_hamil') <i class="fas fa-male text-slate-300 mr-1"></i> Suami: {{ Str::limit($p->nama_suami ?? '-', 20) }}
                                @else <i class="fas fa-id-card text-slate-300 mr-1"></i> NIK: {{ $p->nik ?? '-' }} @endif
                            </p>
                        </div>
                    </div>

                    {{-- Kontrol Aksi (Kanan) --}}
                    <div class="w-full sm:w-[280px] shrink-0 flex flex-col justify-start">
                        {{-- Radio Toggle Modern --}}
                        <div class="status-group shadow-sm">
                            <input type="radio" id="hadir_{{ $p->id }}" name="kehadiran[{{ $p->id }}]" value="1" class="status-input hidden radio-hadir" {{ $statusHadir === true ? 'checked' : '' }} onchange="updateCardUI(this, {{ $p->id }})">
                            <label for="hadir_{{ $p->id }}" class="status-label btn-hadir"><i class="fas fa-check mr-1"></i> Hadir</label>
                            
                            <input type="radio" id="absen_{{ $p->id }}" name="kehadiran[{{ $p->id }}]" value="0" class="status-input hidden radio-absen" {{ $statusHadir === false ? 'checked' : '' }} onchange="updateCardUI(this, {{ $p->id }})">
                            <label for="absen_{{ $p->id }}" class="status-label btn-absen"><i class="fas fa-times mr-1"></i> Absen</label>
                        </div>

                        {{-- Input Alasan (Smooth Collapse) --}}
                        <div id="ket_wrapper_{{ $p->id }}" class="ket-wrapper {{ $statusHadir === false ? 'open' : '' }}">
                            <div class="ket-inner pt-3">
                                <input type="text" name="keterangan[{{ $p->id }}]" id="ket_input_{{ $p->id }}" value="{{ $alasan }}" placeholder="Ketik alasan (Sakit, Pulang Kampung, dll)" class="w-full border-2 border-rose-200 rounded-xl px-4 py-3 text-[12px] font-bold focus:outline-none focus:border-rose-400 focus:ring-4 focus:ring-rose-50 bg-white text-rose-700 placeholder:text-rose-300 transition-all" {{ $statusHadir === false ? '' : 'disabled' }}>
                            </div>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
        @endif

        @if(!$pasiens->isEmpty())
        {{-- FLOATING DOCK (MELAYANG SEKSI DI BAWAH) --}}
        <div id="floatingDock" class="fixed bottom-6 left-0 right-0 z-50 flex justify-center px-4 floating-dock">
            <div class="bg-slate-900/95 backdrop-blur-xl border border-slate-700 p-3 sm:p-4 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.4)] flex flex-col sm:flex-row items-center justify-between gap-4 w-full max-w-4xl">
                
                {{-- Live Counters Indikator --}}
                <div class="flex items-center justify-center gap-6 sm:gap-8 w-full sm:w-auto px-6">
                    <div class="text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Hadir</p>
                        <p class="text-2xl font-black text-emerald-400 leading-none mt-0.5" id="countHadir">0</p>
                    </div>
                    <div class="w-px h-8 bg-slate-700"></div>
                    <div class="text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Absen</p>
                        <p class="text-2xl font-black text-rose-400 leading-none mt-0.5" id="countAbsen">0</p>
                    </div>
                    <div class="w-px h-8 bg-slate-700"></div>
                    <div class="text-center">
                        <p class="text-[9px] font-black text-amber-400 uppercase tracking-widest transition-colors" id="labelSisa">Belum Diisi</p>
                        <p class="text-2xl font-black text-amber-400 leading-none mt-0.5 animate-pulse transition-colors" id="countKosong">{{ $pasiens->count() }}</p>
                    </div>
                </div>
                
                {{-- Tombol Kunci Sesi --}}
                <button type="submit" id="btnSimpan" class="w-full sm:w-auto px-8 py-3.5 bg-slate-700 text-slate-400 font-black text-[13px] rounded-full transition-all flex items-center justify-center gap-2 uppercase tracking-widest disabled:opacity-80 disabled:cursor-not-allowed" disabled>
                    <i class="fas fa-lock"></i> Lengkapi Data
                </button>
            </div>
        </div>
        @endif
    </form>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const totalWarga = {{ $pasiens->count() }};
    
    document.addEventListener('DOMContentLoaded', function() {
        updateCounters();
        // Munculkan floating dock dengan animasi delay estetik
        setTimeout(() => document.getElementById('floatingDock')?.classList.add('show'), 400);
    });

    // 1. LIVE SEARCH (MENCARI NAMA PASIEN)
    document.getElementById('liveSearch')?.addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        document.querySelectorAll('.pasien-card').forEach(card => {
            if (card.getAttribute('data-search').includes(filter)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // 2. FUNGSI INTI: UPDATE UI SETIAP KALI KARTU DIKLIK
    function updateCardUI(radioElement, id) {
        const card = document.getElementById('card_' + id);
        const ketWrapper = document.getElementById('ket_wrapper_' + id);
        const ketInput = document.getElementById('ket_input_' + id);
        const isHadir = radioElement.value === "1";

        // Bersihkan State Warna Sebelumnya
        card.classList.remove('border-slate-200', 'bg-white', 'border-emerald-300', 'bg-emerald-50/50', 'shadow-[0_4px_15px_rgba(16,185,129,0.05)]', 'border-rose-300', 'bg-rose-50/50', 'shadow-[0_4px_15px_rgba(244,63,94,0.05)]');
        
        if (isHadir) {
            card.classList.add('border-emerald-300', 'bg-emerald-50/50', 'shadow-[0_4px_15px_rgba(16,185,129,0.05)]');
            ketWrapper.classList.remove('open');
            ketInput.disabled = true;
            ketInput.value = ''; 
        } else {
            card.classList.add('border-rose-300', 'bg-rose-50/50', 'shadow-[0_4px_15px_rgba(244,63,94,0.05)]');
            ketWrapper.classList.add('open');
            ketInput.disabled = false;
            setTimeout(() => ketInput.focus(), 300);
        }

        updateCounters();
    }

    // 3. FUNGSI INTI: RESET CARD UI KE PUTIH POLOS
    function resetCardUI(id) {
        const card = document.getElementById('card_' + id);
        const ketWrapper = document.getElementById('ket_wrapper_' + id);
        const ketInput = document.getElementById('ket_input_' + id);

        card.classList.remove('border-emerald-300', 'bg-emerald-50/50', 'shadow-[0_4px_15px_rgba(16,185,129,0.05)]', 'border-rose-300', 'bg-rose-50/50', 'shadow-[0_4px_15px_rgba(244,63,94,0.05)]');
        card.classList.add('border-slate-200', 'bg-white');

        ketWrapper.classList.remove('open');
        ketInput.disabled = true;
        ketInput.value = '';
    }

    // 4. ENGINE COUNTER & STATUS TOMBOL DOCK
    function updateCounters() {
        const hadir = document.querySelectorAll('.radio-hadir:checked').length;
        const absen = document.querySelectorAll('.radio-absen:checked').length;
        const kosong = totalWarga - (hadir + absen);

        document.getElementById('countHadir').textContent = hadir;
        document.getElementById('countAbsen').textContent = absen;
        const countSisa = document.getElementById('countKosong');
        countSisa.textContent = kosong;

        const btnSimpan = document.getElementById('btnSimpan');
        const labelSisa = document.getElementById('labelSisa');

        if (kosong === 0) {
            btnSimpan.disabled = false;
            btnSimpan.innerHTML = '<i class="fas fa-save text-lg"></i> Perbarui Sesi';
            btnSimpan.className = "w-full sm:w-auto px-10 py-3.5 bg-emerald-500 text-white font-black text-[13px] rounded-full hover:bg-emerald-400 shadow-[0_0_20px_rgba(16,185,129,0.4)] transition-all flex items-center justify-center gap-2 uppercase tracking-widest hover:-translate-y-1";
            
            labelSisa.textContent = "Data Selesai";
            labelSisa.classList.replace('text-amber-400', 'text-slate-500');
            countSisa.classList.replace('text-amber-400', 'text-slate-500');
            countSisa.classList.remove('animate-pulse');
        } else {
            btnSimpan.disabled = true;
            btnSimpan.innerHTML = '<i class="fas fa-lock"></i> Lengkapi ' + kosong + ' Data Lagi';
            btnSimpan.className = "w-full sm:w-auto px-8 py-3.5 bg-slate-700 text-slate-400 font-black text-[13px] rounded-full transition-all flex items-center justify-center gap-2 uppercase tracking-widest cursor-not-allowed";
            
            labelSisa.textContent = "Belum Diisi";
            labelSisa.classList.replace('text-slate-500', 'text-amber-400');
            countSisa.classList.replace('text-slate-500', 'text-amber-400');
            countSisa.classList.add('animate-pulse');
        }
    }

    // 5. FITUR: HADIR SEMUA (SHORTCUT)
    function tandaiHadirSemua() {
        Swal.fire({
            title: 'Tandai Hadir Semua?',
            html: '<p class="text-sm font-medium text-slate-500 mt-2">Gunakan fitur ini untuk mempercepat presensi. Pastikan Anda <b>mengubah manual</b> status menjadi Absen bagi mereka yang tidak datang.</p>',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-[24px]' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelectorAll('.radio-hadir').forEach(radio => {
                    if (!radio.checked) {
                        radio.checked = true;
                        let id = radio.id.split('_')[1]; 
                        updateCardUI(radio, id);
                    }
                });
            }
        });
    }

    // 6. FITUR: RESET PILIHAN (WIPER)
    function resetPilihan() {
        Swal.fire({
            title: 'Reset Semua Pilihan?',
            text: 'Ini akan mengosongkan semua status (Hadir/Absen) dan alasan yang sudah Anda pilih di halaman ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Kosongkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-[24px]' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelectorAll('.status-input:checked').forEach(radio => {
                    radio.checked = false;
                    let id = radio.id.split('_')[1];
                    resetCardUI(id);
                });
                updateCounters();
                const Toast = Swal.mixin({ toast: true, position: "top-end", showConfirmButton: false, timer: 2000, timerProgressBar: true });
                Toast.fire({ icon: "success", title: "Formulir dikosongkan." });
            }
        });
    }

    // 7. UX SUBMIT (TRANSACTION LOCK)
    document.getElementById('formAbsensi')?.addEventListener('submit', function(e) {
        if(document.querySelectorAll('.radio-hadir:checked').length + document.querySelectorAll('.radio-absen:checked').length < totalWarga) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Aksi Ditolak', text: 'Anda belum mengisi presensi untuk seluruh warga. Cek angka "Belum Diisi" di panel bawah.' });
            return;
        }

        const btn = document.getElementById('btnSimpan');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Memproses Ke Database...';
        btn.classList.add('opacity-75', 'cursor-wait');
        
        Swal.fire({
            title: 'Menyimpan Pembaruan...',
            text: 'Data absensi hari ini sedang diperbarui ke server utama.',
            allowOutsideClick: false, showConfirmButton: false,
            willOpen: () => { Swal.showLoading(); }
        });
    });
</script>
@endpush
@endsection