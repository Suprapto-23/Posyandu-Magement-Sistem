@extends('layouts.kader')

@section('title', 'Absensi Posyandu')
@section('page-name', 'Absensi Warga')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16,1,0.3,1) forwards; }
    @keyframes slideUpFade { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }

    /* Tab Kategori Mobile Friendly */
    .kat-tab { cursor:pointer; padding:0.6rem 1.25rem; border-radius:9999px; font-size:0.75rem; font-weight:900;
               letter-spacing:0.04em; text-transform:uppercase; border:2px solid #e2e8f0; background:white;
               color:#64748b; transition:all 0.25s; display:inline-flex; align-items:center; justify-content:center; gap:0.4rem; white-space:nowrap;}
    .kat-tab:hover { border-color:#cbd5e1; color:#334155; }
    .kat-tab.active-bayi      { background:#0ea5e9; color:white; border-color:#0ea5e9; box-shadow:0 4px 14px -3px rgba(14,165,233,0.5); }
    .kat-tab.active-balita    { background:#8b5cf6; color:white; border-color:#8b5cf6; box-shadow:0 4px 14px -3px rgba(139,92,246,0.5); }
    .kat-tab.active-remaja    { background:#f59e0b; color:white; border-color:#f59e0b; box-shadow:0 4px 14px -3px rgba(245,158,11,0.5); }
    .kat-tab.active-lansia    { background:#10b981; color:white; border-color:#10b981; box-shadow:0 4px 14px -3px rgba(16,185,129,0.5); }
    .kat-tab.active-ibu_hamil { background:#ec4899; color:white; border-color:#ec4899; box-shadow:0 4px 14px -3px rgba(236,72,153,0.5); }

    /* Mini iOS Style Toggle */
    .toggle-checkbox:checked { right: 0; border-color: #10b981; }
    .toggle-checkbox:checked + .toggle-label { background-color: #10b981; }
    
    .table-container { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    .pasien-row { transition: all 0.2s ease; }
    .pasien-row:nth-child(even) { background-color: #f8fafc; }
    .pasien-row.hadir-state { background-color: #ecfdf5 !important; }
    .kompak-input { padding: 0.35rem 0.75rem; font-size: 0.75rem; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto animate-slide-up">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 text-center sm:text-left">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Absensi Terpadu</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Sistem pencatatan kehadiran Real-Time.</p>
        </div>
        <a href="{{ route('kader.absensi.riwayat') }}" class="inline-flex justify-center items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 font-extrabold text-sm rounded-xl hover:bg-slate-50 shadow-sm transition-all w-full sm:w-auto">
            <i class="fas fa-history text-indigo-500"></i> Lihat Arsip
        </a>
    </div>

    {{-- STATISTIK KARTU (TETAP SAMA SEPERTI MILIK ANDA) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
        @php
            $katConfig = [
                'bayi'      => ['label'=>'Bayi',      'icon'=>'fa-baby',          'color'=>'sky'],
                'balita'    => ['label'=>'Balita',    'icon'=>'fa-child',         'color'=>'violet'],
                'remaja'    => ['label'=>'Remaja',    'icon'=>'fa-user-graduate', 'color'=>'amber'],
                'lansia'    => ['label'=>'Lansia',    'icon'=>'fa-user-clock',    'color'=>'emerald'],
                'ibu_hamil' => ['label'=>'Ibu Hamil', 'icon'=>'fa-female',        'color'=>'pink'],
            ];
        @endphp
        @foreach($katConfig as $kat => $cfg)
        <a href="{{ route('kader.absensi.index') }}?kategori={{ $kat }}"
           class="bg-white border-2 rounded-2xl p-4 text-center transition-all hover:-translate-y-0.5 flex flex-col items-center justify-center
                  {{ $kategori === $kat ? 'border-'.$cfg['color'].'-400 shadow-md transform -translate-y-1' : 'border-slate-100 hover:border-slate-300 hover:shadow-sm' }}">
            <div class="w-9 h-9 rounded-full bg-{{ $cfg['color'] }}-50 text-{{ $cfg['color'] }}-600 flex items-center justify-center mb-2 text-base shadow-inner">
                <i class="fas {{ $cfg['icon'] }}"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">{{ $cfg['label'] }}</p>
            <p class="text-xl font-black text-slate-800 leading-none">
                {{ $statsPerKategori[$kat]['total_pertemuan'] ?? 0 }} <span class="text-[10px] font-bold text-slate-400">Sesi</span>
            </p>
        </a>
        @endforeach
    </div>

    {{-- TAB KATEGORI --}}
    <div class="flex overflow-x-auto pb-4 mb-2 gap-2 table-container justify-start sm:justify-center">
        @foreach($katConfig as $kat => $cfg)
        <a href="{{ route('kader.absensi.index') }}?kategori={{ $kat }}" class="kat-tab shrink-0 {{ $kategori === $kat ? 'active-'.$kat : '' }}">
            <i class="fas {{ $cfg['icon'] }}"></i> {{ $cfg['label'] }}
        </a>
        @endforeach
    </div>

    {{-- WADAH UTAMA (TIDAK LAGI MENGGUNAKAN FORM BUNGKUS) --}}
    <div class="bg-white border border-slate-200/80 rounded-[24px] p-5 mb-6 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center text-xl shrink-0"><i class="fas fa-calendar-day"></i></div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Sesi Berjalan</p>
                <p class="text-sm font-black text-slate-800">Pertemuan ke-{{ $pertemuanBerikutnya }} • <span class="text-indigo-600">{{ now()->translatedFormat('d F Y') }}</span></p>
            </div>
        </div>
        
        {{-- FITUR BARU: LIVE SEARCH --}}
        <div class="w-full sm:w-64 relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" id="liveSearch" placeholder="Cari nama / NIK warga..." class="w-full border border-slate-200 rounded-xl pl-9 pr-3 py-2.5 text-sm font-bold text-slate-800 focus:outline-none focus:border-indigo-400 bg-slate-50 transition-colors">
        </div>
    </div>

    {{-- BARIS INFO JUMLAH --}}
    <div class="flex flex-col sm:flex-row items-center justify-between bg-slate-800 text-white px-6 py-3.5 rounded-t-[20px] gap-3">
        <h3 class="font-black text-sm flex items-center gap-2"><i class="fas fa-users text-indigo-400"></i> Daftar Kehadiran (Auto-Save)</h3>
        <div class="flex items-center gap-5">
            <div class="text-center">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Hadir</p>
                <p class="text-xl font-black text-emerald-400 leading-none" id="counter-hadir">{{ count($hadirList ?? []) }}</p>
            </div>
            <div class="w-px h-6 bg-slate-600"></div>
            <div class="text-center">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Absen</p>
                <p class="text-xl font-black text-rose-400 leading-none" id="counter-absen">{{ $pasiens->count() - count($hadirList ?? []) }}</p>
            </div>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-white border-x border-b border-slate-200/80 rounded-b-[20px] overflow-hidden mb-6 shadow-sm">
        @if($pasiens->isEmpty())
            <div class="text-center py-16">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-3 text-2xl shadow-inner"><i class="fas fa-users-slash"></i></div>
                <p class="font-black text-slate-700 text-base">Belum Ada Data Master</p>
            </div>
        @else
            <div class="table-container overflow-x-auto max-h-[65vh]">
                <table class="w-full text-left border-collapse whitespace-nowrap min-w-[700px]">
                    <thead class="sticky top-0 z-20 bg-slate-100/90 backdrop-blur-sm border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center w-12 border-r border-slate-200/50">No</th>
                            <th class="px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest border-r border-slate-200/50">Identitas Warga</th>
                            <th class="px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest border-r border-slate-200/50">Info Spesifik</th>
                            <th class="px-4 py-3 text-[10px] font-black text-indigo-600 uppercase tracking-widest text-center border-r border-slate-200/50 bg-indigo-50/50">Presensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200/60" id="tableBody">
                        @foreach($pasiens as $index => $p)
                        @php 
                            $isHadir = in_array($p->id, $hadirList ?? []); 
                        @endphp
                        <tr class="pasien-row {{ $isHadir ? 'hadir-state' : '' }}" id="row-{{ $p->id }}" data-search="{{ strtolower($p->nama_lengkap . ' ' . $p->nik) }}">
                            
                            <td class="px-4 py-2.5 text-center border-r border-slate-200/50 text-xs font-bold text-slate-400">
                                {{ $index + 1 }}
                            </td>
                            
                            <td class="px-4 py-2.5 border-r border-slate-200/50 max-w-[200px] truncate">
                                <p class="font-black text-slate-800 text-[13px] truncate" title="{{ $p->nama_lengkap }}">{{ $p->nama_lengkap }}</p>
                                <p class="text-[10px] text-slate-400 font-bold font-mono truncate">
                                    @if($kategori === 'bayi' || $kategori === 'balita') <i class="fas fa-female text-slate-300"></i> Ibu: {{ $p->nama_ibu ?? '-' }}
                                    @elseif($kategori === 'ibu_hamil') <i class="fas fa-male text-slate-300"></i> Suami: {{ $p->nama_suami ?? '-' }}
                                    @else <i class="fas fa-id-card text-slate-300"></i> {{ $p->nik ?? '-' }} @endif
                                </p>
                            </td>

                            <td class="px-4 py-2.5 border-r border-slate-200/50">
                                @php
                                    if ($kategori === 'ibu_hamil') $info = $p->usia_kehamilan ? $p->usia_kehamilan . ' Minggu' : '-';
                                    else {
                                        $diff = \Carbon\Carbon::parse($p->tanggal_lahir)->diff(now());
                                        if ($kategori === 'bayi') $info = $diff->m . ' bln ' . $diff->d . ' hr';
                                        elseif ($kategori === 'balita') $info = $diff->y . ' th ' . $diff->m . ' bln';
                                        else $info = $diff->y . ' tahun';
                                    }
                                @endphp
                                <span class="px-2 py-1 bg-white border border-slate-200 rounded text-[10px] font-black text-slate-600 uppercase">{{ $info }}</span>
                            </td>

                            <td class="px-4 py-2.5 text-center bg-slate-50/30">
                                <div class="relative inline-block w-12 align-middle select-none">
                                    <input type="checkbox" value="{{ $p->id }}" id="chk-{{ $p->id }}" 
                                           {{ $isHadir ? 'checked' : '' }}
                                           class="hadir-check toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-[3px] border-slate-200 appearance-none cursor-pointer transition-all duration-300 z-10 top-0.5" 
                                           onchange="toggleHadir({{ $p->id }}, this)"/>
                                    <label for="chk-{{ $p->id }}" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 cursor-pointer transition-colors duration-300 relative"></label>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if(!$pasiens->isEmpty())
    {{-- TOMBOL SELESAI SESI --}}
    <form action="{{ route('kader.absensi.selesai') }}" method="POST">
        @csrf
        <input type="hidden" name="kategori" value="{{ $kategori }}">
        <div class="sticky bottom-4 z-40 bg-white/95 backdrop-blur-xl border border-slate-200 p-4 sm:p-5 rounded-[20px] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div class="hidden sm:block">
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest"><i class="fas fa-cloud-upload-alt text-indigo-500 mr-1"></i> Data Tersimpan Otomatis</p>
                <p class="text-xs font-black text-slate-800 mt-0.5">Jika kehadiran sudah sesuai, klik selesai untuk melihat ringkasan.</p>
            </div>
            <button type="submit" class="w-full sm:w-auto px-10 py-3.5 bg-slate-800 text-white font-black text-sm rounded-xl hover:bg-slate-900 hover:shadow-lg transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                Selesai & Lihat Ringkasan <i class="fas fa-arrow-right text-base"></i>
            </button>
        </div>
    </form>
    @endif

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    const total = {{ $pasiens->count() }};

    // 1. LIVE SEARCH LOGIC
    document.getElementById('liveSearch').addEventListener('input', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('.pasien-row');
        
        rows.forEach(row => {
            let dataSearch = row.getAttribute('data-search');
            if (dataSearch.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // 2. COUNTER LOGIC
    function hitungCounter() {
        const hadir = document.querySelectorAll('.hadir-check:checked').length;
        document.getElementById('counter-hadir').textContent = hadir;
        document.getElementById('counter-absen').textContent = total - hadir;
    }

    // 3. UI UPDATE LOGIC
    function updateRowUI(id, isHadir) {
        const row = document.getElementById('row-' + id);
        if(isHadir) row.classList.add('hadir-state');
        else row.classList.remove('hadir-state');
        hitungCounter();
    }

    // 4. INSTANT SAVE (AJAX FETCH)
    async function toggleHadir(pasienId, element) {
        const isHadir = element.checked;
        
        // Optimistic UI Update (Update layar duluan sebelum nunggu server biar terasa cepat)
        updateRowUI(pasienId, isHadir);

        try {
            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('kategori', '{{ $kategori }}');
            formData.append('pasien_id', pasienId);
            formData.append('hadir', isHadir ? 1 : 0);

            let response = await fetch('{{ route("kader.absensi.toggle") }}', {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });

            let result = await response.json();

            if (!response.ok) throw new Error(result.message);

            // Toast Notification Kecil
            Toastify({
                text: result.message,
                duration: 2000,
                gravity: "bottom", position: "right",
                style: { background: isHadir ? "#10b981" : "#64748b", borderRadius: "10px", fontSize: "12px", fontWeight: "bold" }
            }).showToast();

        } catch (error) {
            // Jika gagal, kembalikan posisi toggle seperti semula (Rollback UI)
            element.checked = !isHadir;
            updateRowUI(pasienId, !isHadir);
            Swal.fire({ icon: 'error', title: 'Gagal Menyimpan', text: error.message || 'Koneksi terputus.', confirmButtonColor: '#f43f5e' });
        }
    }
</script>
@endpush
@endsection