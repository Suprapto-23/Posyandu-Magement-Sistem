@extends('layouts.kader')
@section('title', 'Log Pemeriksaan Pasien')
@section('page-name', 'Rekam Medis (EMR)')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* Animasi Masuk Profesional */
    .fade-in-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

    /* Input & Select Enterprise */
    .form-control-modern {
        background-color: #ffffff; border: 1px solid #cbd5e1; color: #334155;
        transition: all 0.2s ease-in-out; outline: none; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .form-control-modern:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
    
    select.form-control-modern {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 1rem center; background-size: 1rem;
    }

    /* Tabel Profesional (Clean Data Grid) */
    .sirs-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .sirs-table th { background-color: #f8fafc; color: #64748b; font-weight: 700; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.05em; padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; border-top: 1px solid #e2e8f0; }
    .sirs-table th:first-child { border-top-left-radius: 12px; border-left: 1px solid #e2e8f0; }
    .sirs-table th:last-child { border-top-right-radius: 12px; border-right: 1px solid #e2e8f0; }
    .sirs-table td { padding: 1.25rem; border-bottom: 1px solid #e2e8f0; vertical-align: top; background-color: #ffffff; transition: background-color 0.2s; }
    .sirs-table td:first-child { border-left: 1px solid #e2e8f0; }
    .sirs-table td:last-child { border-right: 1px solid #e2e8f0; }
    .sirs-row:hover td { background-color: #f8fafc; }
    .sirs-row:last-child td:first-child { border-bottom-left-radius: 12px; }
    .sirs-row:last-child td:last-child { border-bottom-right-radius: 12px; }

    /* Micro-Badges Medis (Tegas & Presisi) */
    .med-badge { display: inline-flex; align-items: center; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.65rem; font-weight: 700; white-space: nowrap; border: 1px solid transparent; }
    .med-blue { background: #eff6ff; color: #0369a1; border-color: #bae6fd; }
    .med-green { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
    .med-amber { background: #fffbeb; color: #b45309; border-color: #fde68a; }
    .med-rose { background: #fff1f2; color: #be123c; border-color: #fecdd3; }
    .med-purple { background: #faf5ff; color: #6b21a8; border-color: #e9d5ff; }

    /* CSS Native Animation untuk Empty State (Pengganti Lottie yang kebal Localhost) */
    @keyframes floating-doc { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    @keyframes searching-glass { 0%, 100% { transform: translate(0, 0) rotate(0deg); } 25% { transform: translate(-10px, -10px) rotate(-15deg); } 75% { transform: translate(10px, 10px) rotate(15deg); } }
    .anim-doc { animation: floating-doc 4s ease-in-out infinite; }
    .anim-glass { animation: searching-glass 3s ease-in-out infinite; transform-origin: center; }
</style>
@endpush

@section('content')
<div class="max-w-[1400px] mx-auto fade-in-up pb-12 relative z-10">

    {{-- 1. HEADER (ENTERPRISE LOOK) --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-indigo-50 border border-indigo-100 text-indigo-600 text-[10px] font-black uppercase tracking-widest mb-3">
                <i class="fas fa-server"></i> Modul E-Rekam Medis
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins mb-1">Log Pemeriksaan Fisik</h1>
            <p class="text-slate-500 font-medium text-sm">Pusat data antropometri dan klinis warga terintegrasi.</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('kader.pemeriksaan.create') }}" class="w-full md:w-auto px-6 py-3 bg-indigo-600 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-indigo-700 transition-all shadow-sm flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i> Input Rekam Medis
            </a>
        </div>
    </div>

    {{-- 2. PANEL FILTER & LIVE SEARCH (GRID LAYOUT RAPI) --}}
    @php
        $reqKategori = request('kategori', '');
        $reqStatus   = request('status', '');
    @endphp

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 mb-6">
        <form action="{{ route('kader.pemeriksaan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            
            {{-- Navigasi Kategori (Segmented Control Presisi) --}}
            <div class="md:col-span-5 lg:col-span-6 flex flex-wrap bg-slate-100 p-1 rounded-lg border border-slate-200">
                @foreach([
                    ''          => ['label' => 'Semua', 'icon' => 'fa-border-all'],
                    'balita'    => ['label' => 'Balita', 'icon' => 'fa-child'],
                    'ibu_hamil' => ['label' => 'Ibu Hamil', 'icon' => 'fa-female'],
                    'remaja'    => ['label' => 'Remaja', 'icon' => 'fa-user-graduate'],
                    'lansia'    => ['label' => 'Lansia', 'icon' => 'fa-wheelchair']
                ] as $val => $data)
                    <button type="submit" name="kategori" value="{{ $val }}" class="flex-1 px-3 py-2 rounded-md text-[10px] font-black uppercase tracking-widest transition-all {{ $reqKategori === $val ? 'bg-white text-indigo-600 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-700' }}">
                        <i class="fas {{ $data['icon'] }} mr-1 {{ $reqKategori === $val ? 'text-indigo-500' : 'opacity-40' }}"></i> {{ $data['label'] }}
                    </button>
                @endforeach
                <input type="hidden" name="status" value="{{ $reqStatus }}">
            </div>

            {{-- Filter Status --}}
            <div class="md:col-span-3 lg:col-span-3">
                <select name="status" onchange="this.form.submit()" class="form-control-modern w-full text-xs font-bold rounded-lg px-4 py-2.5 h-full cursor-pointer">
                    <option value="">-- Status Validasi Medis --</option>
                    <option value="pending" {{ $reqStatus == 'pending' ? 'selected' : '' }}>Menunggu Bidan</option>
                    <option value="tervalidasi" {{ $reqStatus == 'tervalidasi' ? 'selected' : '' }}>Tervalidasi Bidan</option>
                    <option value="ditolak" {{ $reqStatus == 'ditolak' ? 'selected' : '' }}>Ditolak / Revisi</option>
                </select>
            </div>

            {{-- Live Search Input --}}
            <div class="md:col-span-4 lg:col-span-3 relative flex items-center h-full">
                <i class="fas fa-search absolute left-4 text-slate-400 text-xs"></i>
                <input type="text" id="liveSearchInput" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIK..." class="form-control-modern w-full text-xs font-bold rounded-lg pl-9 pr-4 py-2.5 h-full placeholder:text-slate-400 placeholder:font-normal">
            </div>

        </form>
    </div>

    {{-- 3. DATA GRID (TABEL PROFESIONAL) --}}
    <div class="overflow-x-auto relative z-10 pb-10" style="scrollbar-width: thin;">
        <table class="sirs-table min-w-[1000px]">
            <thead>
                <tr>
                    <th class="text-left w-36">Waktu & Kategori</th>
                    <th class="text-left w-64">Identitas Pasien</th>
                    <th class="text-left">Hasil Pengukuran Medis</th>
                    <th class="text-center w-36">Status Validasi</th>
                    <th class="text-right w-28">Tindakan</th>
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
                
                {{-- Row dengan Atribut Live Search --}}
                <tr class="sirs-row med-row" data-search="{{ strtolower($namaPasien . ' ' . $nikPasien) }}">
                    
                    {{-- 1. WAKTU --}}
                    <td>
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-bold text-slate-800">{{ $item->tanggal_periksa->format('d M Y') }}</span>
                            <span class="text-[10px] font-bold text-slate-500">{{ $item->tanggal_periksa->format('H:i') }} WIB</span>
                            <span class="inline-block mt-1 bg-slate-100 text-slate-600 text-[9px] font-bold uppercase px-2 py-0.5 rounded border border-slate-200 w-max">
                                {{ $kategori }}
                            </span>
                        </div>
                    </td>

                    {{-- 2. IDENTITAS --}}
                    <td>
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shrink-0">
                                {{ strtoupper(substr($namaPasien, 0, 1)) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800 truncate max-w-[200px]" title="{{ $namaPasien }}">{{ $namaPasien }}</span>
                                <span class="text-xs text-slate-500 font-mono mt-0.5">NIK: {{ $nikPasien }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- 3. HASIL FISIK (Micro Badges Rapi) --}}
                    <td class="whitespace-normal">
                        <div class="flex flex-wrap gap-1.5 max-w-[380px]">
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
                                <span class="text-xs text-slate-400 italic">Belum ada input klinis.</span>
                            @endif
                        </div>
                    </td>

                    {{-- 4. STATUS --}}
                    <td class="text-center">
                        <div class="inline-flex flex-col items-center justify-center py-1.5 px-3 rounded-md bg-{{ $badgeColor }}-50 border border-{{ $badgeColor }}-200 w-max mx-auto">
                            <i class="fas {{ $iconStatus }} text-{{ $badgeColor }}-500 text-sm mb-1"></i>
                            <span class="text-[9px] font-black text-{{ $badgeColor }}-600 uppercase tracking-wide">{{ $statusText }}</span>
                        </div>
                    </td>

                    {{-- 5. AKSI --}}
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('kader.pemeriksaan.show', $item->id) }}" class="w-8 h-8 rounded-md bg-white border border-slate-200 text-indigo-500 hover:bg-indigo-50 hover:border-indigo-300 flex items-center justify-center transition-colors" title="Detail Medis"><i class="fas fa-file-medical text-sm"></i></a>
                            
                            <a href="{{ route('kader.pemeriksaan.edit', $item->id) }}" class="w-8 h-8 rounded-md bg-white border border-slate-200 text-amber-500 hover:bg-amber-50 hover:border-amber-300 flex items-center justify-center transition-colors" title="Edit Data"><i class="fas fa-pen text-sm"></i></a>
                            
                            <form action="{{ route('kader.pemeriksaan.destroy', $item->id) }}" method="POST" class="delete-form m-0 p-0">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-delete w-8 h-8 rounded-md bg-white border border-slate-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 flex items-center justify-center transition-colors" title="Hapus"><i class="fas fa-trash-alt text-sm"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                @endforelse

                {{-- 4. EMPTY STATE BERSATU DENGAN TABEL (DENGAN NATIVE SVG ANIMATION ANTI-ERROR) --}}
                <tr id="emptyStateRow" style="{{ (isset($pemeriksaans) && count($pemeriksaans) > 0) ? 'display:none;' : '' }}">
                    <td colspan="5" class="py-20 text-center border-b border-l border-r border-slate-200 bg-white rounded-b-xl">
                        <div class="flex flex-col items-center justify-center max-w-md mx-auto">
                            
                            {{-- Native SVG Animation (Pengganti Lottie yang 100% Kebal Localhost) --}}
                            <div class="relative w-40 h-40 mb-6 flex items-center justify-center">
                                <div class="absolute inset-0 bg-indigo-50 rounded-full blur-xl"></div>
                                
                                {{-- Ikon Papan Dada (Clipboard) --}}
                                <svg class="anim-doc w-24 h-24 text-indigo-200 relative z-10" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3H14.82C14.4 1.84 13.3 1 12 1S9.6 1.84 9.18 3H5C3.9 3 3 3.9 3 5V19C3 20.1 3.9 21 5 21H19C20.1 21 21 20.1 21 19V5C21 3.9 20.1 3 19 3ZM12 3C12.55 3 13 3.45 13 4C13 4.55 12.55 5 12 5C11.45 5 11 4.55 11 4C11 3.45 11.45 3 12 3ZM19 19H5V5H7V7H17V5H19V19Z"></path>
                                    <path d="M11 10H7V12H11V10Z" fill="#6366f1"></path>
                                    <path d="M17 10H13V12H17V10Z" fill="#818cf8"></path>
                                    <path d="M17 14H7V16H17V14Z" fill="#818cf8"></path>
                                </svg>
                                
                                {{-- Ikon Kaca Pembesar Melayang --}}
                                <svg class="anim-glass absolute right-2 bottom-4 w-16 h-16 text-indigo-500 z-20 drop-shadow-lg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21L15.05 15.05M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"></path>
                                </svg>
                            </div>

                            <h4 class="text-lg font-bold text-slate-800 mb-2">Rekam Medis Tidak Ditemukan</h4>
                            <p class="text-sm text-slate-500 mb-6">Sistem tidak menemukan data pemeriksaan yang sesuai dengan filter pencarian Anda saat ini.</p>
                            <a href="{{ route('kader.pemeriksaan.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-300 text-indigo-600 font-bold text-xs rounded-lg hover:bg-indigo-50 transition-colors shadow-sm">
                                <i class="fas fa-plus"></i> Input Pasien Baru
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Pagination UI --}}
    @if(isset($pemeriksaans) && count($pemeriksaans) > 0 && method_exists($pemeriksaans, 'links'))
    <div class="mt-4 flex justify-end">
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

    // 2. FITUR HAPUS DENGAN SWEETALERT
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Hapus Rekam Medis?',
                html: '<p class="text-sm text-slate-500">Log pemeriksaan fisik ini akan dihapus secara permanen dari database.</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e', 
                cancelButtonColor: '#cbd5e1',  
                confirmButtonText: 'Ya, Hapus Data',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl border border-slate-100 shadow-xl' },
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // 3. NOTIFIKASI BERHASIL
    @if(session('success'))
        Swal.fire({
            icon: 'success', title: 'Berhasil', text: "{{ session('success') }}",
            confirmButtonColor: '#10b981', timer: 3000, showConfirmButton: false, customClass: { popup: 'rounded-2xl' }
        });
    @endif
    @if(session('error'))
        Swal.fire({
            icon: 'error', title: 'Aksi Gagal', text: "{{ session('error') }}",
            confirmButtonColor: '#f43f5e', customClass: { popup: 'rounded-2xl' }
        });
    @endif
</script>
@endpush
@endsection