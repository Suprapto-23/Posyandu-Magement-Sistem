@extends('layouts.kader')
@section('title', 'Presensi Kehadiran Warga')
@section('page-name', 'Buku Kehadiran (Meja 1)')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Latar Belakang Gradasi Atas (Hero Mesh) */
    .hero-gradient {
        position: absolute; top: 0; left: 0; right: 0; height: 350px; z-index: -1;
        background: radial-gradient(circle at 15% 50%, rgba(99, 102, 241, 0.08), transparent 50%),
                    radial-gradient(circle at 85% 30%, rgba(16, 185, 129, 0.05), transparent 50%);
        pointer-events: none;
    }

    /* RADIO BUTTON CUSTOM (Gaya Toggle Modern) */
    .radio-hidden { display: none; }
    .label-btn { 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        cursor: pointer; position: relative; overflow: hidden;
    }
    
    /* State: Unchecked */
    .label-hadir { background-color: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0; }
    .label-absen { background-color: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0; }

    /* State: Checked (Hadir) */
    .radio-hadir:checked + .label-hadir { 
        background-color: #10b981; color: white; border-color: #059669; 
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25); transform: scale(1.03); z-index: 10; 
    }
    
    /* State: Checked (Absen) */
    .radio-absen:checked + .label-absen { 
        background-color: #f43f5e; color: white; border-color: #e11d48; 
        box-shadow: 0 4px 12px rgba(244, 63, 94, 0.25); transform: scale(1.03); z-index: 10; 
    }

    /* Row Hover Effect */
    .warga-row { border: 1px solid transparent; transition: all 0.2s; border-bottom: 1px solid #f1f5f9; }
    .warga-row:hover { background-color: #ffffff; border-color: #e0e7ff; box-shadow: 0 10px 25px -10px rgba(79, 70, 229, 0.1); border-radius: 16px; z-index: 5; position: relative; }
    
    /* Kotak Keterangan Animasi Pop-out */
    .ket-box { max-height: 0; opacity: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); transform: translateY(-10px); }
    .ket-box.open { max-height: 80px; opacity: 1; margin-top: 0.75rem; transform: translateY(0); }
</style>
@endpush

@section('content')
<div class="hero-gradient"></div>

<div class="max-w-[1200px] mx-auto animate-slide-up pb-12 relative z-10">

    {{-- HEADER EKSKLUSIF --}}
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 mb-8 mt-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-indigo-100 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-xl mb-3 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span> Meja 1: Pendaftaran Sesi
            </div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight font-poppins mb-2">Presensi Kehadiran</h1>
            <p class="text-slate-500 font-medium text-[14px]">Sistem cerdas rekam kehadiran warga untuk optimalisasi data Posyandu.</p>
        </div>

        <div class="flex items-center gap-4 bg-white/80 backdrop-blur-md px-6 py-4 rounded-[24px] border border-white shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500 text-xl shrink-0"><i class="far fa-calendar-check"></i></div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Tanggal Operasional</p>
                <p class="font-black text-slate-800 text-[15px]">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- NAVIGASI KATEGORI (MODERN PILLS) --}}
    <div class="flex flex-wrap gap-2 mb-8">
        @php
            $tabs = [
                'bayi'      => ['label' => 'Bayi (0-11 Bln)', 'icon' => 'fa-baby-carriage', 'color' => 'sky'],
                'balita'    => ['label' => 'Balita (1-5 Thn)', 'icon' => 'fa-child', 'color' => 'indigo'],
                'ibu_hamil' => ['label' => 'Ibu Hamil', 'icon' => 'fa-female', 'color' => 'pink'],
                'remaja'    => ['label' => 'Remaja', 'icon' => 'fa-user-graduate', 'color' => 'blue'],
                'lansia'    => ['label' => 'Lansia', 'icon' => 'fa-wheelchair', 'color' => 'emerald'],
            ];
        @endphp

        @foreach($tabs as $key => $tab)
            @php $isActive = $kategori === $key; @endphp
            <a href="{{ route('kader.absensi.index', ['kategori' => $key]) }}" 
               class="spa-route relative flex items-center gap-2 px-5 py-3 rounded-[16px] text-[13px] font-black tracking-wide transition-all border-2
               {{ $isActive ? "bg-white border-{$tab['color']}-500 text-{$tab['color']}-600 shadow-md transform -translate-y-1" : "bg-white/50 border-transparent text-slate-500 hover:bg-white hover:text-slate-800" }}">
                <i class="fas {{ $tab['icon'] }} {{ $isActive ? "text-{$tab['color']}-500" : "opacity-40" }} text-lg"></i> 
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    {{-- ALERT MODE EDIT (Jika data hari ini sudah ada) --}}
    @if($sesiHariIni)
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 p-5 rounded-[24px] mb-8 flex items-start gap-4 shadow-sm">
            <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl shrink-0"><i class="fas fa-magic"></i></div>
            <div>
                <h4 class="text-[14px] font-black text-amber-900 mb-1">Mode Koreksi Data Aktif</h4>
                <p class="text-[13px] text-amber-700 font-medium">Sistem memuat daftar hadir <b>{{ strtoupper(str_replace('_', ' ', $kategori)) }}</b> yang sebelumnya telah Anda simpan hari ini. Anda dapat mengedit status kehadiran mereka.</p>
            </div>
        </div>
    @endif

    {{-- FORM UTAMA ABSENSI --}}
    <form action="{{ route('kader.absensi.store') }}" method="POST" id="formAbsensi">
        @csrf
        <input type="hidden" name="kategori" value="{{ $kategori }}">

        <div class="bg-white rounded-[32px] border border-slate-200 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)] overflow-hidden mb-8">
            
            {{-- HEADER TABEL & BULK ACTION --}}
            <div class="px-6 py-5 sm:px-8 sm:py-6 bg-slate-50/80 border-b border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-lg"><i class="fas fa-list-check"></i></div>
                    <div>
                        <h3 class="font-black text-slate-800 text-[14px] uppercase tracking-widest">Daftar Panggilan Sesi</h3>
                        <p class="text-[11px] font-bold text-slate-400 mt-0.5">Total: {{ count($pasiens) }} Sasaran Terdaftar</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    {{-- FITUR BARU: TOMBOL HADIR SEMUA --}}
                    @if(count($pasiens) > 0)
                    <button type="button" id="btnHadirSemua" class="w-full sm:w-auto px-5 py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-500 hover:text-white font-black text-[11px] uppercase tracking-widest rounded-[12px] transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-check-double"></i> Tandai Hadir Semua
                    </button>
                    @endif

                    {{-- PENCARIAN --}}
                    <div class="relative w-full sm:w-64 group">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                        <input type="text" id="searchInput" placeholder="Cari warga / NIK..." class="w-full bg-white border border-slate-200 text-sm font-bold text-slate-700 rounded-[14px] pl-10 pr-4 py-2.5 outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all shadow-sm">
                    </div>
                </div>
            </div>

            {{-- LIST WARGA --}}
            <div class="px-2 py-2" id="wargaList">
                @forelse($pasiens as $index => $p)
                    @php
                        $statusHadir = $absensiData[$p->id]['hadir'] ?? null;
                        $keterangan  = $absensiData[$p->id]['keterangan'] ?? '';
                    @endphp
                    <div class="warga-row p-4 sm:p-5 mx-2 my-1 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                        
                        {{-- Identitas Warga --}}
                        <div class="flex items-center gap-4 sm:gap-5 w-full lg:w-1/2">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 text-slate-400 flex items-center justify-center font-black text-lg shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <div class="truncate">
                                <h4 class="warga-nama text-[15px] sm:text-[16px] font-black text-slate-800 font-poppins mb-0.5 truncate">{{ $p->nama_lengkap }}</h4>
                                <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 tracking-widest uppercase"><i class="far fa-id-card mr-1"></i> NIK: <span class="warga-nik text-slate-500">{{ $p->nik ?? '-' }}</span></p>
                            </div>
                        </div>

                        {{-- Panel Tombol Toggle & Keterangan --}}
                        <div class="flex flex-col lg:items-end w-full lg:w-auto">
                            <div class="flex gap-2 w-full lg:w-auto bg-slate-50 p-1 rounded-[16px] border border-slate-100">
                                {{-- Tombol Hadir --}}
                                <input type="radio" name="kehadiran[{{ $p->id }}]" id="hadir_{{ $p->id }}" value="1" class="radio-hidden radio-hadir logic-radio" data-id="{{ $p->id }}" {{ $statusHadir === true ? 'checked' : '' }} required>
                                <label for="hadir_{{ $p->id }}" class="label-btn label-hadir flex-1 lg:w-28 flex justify-center items-center gap-1.5 py-2 px-4 rounded-[12px] text-[11px] sm:text-[12px] font-black tracking-widest uppercase">
                                    <i class="fas fa-check"></i> Hadir
                                </label>
                                
                                {{-- Tombol Absen --}}
                                <input type="radio" name="kehadiran[{{ $p->id }}]" id="absen_{{ $p->id }}" value="0" class="radio-hidden radio-absen logic-radio" data-id="{{ $p->id }}" {{ $statusHadir === false ? 'checked' : '' }} required>
                                <label for="absen_{{ $p->id }}" class="label-btn label-absen flex-1 lg:w-28 flex justify-center items-center gap-1.5 py-2 px-4 rounded-[12px] text-[11px] sm:text-[12px] font-black tracking-widest uppercase">
                                    <i class="fas fa-times"></i> Absen
                                </label>
                            </div>

                            {{-- Kotak Keterangan (Muncul Indah di bawah tombol) --}}
                            <div id="ketBox_{{ $p->id }}" class="ket-box {{ $statusHadir === false ? 'open' : '' }} w-full lg:w-[232px]">
                                <div class="relative">
                                    <div class="absolute -top-2 right-12 w-4 h-4 bg-rose-50 transform rotate-45 border-t border-l border-rose-200 hidden lg:block"></div>
                                    <input type="text" name="keterangan[{{ $p->id }}]" value="{{ $keterangan }}" placeholder="Keterangan (Sakit/Izin).." class="w-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold px-4 py-2.5 rounded-[12px] outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-100 placeholder:text-rose-300 shadow-inner relative z-10">
                                </div>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="text-center py-24 px-4">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-5xl mx-auto mb-5 border-2 border-dashed border-slate-200"><i class="fas fa-folder-open"></i></div>
                        <h4 class="text-[16px] font-black text-slate-800 uppercase tracking-widest mb-2">Data Warga Kosong</h4>
                        <p class="text-[14px] text-slate-500 font-medium max-w-sm mx-auto">Belum ada warga yang terdaftar pada kategori ini. Silakan tambahkan data warga terlebih dahulu.</p>
                    </div>
                @endforelse
            </div>

            {{-- ACTION BAR BAWAH (Menyatu Indah dengan Kartu) --}}
            @if(count($pasiens) > 0)
            <div class="bg-white border-t border-slate-100 p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6 rounded-b-[32px]">
                
                <div class="flex items-center justify-center md:justify-start gap-8 w-full md:w-auto">
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sudah Diisi</p>
                        <p class="text-4xl font-black text-indigo-600 leading-none font-poppins" id="countSudah">0</p>
                    </div>
                    <div class="w-px h-12 bg-slate-200 hidden md:block"></div>
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sisa Belum</p>
                        <p class="text-4xl font-black text-rose-500 leading-none font-poppins transition-colors duration-300" id="countSisa">{{ count($pasiens) }}</p>
                    </div>
                </div>

                <button type="submit" id="btnSubmit" class="w-full md:w-auto px-10 py-5 bg-gradient-to-r from-slate-900 to-slate-800 text-white font-black text-[13px] uppercase tracking-widest rounded-[20px] hover:from-indigo-600 hover:to-violet-600 shadow-[0_10px_25px_rgba(0,0,0,0.1)] hover:shadow-[0_15px_30px_rgba(79,70,229,0.3)] hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-cloud-upload-alt text-xl text-indigo-300"></i> Simpan Data Presensi
                </button>
            </div>
            @endif

        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const totalPasiens = {{ count($pasiens) }};
    const countSudahEl = document.getElementById('countSudah');
    const countSisaEl  = document.getElementById('countSisa');
    const radios       = document.querySelectorAll('.logic-radio');

    // 1. ENGINE PENGHITUNG REAL-TIME
    function updateCounters() {
        const answered = document.querySelectorAll('.logic-radio:checked').length;
        const sisa = totalPasiens - answered;
        
        if(countSudahEl) countSudahEl.textContent = answered;
        if(countSisaEl) {
            countSisaEl.textContent = sisa;
            // Ubah warna Sisa Belum menjadi Hijau jika sudah tuntas (0)
            if (sisa === 0) {
                countSisaEl.classList.remove('text-rose-500');
                countSisaEl.classList.add('text-emerald-500');
            } else {
                countSisaEl.classList.add('text-rose-500');
                countSisaEl.classList.remove('text-emerald-500');
            }
        }
    }

    // 2. ENGINE TOGGLE KETERANGAN
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            const id = this.dataset.id;
            const ketBox = document.getElementById('ketBox_' + id);
            
            if (this.value == '0') {
                ketBox.classList.add('open');
                setTimeout(() => ketBox.querySelector('input').focus(), 100);
            } else {
                ketBox.classList.remove('open');
                ketBox.querySelector('input').value = '';
            }
            
            updateCounters();
        });
    });

    // Inisialisasi saat memuat halaman
    updateCounters();

    // 3. FITUR "TANDAI HADIR SEMUA" (BULK ACTION)
    document.getElementById('btnHadirSemua')?.addEventListener('click', function() {
        Swal.fire({
            title: 'Tandai Hadir Semua?',
            html: '<p class="text-sm text-slate-500">Seluruh warga dalam daftar ini akan otomatis ditandai sebagai <b>Hadir</b>. Anda tetap bisa mengubahnya secara manual setelah ini.</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hadirkan Semua',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-[24px]' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelectorAll('.radio-hadir').forEach(radio => {
                    radio.checked = true;
                    // Trigger event change agar kotak keterangan absen menutup otomatis
                    radio.dispatchEvent(new Event('change'));
                });
                
                // Beri notifikasi mini
                const Toast = Swal.mixin({
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 2000,
                    timerProgressBar: true, customClass: { popup: 'rounded-[16px]' }
                });
                Toast.fire({ icon: 'success', title: 'Berhasil ditandai hadir semua' });
            }
        });
    });

    // 4. FITUR PENCARIAN WARGA INSTAN
    const searchInput = document.getElementById('searchInput');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('.warga-row');
            
            rows.forEach(row => {
                const nama = row.querySelector('.warga-nama').textContent.toLowerCase();
                const nik = row.querySelector('.warga-nik').textContent.toLowerCase();
                if (nama.includes(filter) || nik.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // 5. SMART VALIDATION SEBELUM SUBMIT
    document.getElementById('formAbsensi')?.addEventListener('submit', function(e) {
        const answered = document.querySelectorAll('.logic-radio:checked').length;
        
        if (answered < totalPasiens) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning', title: 'Belum Tuntas',
                html: `<p class="text-sm text-slate-500">Anda belum mengisi status untuk <b>${totalPasiens - answered} warga</b>. Mohon lengkapi seluruh daftar panggilan sebelum menyimpan.</p>`,
                confirmButtonColor: '#4f46e5', confirmButtonText: 'Lanjutkan Mengisi',
                customClass: { popup: 'rounded-[28px]' }
            });
            return;
        }

        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-xl text-indigo-300"></i> Menyimpan ke Server...';
        btn.classList.add('opacity-75', 'cursor-wait', 'scale-95');
    });
</script>
@endpush
@endsection