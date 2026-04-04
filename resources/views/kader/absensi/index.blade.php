@extends('layouts.kader')

@section('title', 'Absensi Posyandu')
@section('page-name', 'Absensi Warga')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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

    /* Mini iOS Style Toggle untuk Tabel */
    .toggle-checkbox:checked { right: 0; border-color: #10b981; }
    .toggle-checkbox:checked + .toggle-label { background-color: #10b981; }
    
    /* Table State (Zebra & Highlight) */
    .table-container { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    .pasien-row { transition: all 0.2s ease; }
    .pasien-row:nth-child(even) { background-color: #f8fafc; } /* Zebra Striping */
    .pasien-row.hadir-state { background-color: #ecfdf5 !important; } /* Highlight Hijau jika hadir */
    
    .kompak-input { padding: 0.35rem 0.75rem; font-size: 0.75rem; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto animate-slide-up">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 text-center sm:text-left">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Absensi Terpadu</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Entri data masal (Bulk Entry) ala Spreadsheet.</p>
        </div>
        <a href="{{ route('kader.absensi.riwayat') }}" class="inline-flex justify-center items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 font-extrabold text-sm rounded-xl hover:bg-slate-50 shadow-sm transition-all w-full sm:w-auto">
            <i class="fas fa-history text-indigo-500"></i> Lihat Arsip
        </a>
    </div>

    {{-- STATISTIK KARTU --}}
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

    {{-- TAB KATEGORI (Scrollable di Mobile) --}}
    <div class="flex overflow-x-auto pb-4 mb-2 gap-2 table-container justify-start sm:justify-center">
        @foreach($katConfig as $kat => $cfg)
        <a href="{{ route('kader.absensi.index') }}?kategori={{ $kat }}" class="kat-tab shrink-0 {{ $kategori === $kat ? 'active-'.$kat : '' }}">
            <i class="fas {{ $cfg['icon'] }}"></i> {{ $cfg['label'] }}
        </a>
        @endforeach
    </div>

    @if($sesiHariIni)
    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-[20px] flex items-start gap-3 shadow-sm text-left">
        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-500 flex items-center justify-center text-lg shrink-0 mt-1"><i class="fas fa-check-circle"></i></div>
        <div>
            <p class="font-black text-amber-900 text-sm mb-0.5">Sesi {{ $katConfig[$kategori]['label'] }} Hari Ini Selesai!</p>
            <p class="text-xs font-medium text-amber-700 leading-relaxed mb-2">
                Pertemuan ke-{{ $sesiHariIni->nomor_pertemuan }} pada {{ $sesiHariIni->tanggal_posyandu->translatedFormat('d F Y') }} telah dikunci.
            </p>
            <a href="{{ route('kader.absensi.show', $sesiHariIni->id) }}" class="inline-flex px-4 py-2 bg-amber-500 text-white font-black text-[11px] rounded-lg hover:bg-amber-600 shadow-sm transition-all uppercase tracking-wider">Buka Log Sesi Ini</a>
        </div>
    </div>
    @endif

    {{-- FORM ABSENSI --}}
    <form action="{{ route('kader.absensi.store') }}" method="POST" id="formAbsensi">
        @csrf
        <input type="hidden" name="kategori" value="{{ $kategori }}">

        <div class="bg-white border border-slate-200/80 rounded-[24px] p-5 mb-6 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tgl Pelaksanaan</label>
                    <input type="date" name="tanggal_posyandu" value="{{ date('Y-m-d') }}" required class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-800 focus:outline-none focus:border-indigo-400 bg-slate-50">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">No. Sesi</label>
                    <div class="flex items-center gap-2 px-3 py-2 bg-indigo-50 border border-indigo-100 rounded-xl">
                        <i class="fas fa-hashtag text-indigo-500 text-sm"></i>
                        <span class="text-sm font-black text-indigo-700">Pertemuan ke-{{ $pertemuanBerikutnya }}</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Topik (Opsional)</label>
                    <input type="text" name="catatan" placeholder="Agenda posyandu..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-800 focus:outline-none focus:border-indigo-400 bg-slate-50">
                </div>
            </div>
        </div>

        {{-- BARIS INFO JUMLAH --}}
        <div class="flex flex-col sm:flex-row items-center justify-between bg-slate-800 text-white px-6 py-3.5 rounded-t-[20px] gap-3">
            <h3 class="font-black text-sm flex items-center gap-2"><i class="fas fa-users text-indigo-400"></i> Tabel Kehadiran</h3>
            <div class="flex items-center gap-5">
                <div class="text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Hadir</p>
                    <p class="text-xl font-black text-emerald-400 leading-none" id="counter-hadir">0</p>
                </div>
                <div class="w-px h-6 bg-slate-600"></div>
                <div class="text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Absen</p>
                    <p class="text-xl font-black text-rose-400 leading-none" id="counter-absen">{{ $pasiens->count() }}</p>
                </div>
            </div>
        </div>

        {{-- TABEL DATA ALA EXCEL --}}
        <div class="bg-white border-x border-b border-slate-200/80 rounded-b-[20px] overflow-hidden mb-6 shadow-sm">
            
            <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 bg-slate-50">
                <p class="text-[11px] font-bold text-slate-500">Geser ke kanan <i class="fas fa-arrows-alt-h mx-1"></i> jika tabel terpotong.</p>
                <div class="flex gap-2">
                    <button type="button" onclick="centangSemua(true)" class="px-3 py-1.5 bg-emerald-100 text-emerald-700 font-black text-[10px] rounded-lg hover:bg-emerald-200 transition-colors uppercase tracking-widest"><i class="fas fa-check-double mr-1"></i> Hadir Semua</button>
                    <button type="button" onclick="centangSemua(false)" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-500 font-black text-[10px] rounded-lg hover:bg-slate-100 transition-colors uppercase tracking-widest"><i class="fas fa-eraser mr-1"></i> Reset</button>
                </div>
            </div>

            @if($pasiens->isEmpty())
                <div class="text-center py-16">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-3 text-2xl shadow-inner"><i class="fas fa-users-slash"></i></div>
                    <p class="font-black text-slate-700 text-base">Belum Ada Data</p>
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
                                <th class="px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/60">
                            @foreach($pasiens as $index => $p)
                            <tr class="pasien-row" id="row-{{ $p->id }}">
                                
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

                                <td class="px-4 py-2.5 text-center border-r border-slate-200/50 bg-slate-50/30">
                                    {{-- Mini Toggle --}}
                                    <div class="relative inline-block w-12 align-middle select-none">
                                        <input type="checkbox" name="hadir[]" value="{{ $p->id }}" id="chk-{{ $p->id }}" class="hadir-check toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-[3px] border-slate-200 appearance-none cursor-pointer transition-all duration-300 z-10 top-0.5" onchange="updateRow({{ $p->id }}, this.checked)"/>
                                        <label for="chk-{{ $p->id }}" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 cursor-pointer transition-colors duration-300 relative"></label>
                                    </div>
                                </td>

                                <td class="px-4 py-2.5 min-w-[200px]">
                                    <input type="text" name="keterangan[{{ $p->id }}]" id="ket-{{ $p->id }}" placeholder="Opsional..." class="kompak-input w-full border border-slate-200 rounded bg-white text-slate-700 font-bold focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 outline-none">
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if(!$pasiens->isEmpty() && !$sesiHariIni)
        <div class="sticky bottom-4 z-40 bg-white/95 backdrop-blur-xl border border-slate-200 p-4 sm:p-5 rounded-[20px] shadow-[0_-10px_40px_rgba(0,0,0,0.1)] flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <div class="hidden sm:block">
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest"><i class="fas fa-info-circle text-indigo-500 mr-1"></i> Cek Kembali</p>
                <p class="text-xs font-black text-slate-800 mt-0.5">Pastikan jumlah hadir & absen sudah sesuai sebelum dikunci.</p>
            </div>
            <button type="submit" id="btnSimpan" class="w-full sm:w-auto px-10 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-black text-sm rounded-xl hover:shadow-[0_8px_20px_rgba(16,185,129,0.4)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                <i class="fas fa-lock text-base"></i> Kunci & Simpan Data
            </button>
        </div>
        @endif

    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const total = {{ $pasiens->count() }};

    function hitungCounter() {
        const hadir = document.querySelectorAll('.hadir-check:checked').length;
        document.getElementById('counter-hadir').textContent = hadir;
        document.getElementById('counter-absen').textContent = total - hadir;
    }

    function updateRow(id, isHadir) {
        const row = document.getElementById('row-' + id);
        const ket = document.getElementById('ket-' + id);
        
        if(isHadir) {
            row.classList.add('hadir-state');
            if (ket) { ket.placeholder = 'Catatan saat hadir...'; ket.value = ''; ket.classList.add('bg-emerald-50'); }
        } else {
            row.classList.remove('hadir-state');
            if (ket) { ket.placeholder = 'Alasan absen...'; ket.classList.remove('bg-emerald-50'); }
        }
        hitungCounter();
    }

    function centangSemua(val) {
        document.querySelectorAll('.hadir-check').forEach(chk => {
            if (chk.checked !== val) {
                chk.checked = val;
                updateRow(chk.value, val);
            }
        });
    }

    document.getElementById('formAbsensi').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Merekap Data Tabel',
            html: '<p class="text-sm text-slate-500">Mohon tunggu, sistem sedang menyimpan entri masal ini...</p>',
            allowOutsideClick: false, showConfirmButton: false,
            willOpen: () => { Swal.showLoading(); }
        });

        try {
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST', body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                Swal.fire({
                    icon: 'success', title: 'Tabel Dikunci!', text: result.message,
                    confirmButtonColor: '#10b981', timer: 1500
                }).then(() => { window.location.href = result.redirect; });
            } else {
                Swal.fire({ icon: 'error', title: 'Aksi Ditolak', text: result.message || 'Terjadi kesalahan.', confirmButtonColor: '#f43f5e' });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Koneksi Terputus', text: 'Server tidak merespons.', confirmButtonColor: '#f43f5e' });
        }
    });
</script>
@endpush
@endsection