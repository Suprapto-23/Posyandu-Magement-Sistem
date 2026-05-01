@extends('layouts.kader')
@section('title', 'Log Pemeriksaan Pasien')
@section('page-name', 'Rekam Medis (EMR)')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* =================================================================
       NEXUS SAAS DESIGN SYSTEM
       ================================================================= */
    
    /* Animasi Masuk Profesional */
    .animate-fade-in { opacity: 0; animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

    /* Input & Select Nexus (Sleek) */
    .nexus-input, .nexus-select {
        background-color: #ffffff; border: 1px solid #e2e8f0; color: #334155;
        font-family: 'Inter', sans-serif; font-size: 0.85rem; font-weight: 600;
        border-radius: 14px; transition: all 0.2s ease; width: 100%; outline: none;
    }
    .nexus-input { padding: 0.75rem 1.25rem 0.75rem 2.75rem; }
    .nexus-select {
        appearance: none; -webkit-appearance: none; cursor: pointer; padding: 0.75rem 2.5rem 0.75rem 1.25rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;
    }
    .nexus-input:focus, .nexus-select:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
    .nexus-input:hover, .nexus-select:hover { border-color: #cbd5e1; }

    /* Tabel Nexus Presisi */
    .nexus-table-container { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 24px; box-shadow: 0 10px 30px -10px rgba(0,0,0,0.05); overflow: hidden; }
    .nexus-table { width: 100%; border-collapse: collapse; text-align: left; }
    .nexus-table th { background: #f8fafc; color: #64748b; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 1rem 1.5rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap; position: sticky; top: 0; z-index: 10; }
    .nexus-table td { padding: 1.25rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s; }
    .nexus-table tr:last-child td { border-bottom: none; }
    .nexus-table tr:hover td { background-color: #f8fafc; }

    /* Scrollbar Estetik */
    .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scroll::-webkit-scrollbar-track { background: transparent; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* Micro-Badges Medis (Tegas & Presisi) */
    .med-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.6rem; border-radius: 8px; font-size: 0.65rem; font-weight: 700; white-space: nowrap; border: 1px solid transparent; letter-spacing: 0.02em; }
    .med-blue { background: #f0f9ff; color: #0284c7; border-color: #bae6fd; }
    .med-green { background: #ecfdf5; color: #059669; border-color: #a7f3d0; }
    .med-amber { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .med-rose { background: #fff1f2; color: #e11d48; border-color: #fecdd3; }
    .med-purple { background: #faf5ff; color: #7e22ce; border-color: #e9d5ff; }

    /* =================================================================
       SWEETALERT 2 - ISOLASI TOTAL (ANTI-BUG ABU-ABU)
       ================================================================= */
    body.swal2-shown:not(.swal2-toast-shown) .swal2-container { z-index: 10000 !important; backdrop-filter: blur(6px) !important; background: rgba(15, 23, 42, 0.4) !important; }
    
    .nexus-modal { border-radius: 28px !important; padding: 2rem !important; background: #ffffff !important; width: 26em !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important; border: 1px solid #f1f5f9 !important; }
    .nexus-modal .swal2-title { font-family: 'Poppins', sans-serif !important; font-weight: 800 !important; font-size: 1.3rem !important; color: #0f172a !important; margin-bottom: 0.5rem !important; }
    .nexus-modal .swal2-html-container { font-family: 'Inter', sans-serif !important; color: #64748b !important; font-size: 0.85rem !important; line-height: 1.6 !important; }
    
    .btn-swal-danger { background: #f43f5e !important; color: white !important; border-radius: 100px !important; padding: 12px 24px !important; font-weight: 700 !important; font-size: 11px !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; border: none !important; margin-right: 8px !important; transition: 0.2s !important; }
    .btn-swal-danger:hover { background: #e11d48 !important; }
    .btn-swal-cancel { background: #f1f5f9 !important; color: #475569 !important; border-radius: 100px !important; padding: 12px 24px !important; font-weight: 700 !important; font-size: 11px !important; text-transform: uppercase !important; letter-spacing: 0.05em !important; border: none !important; transition: 0.2s !important; }
    .btn-swal-cancel:hover { background: #e2e8f0 !important; }

    /* Toast Pojok (Mini & Rapi) */
    div:where(.swal2-container).swal2-top-end { pointer-events: none !important; }
    div:where(.swal2-container).swal2-top-end > .swal2-toast {
        pointer-events: auto !important; background: #ffffff !important; border: 1px solid #e2e8f0 !important; border-radius: 14px !important; padding: 10px 18px !important; box-shadow: 0 10px 30px -5px rgba(0,0,0,0.1) !important; width: auto !important; display: flex !important; align-items: center !important; margin-top: 1rem !important; margin-right: 1rem !important;
    }
    div:where(.swal2-container).swal2-top-end .swal2-icon { transform: scale(0.6) !important; margin: 0 8px 0 -4px !important; }
    div:where(.swal2-container).swal2-top-end .swal2-title { font-family: 'Inter', sans-serif !important; font-size: 13px !important; font-weight: 600 !important; color: #334155 !important; margin: 0 !important; }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto animate-fade-in pb-12 relative z-10 mt-2">

    {{-- AURA BACKGROUND LEMBUT --}}
    <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 left-0 w-[400px] h-[400px] bg-sky-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>

    {{-- 1. HEADER (ENTERPRISE LOOK) --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl shrink-0 border border-indigo-100 shadow-sm">
                <i class="fas fa-notes-medical"></i>
            </div>
            <div>
                <div class="inline-flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[9px] font-bold text-indigo-600 uppercase tracking-widest">Modul E-Rekam Medis</span>
                </div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight font-poppins mb-0.5">Log Pemeriksaan Fisik</h1>
                <p class="text-slate-500 font-medium text-[13px]">Pusat data antropometri dan klinis warga terintegrasi.</p>
            </div>
        </div>
        <div class="shrink-0">
            <a href="{{ route('kader.pemeriksaan.create') }}" class="w-full md:w-auto px-6 py-3.5 bg-slate-800 text-white font-bold text-[11px] uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition-colors shadow-sm flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Input Rekam Medis
            </a>
        </div>
    </div>

    {{-- 2. PANEL FILTER (GRID LAYOUT RAPI & NATIVE) --}}
    @php
        $reqKategori = request('kategori', '');
        $reqStatus   = request('status', '');
    @endphp

    <div class="bg-white/80 backdrop-blur-xl rounded-[24px] border border-slate-200 shadow-sm p-4 md:p-5 mb-6 relative z-20">
        <form action="{{ route('kader.pemeriksaan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            
            {{-- Navigasi Kategori (Pills Style) --}}
            <div class="md:col-span-12 lg:col-span-6 flex flex-wrap bg-slate-50 p-1.5 rounded-[16px] border border-slate-200">
                @foreach([
                    ''          => ['label' => 'Semua', 'icon' => 'fa-border-all'],
                    'balita'    => ['label' => 'Balita', 'icon' => 'fa-child'],
                    'ibu_hamil' => ['label' => 'Ibu Hamil', 'icon' => 'fa-female'],
                    'remaja'    => ['label' => 'Remaja', 'icon' => 'fa-user-graduate'],
                    'lansia'    => ['label' => 'Lansia', 'icon' => 'fa-wheelchair']
                ] as $val => $data)
                    <button type="submit" name="kategori" value="{{ $val }}" class="flex-1 px-3 py-2.5 rounded-[12px] text-[11px] font-bold uppercase tracking-wider transition-all {{ $reqKategori === $val ? 'bg-white text-indigo-600 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100/50' }}">
                        <i class="fas {{ $data['icon'] }} mr-1.5 {{ $reqKategori === $val ? 'text-indigo-500' : 'opacity-50' }}"></i> {{ $data['label'] }}
                    </button>
                @endforeach
                <input type="hidden" name="status" value="{{ $reqStatus }}">
            </div>

            {{-- Filter Status --}}
            <div class="md:col-span-5 lg:col-span-3 h-full">
                <select name="status" onchange="this.form.submit()" class="nexus-select h-full">
                    <option value="">Status Validasi Medis</option>
                    <option value="pending" {{ $reqStatus == 'pending' ? 'selected' : '' }}>Menunggu Bidan</option>
                    <option value="tervalidasi" {{ $reqStatus == 'tervalidasi' ? 'selected' : '' }}>Tervalidasi Bidan</option>
                    <option value="ditolak" {{ $reqStatus == 'ditolak' ? 'selected' : '' }}>Ditolak / Revisi</option>
                </select>
            </div>

            {{-- Live Search Input --}}
            <div class="md:col-span-7 lg:col-span-3 relative flex items-center h-full">
                <i class="fas fa-search absolute left-4 text-slate-400 text-sm"></i>
                <input type="text" id="liveSearchInput" name="search" value="{{ request('search') }}" placeholder="Cari Nama atau NIK..." class="nexus-input h-full">
            </div>

        </form>
    </div>

    {{-- 3. DATA GRID (TABEL PROFESIONAL NEXUS) --}}
    <div class="nexus-table-container min-h-[400px] flex flex-col">
        <div class="overflow-x-auto overflow-y-auto custom-scroll flex-1 max-h-[600px]">
            <table class="nexus-table min-w-[1000px]">
                <thead>
                    <tr>
                        <th class="w-40 pl-6 text-center">Tgl / Kategori</th>
                        <th class="w-64">Identitas Pasien</th>
                        <th>Hasil Pengukuran Fisik</th>
                        <th class="w-40 text-center">Status Validasi</th>
                        <th class="w-32 pr-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="medisTableBody">
                    
                    @forelse($pemeriksaans ?? [] as $item)
                    @php
                        $kategori = strtoupper(str_replace('_', ' ', $item->kategori_pasien ?? 'UMUM'));
                        $namaPasien = $item->nama_pasien;
                        $nikPasien = $item->nik_pasien;
                        $badgeColor = $item->status_verifikasi_badge;
                        $statusText = $item->status_verifikasi_text;
                        $iconStatus = $badgeColor == 'emerald' ? 'fa-check-circle' : ($badgeColor == 'rose' ? 'fa-times-circle' : 'fa-clock');
                    @endphp
                    
                    <tr class="med-row" data-search="{{ strtolower($namaPasien . ' ' . $nikPasien) }}">
                        
                        {{-- 1. WAKTU & KATEGORI --}}
                        <td class="pl-6 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-[13px] font-bold text-slate-800">{{ $item->tanggal_periksa->format('d M Y') }}</span>
                                <span class="text-[10px] font-semibold text-slate-500 mt-0.5">{{ $item->tanggal_periksa->format('H:i') }} WIB</span>
                                <span class="inline-block mt-2 bg-slate-100 text-slate-600 text-[9px] font-bold uppercase px-2 py-0.5 rounded-md border border-slate-200">
                                    {{ $kategori }}
                                </span>
                            </div>
                        </td>

                        {{-- 2. IDENTITAS --}}
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[12px] bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shrink-0 font-poppins">
                                    {{ strtoupper(substr($namaPasien, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-[14px] font-semibold text-slate-800 truncate" title="{{ $namaPasien }}">{{ $namaPasien }}</h4>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <i class="far fa-address-card text-slate-300 text-[10px]"></i>
                                        <span class="text-[11px] text-slate-500 font-mono tracking-wide">NIK: {{ $nikPasien }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- 3. HASIL FISIK (Micro Badges Rapi) --}}
                        <td class="whitespace-normal py-3">
                            <div class="flex flex-wrap gap-2 max-w-[400px]">
                                @if($item->berat_badan) <span class="med-badge med-blue">BB: {{ $item->berat_badan }} kg</span> @endif
                                @if($item->tinggi_badan) <span class="med-badge med-green">TB: {{ $item->tinggi_badan }} cm</span> @endif
                                @if($item->lingkar_kepala) <span class="med-badge med-amber">LK: {{ $item->lingkar_kepala }} cm</span> @endif
                                @if($item->lingkar_lengan) <span class="med-badge med-amber">LILA: {{ $item->lingkar_lengan }} cm</span> @endif
                                @if($item->lingkar_perut) <span class="med-badge med-amber">LP: {{ $item->lingkar_perut }} cm</span> @endif
                                
                                @if($item->tekanan_darah) <span class="med-badge med-rose">Tensi: {{ $item->tekanan_darah }}</span> @endif
                                @if($item->hemoglobin) <span class="med-badge med-rose">Hb: {{ $item->hemoglobin }} g/dL</span> @endif
                                
                                @if($item->gula_darah) <span class="med-badge med-purple">Gula: {{ $item->gula_darah }}</span> @endif
                                @if($item->kolesterol) <span class="med-badge med-purple">Koles: {{ $item->kolesterol }}</span> @endif
                                @if($item->asam_urat) <span class="med-badge med-purple">AU: {{ $item->asam_urat }}</span> @endif

                                @if(empty($item->berat_badan) && empty($item->tinggi_badan) && empty($item->tekanan_darah))
                                    <span class="text-[11px] text-slate-400 italic">Belum ada input klinis spesifik.</span>
                                @endif
                            </div>
                        </td>

                        {{-- 4. STATUS --}}
                        <td class="text-center">
                            <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-full bg-{{ $badgeColor }}-50 border border-{{ $badgeColor }}-200 w-full max-w-[130px] mx-auto shadow-sm">
                                <i class="fas {{ $iconStatus }} text-{{ $badgeColor }}-500 text-[10px]"></i>
                                <span class="text-[9px] font-bold text-{{ $badgeColor }}-600 uppercase tracking-widest">{{ $statusText }}</span>
                            </div>
                        </td>

                        {{-- 5. AKSI --}}
                        <td class="text-right pr-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('kader.pemeriksaan.show', $item->id) }}" class="w-9 h-9 rounded-[10px] bg-white border border-slate-200 text-indigo-500 hover:bg-indigo-50 hover:border-indigo-300 flex items-center justify-center transition-colors shadow-sm" title="Detail Medis"><i class="fas fa-file-medical text-sm"></i></a>
                                
                                <a href="{{ route('kader.pemeriksaan.edit', $item->id) }}" class="w-9 h-9 rounded-[10px] bg-white border border-slate-200 text-amber-500 hover:bg-amber-50 hover:border-amber-300 flex items-center justify-center transition-colors shadow-sm" title="Koreksi Data"><i class="fas fa-pen text-[13px]"></i></a>
                                
                                <form action="{{ route('kader.pemeriksaan.destroy', $item->id) }}" method="POST" class="delete-form m-0 p-0">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-delete w-9 h-9 rounded-[10px] bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:bg-rose-50 hover:border-rose-200 flex items-center justify-center transition-colors shadow-sm" title="Hapus Permanen"><i class="fas fa-trash-alt text-[13px]"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    @endforelse

                    {{-- 4. EMPTY STATE BERSATU DENGAN TABEL --}}
                    <tr id="emptyStateRow" style="{{ (isset($pemeriksaans) && count($pemeriksaans) > 0) ? 'display:none;' : '' }}">
                        <td colspan="5" class="py-24 text-center bg-slate-50/50">
                            <div class="flex flex-col items-center justify-center max-w-md mx-auto">
                                <div class="w-20 h-20 bg-white rounded-full border border-slate-100 shadow-sm flex items-center justify-center text-indigo-300 text-3xl mx-auto mb-4">
                                    <i class="fas fa-stethoscope"></i>
                                </div>
                                <h4 class="text-[15px] font-bold text-slate-700 uppercase tracking-widest mb-1">Rekam Medis Kosong</h4>
                                <p class="text-[13px] text-slate-500 mb-6 font-medium">Sistem tidak menemukan log pemeriksaan yang sesuai dengan filter pencarian Anda saat ini.</p>
                                <a href="{{ route('kader.pemeriksaan.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 text-indigo-600 font-bold text-[11px] uppercase tracking-widest rounded-xl hover:bg-indigo-50 hover:border-indigo-200 transition-colors shadow-sm">
                                    <i class="fas fa-plus"></i> Input Pasien Baru
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination UI --}}
    @if(isset($pemeriksaans) && count($pemeriksaans) > 0 && method_exists($pemeriksaans, 'links'))
    <div class="mt-6 flex justify-end">
        {{ $pemeriksaans->links() }}
    </div>
    @endif

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. FITUR LIVE SEARCH INSTAN JAVASCRIPT
    document.getElementById('liveSearchInput').addEventListener('input', function(e) {
        const keyword = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.med-row');
        const emptyState = document.getElementById('emptyStateRow');
        let adaDataYangCocok = false;

        rows.forEach(row => {
            const dataSearch = row.getAttribute('data-search');
            if(dataSearch.includes(keyword)) {
                row.style.display = ''; 
                adaDataYangCocok = true;
            } else {
                row.style.display = 'none'; 
            }
        });

        // Tampilkan State Kosong secara presisi jika tidak ada yang cocok
        if (emptyState) {
            emptyState.style.display = adaDataYangCocok ? 'none' : '';
        }
    });

    // 2. FITUR HAPUS DENGAN SWEETALERT (ANTI BUG BACKGROUND)
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Hapus Rekam Medis?',
                html: 'Log pemeriksaan fisik ini akan dihapus secara permanen dari database.',
                icon: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                reverseButtons: true,
                confirmButtonText: 'Ya, Hapus Data',
                cancelButtonText: 'Batal',
                backdrop: true, // Pastikan ada layer gelap khusus untuk modal ini
                customClass: { 
                    popup: 'nexus-modal', 
                    confirmButton: 'btn-swal-danger',
                    cancelButton: 'btn-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // 3. NOTIFIKASI BERHASIL TOAST (MUNGIL & RAPI)
    @if(session('success'))
        const ToastSuccess = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
            backdrop: false, // MATIKAN BACKDROP AGAR LAYAR TIDAK HITAM SEPARUH
            customClass: { popup: 'nexus-toast' }
        });
        ToastSuccess.fire({ icon: 'success', title: "{{ session('success') }}" });
    @endif

    // Notifikasi Error (Tetap Popup Tengah agar Jelas)
    @if(session('error'))
        Swal.fire({
            icon: 'error', title: 'Aksi Gagal', text: "{{ session('error') }}",
            buttonsStyling: false, backdrop: true,
            confirmButtonText: 'Tutup',
            customClass: { popup: 'nexus-modal', confirmButton: 'btn-swal-cancel' }
        });
    @endif
</script>
@endpush
@endsection