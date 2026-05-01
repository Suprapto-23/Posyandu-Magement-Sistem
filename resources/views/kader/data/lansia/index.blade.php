@extends('layouts.kader')

@section('title', 'Data Lansia')
@section('page-name', 'Database Lansia')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* ANIMASI MASUK HALUS */
    .fade-in-up { animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }

    /* CHECKBOX CRM STYLE */
    .crm-checkbox {
        appearance: none; width: 20px; height: 20px; border: 2px solid #cbd5e1; border-radius: 6px; 
        background: #ffffff; cursor: pointer; transition: all 0.2s ease; position: relative;
    }
    .crm-checkbox:hover { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
    .crm-checkbox:checked { background: #10b981; border-color: #10b981; }
    .crm-checkbox:checked::after {
        content: '\f00c'; font-family: 'Font Awesome 5 Free'; font-weight: 900; position: absolute; 
        color: white; font-size: 11px; top: 50%; left: 50%; transform: translate(-50%, -50%);
    }

    /* INPUT PENCARIAN CRM */
    .crm-search {
        width: 100%; background-color: #ffffff; border: 1px solid #e2e8f0; color: #1e293b;
        font-size: 0.85rem; font-weight: 600; border-radius: 9999px; padding: 0.75rem 1.5rem 0.75rem 3rem;
        outline: none; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .crm-search:focus { border-color: #10b981; box-shadow: 0 4px 20px -3px rgba(16, 185, 129, 0.15); }

    /* TABEL CRM KAPSUL */
    .crm-card { background: #ffffff; border: 1px solid #f1f5f9; border-radius: 28px; box-shadow: 0 10px 40px -10px rgba(16, 185, 129, 0.05); overflow: hidden; padding: 4px; }
    .crm-table { width: 100%; border-collapse: collapse; text-align: left; }
    .crm-table th { background: #f0fdf4; color: #059669; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; padding: 1.25rem 1rem; border-bottom: 1px solid #dcfce7; }
    .crm-table th:first-child { border-top-left-radius: 24px; }
    .crm-table th:last-child { border-top-right-radius: 24px; }
    .crm-table td { padding: 1.25rem 1rem; vertical-align: middle; border-bottom: 1px solid #f8fafc; transition: all 0.2s ease; }
    .crm-table tr:hover td { background-color: #f8fafc; }
    .crm-table tr:last-child td { border-bottom: none; }

    /* CUSTOM SCROLLBAR HALUS */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(203, 213, 225, 0.4); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(203, 213, 225, 0.8); }

    /* ACTION BUTTONS (GAYA NEXUS) */
    .action-btn { width: 36px; height: 36px; border-radius: 12px; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; border: 1px solid transparent; background: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    .action-btn.sync:hover { background: #10b981; color: white; border-color: #059669; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); transform: translateY(-2px); }
    .action-btn.view:hover { background: #4f46e5; color: white; border-color: #4338ca; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3); transform: translateY(-2px); }
    .action-btn.edit:hover { background: #f59e0b; color: white; border-color: #d97706; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transform: translateY(-2px); }
    .action-btn.delete:hover { background: #f43f5e; color: white; border-color: #e11d48; box-shadow: 0 4px 15px rgba(244, 63, 94, 0.3); transform: translateY(-2px); }

    /* SWEETALERT CUSTOM */
    div:where(.swal2-container) { z-index: 10000 !important; backdrop-filter: blur(8px) !important; background: rgba(15, 23, 42, 0.4) !important; }
    .swal2-popup:not(.swal2-toast) { border-radius: 32px !important; padding: 2.5rem 2rem !important; background: rgba(255, 255, 255, 0.98) !important; border: 1px solid rgba(255,255,255,0.5) !important; width: 28em !important; box-shadow: 0 20px 60px -15px rgba(0,0,0,0.1) !important; }
    .swal2-popup .swal2-title { font-family: 'Poppins', sans-serif !important; font-weight: 900 !important; font-size: 1.5rem !important; color: #1e293b !important; }
    .btn-nexus-cancel { background: #f1f5f9 !important; color: #64748b !important; border-radius: 100px !important; padding: 12px 24px !important; font-size: 12px !important; font-weight: 800 !important; text-transform: uppercase !important; transition: all 0.3s ease !important; }
    .btn-nexus-danger { background: #f43f5e !important; color: #ffffff !important; border-radius: 100px !important; padding: 12px 28px !important; font-size: 12px !important; font-weight: 800 !important; text-transform: uppercase !important; box-shadow: 0 8px 20px rgba(244,63,94,0.3) !important; transition: all 0.3s ease !important; }
</style>
@endpush

@section('content')
{{-- PRELOADER SISTEM --}}
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-500 opacity-100 pointer-events-auto">
    <div class="relative w-16 h-16 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-emerald-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-emerald-600 rounded-full border-t-transparent animate-spin"></div>
        <i class="fas fa-user-clock text-emerald-600 text-xl"></i>
    </div>
    <p class="text-[11px] font-black tracking-[0.2em] text-slate-500 uppercase">Memuat Pangkalan Data</p>
</div>

<div class="max-w-[1400px] mx-auto fade-in-up pb-12 relative z-10">

    {{-- AURA BACKGROUND (EMERALD/TEAL) --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-emerald-400/20 to-teal-400/20 rounded-full blur-[80px] pointer-events-none z-0"></div>

    {{-- 1. HEADER CRM --}}
    <div class="bg-white/90 backdrop-blur-2xl rounded-[32px] border border-white/50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 mb-8 relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6 z-10">
        
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-2xl"></div>

        <div class="flex items-center gap-6 relative z-10 w-full md:w-auto">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center text-3xl shadow-[0_8px_20px_rgba(16,185,129,0.3)] shrink-0 transform -rotate-3 transition-transform hover:rotate-0">
                <i class="fas fa-user-clock drop-shadow-sm"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest border border-emerald-200 bg-emerald-50 px-2.5 py-1 rounded-md">Automasi Sistem Aktif</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins mb-1">Database Lansia</h1>
                <p class="text-slate-500 font-medium text-[13px] max-w-md">Kelola master data peserta Posyandu Lansia, riwayat kesehatan, dan kemandirian.</p>
            </div>
        </div>
        
        <div class="relative z-10 shrink-0 w-full md:w-auto flex flex-col sm:flex-row gap-4">
            <a href="{{ route('kader.import.index') }}?type=lansia" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-4 bg-white border border-slate-200 text-emerald-600 font-black text-[12px] uppercase tracking-widest rounded-[16px] hover:bg-emerald-50 hover:border-emerald-200 transition-all shadow-sm">
                <i class="fas fa-file-import text-sm"></i> Import Massal
            </a>
            <a href="{{ route('kader.data.lansia.create') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-8 py-4 bg-emerald-600 text-white font-black text-[12px] uppercase tracking-widest rounded-[16px] hover:bg-emerald-700 transition-all shadow-[0_8px_20px_rgba(16,185,129,0.3)] hover:-translate-y-1 hover:shadow-[0_12px_25px_rgba(16,185,129,0.4)]">
                <i class="fas fa-plus text-sm text-emerald-200"></i> Registrasi Baru
            </a>
        </div>
    </div>

    {{-- 2. CRM CONTROL PANEL (NAVIGASI & FILTER) --}}
    <div class="flex flex-col xl:flex-row items-center justify-between gap-5 mb-6 relative z-20">
        
        <div class="flex items-center gap-4 w-full xl:w-auto">
            {{-- Badge Statistik Minimalis --}}
            <div class="inline-flex items-center gap-2 bg-white border border-slate-200 px-6 py-3.5 rounded-full shadow-[0_4px_15px_rgba(0,0,0,0.02)]">
                <i class="fas fa-users text-emerald-500 text-sm"></i>
                <span class="text-[11px] font-black text-slate-700 uppercase tracking-widest">Total: {{ $stats['total'] ?? 0 }} Lansia</span>
            </div>

            {{-- Tombol Bulk Delete (Muncul Dinamis) --}}
            <form action="{{ route('kader.data.lansia.bulk-delete') }}" method="POST" id="bulkDeleteForm" class="hidden w-full sm:w-auto">
                @csrf
                <div id="bulkDeleteInputs"></div>
                <button type="button" onclick="confirmBulkDelete()" class="w-full sm:w-auto px-6 py-3.5 bg-rose-50 border border-rose-200 text-rose-600 font-black text-[11px] uppercase tracking-widest rounded-full hover:bg-rose-500 hover:text-white transition-all shadow-sm hover:shadow-[0_8px_20px_rgba(244,63,94,0.3)] flex items-center justify-center gap-2">
                    <i class="fas fa-trash-alt"></i> Eksekusi Hapus (<span id="bulkCount">0</span>)
                </button>
            </form>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-4 w-full xl:w-auto">
            {{-- Live Search Kapsul --}}
            <div class="relative w-full sm:w-[400px] group">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm group-focus-within:text-emerald-500 transition-colors"></i>
                <input type="text" id="liveSearchInput" placeholder="Ketik Nama, NIK, atau Penyakit..." class="crm-search" autocomplete="off">
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- TABEL DATABASE LANSIA --}}
    {{-- ======================================================== --}}
    <div class="crm-card relative z-10">
        <div class="overflow-x-auto custom-scrollbar" style="min-h: 300px;">
            <table class="crm-table min-w-[1200px]">
                <thead>
                    <tr>
                        <th class="w-12 text-center pl-6"><input type="checkbox" class="crm-checkbox select-all-btn" onclick="toggleSelectAll(this)"></th>
                        <th class="w-56">Identitas Lansia</th>
                        <th class="w-48">Kesehatan Dasar</th>
                        <th class="w-48">Keluarga & Kontak</th>
                        <th class="w-32 text-center">Status Akun</th>
                        <th class="w-40 text-center pr-6">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lansias as $item)
                    @php 
                        $diff = \Carbon\Carbon::parse($item->tanggal_lahir)->diff(now());
                        $strUmur = $diff->y . ' Thn';
                        $avatarColor = $item->jenis_kelamin == 'L' ? 'bg-sky-50 text-sky-500 border-sky-200' : 'bg-rose-50 text-rose-500 border-rose-200';
                        $jkBadge = $item->jenis_kelamin == 'L' ? 'bg-sky-50 text-sky-600 border-sky-200' : 'bg-rose-50 text-rose-600 border-rose-200';
                        
                        $imtClass = 'bg-slate-100 text-slate-500';
                        if($item->imt) {
                            if($item->imt < 18.5) $imtClass = 'bg-amber-50 text-amber-600 border-amber-200';
                            elseif($item->imt < 25) $imtClass = 'bg-emerald-50 text-emerald-600 border-emerald-200';
                            else $imtClass = 'bg-rose-50 text-rose-600 border-rose-200';
                        }
                    @endphp
                    <tr class="pasien-row hover:bg-slate-50/50 transition-colors" data-search="{{ strtolower($item->nama_lengkap . ' ' . $item->nik . ' ' . $item->penyakit_bawaan) }}">
                        
                        <td class="text-center pl-6"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="crm-checkbox row-checkbox" onchange="checkBulkStatus()"></td>
                        
                        {{-- 1. IDENTITAS --}}
                        <td>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-[12px] flex items-center justify-center font-black text-sm shrink-0 border shadow-sm {{ $avatarColor }}">
                                    {{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-black text-slate-800 font-poppins truncate max-w-[150px]" title="{{ $item->nama_lengkap }}">{{ $item->nama_lengkap }}</span>
                                    <div class="flex items-center gap-1 mt-1">
                                        <span class="text-[10px] font-bold text-slate-400 font-mono">{{ $item->nik ?? '-' }}</span>
                                        <span class="text-[10px] font-bold text-slate-300">•</span>
                                        <span class="badge-mini border {{ $jkBadge }}" style="padding: 2px 6px; font-size:8px;">{{ $item->jenis_kelamin == 'L' ? 'PRIA' : 'WANITA' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- 2. KESEHATAN DASAR --}}
                        <td>
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm">Usia: <span class="text-indigo-600 font-black">{{ $strUmur }}</span></span>
                                    <span class="text-[9px] font-black uppercase tracking-widest border px-2 py-0.5 rounded {{ $imtClass }}">IMT: {{ $item->imt ?? '-' }}</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-600 bg-slate-50 px-2 py-1 rounded border border-slate-100 inline-block line-clamp-1 max-w-[180px]" title="{{ $item->penyakit_bawaan ?? 'Sehat' }}">
                                    <i class="fas fa-notes-medical text-rose-400 mr-1"></i> {{ $item->penyakit_bawaan ?? 'Tidak ada riwayat' }}
                                </span>
                            </div>
                        </td>

                        {{-- 3. DATA KELUARGA & KEMANDIRIAN --}}
                        <td>
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded bg-emerald-50 flex items-center justify-center text-emerald-500 text-[10px] border border-emerald-100"><i class="fas fa-wheelchair"></i></div>
                                    <p class="text-[11px] font-black text-slate-700 uppercase tracking-widest">{{ $item->kemandirian ?? 'MANDIRI' }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded bg-slate-100 flex items-center justify-center text-slate-500 text-[10px] border border-slate-200"><i class="fas fa-phone-alt"></i></div>
                                    <p class="text-[11px] font-semibold text-slate-500">{{ $item->telepon_keluarga ?? '-' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- 4. STATUS AKUN --}}
                        <td class="text-center">
                            @if($item->user_id)
                                <span class="text-[9px] font-black uppercase tracking-widest text-emerald-500"><i class="fas fa-check-circle"></i> Terhubung Akun</span>
                            @else
                                <form action="{{ route('kader.data.lansia.sync', $item->id) }}" method="POST" class="m-0 p-0 inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-amber-600 bg-white border border-amber-300 px-2 py-1 rounded hover:bg-amber-50 transition-all shadow-sm">
                                        <i class="fas fa-satellite-dish animate-pulse"></i> Deteksi Akun
                                    </button>
                                </form>
                            @endif
                        </td>

                        {{-- 5. TINDAKAN --}}
                        <td class="text-center pr-6">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('kader.data.lansia.show', $item->id) }}" class="action-btn view text-emerald-500 border-slate-200" title="Buka Rekam Medis"><i class="fas fa-book-medical text-[12px]"></i></a>
                                <a href="{{ route('kader.data.lansia.edit', $item->id) }}" class="action-btn edit text-amber-500 border-slate-200" title="Edit Profil"><i class="fas fa-pen text-[12px]"></i></a>
                                <form action="{{ route('kader.data.lansia.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}" class="m-0 p-0">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmSingleDelete('{{ $item->id }}', '{{ addslashes($item->nama_lengkap) }}')" class="action-btn delete text-rose-500 border-slate-200" title="Hapus Data"><i class="fas fa-trash-alt text-[12px]"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-state-row">
                        <td colspan="6" class="py-24 text-center border-none">
                            <div class="flex flex-col items-center justify-center max-w-md mx-auto bg-slate-50/50 border border-dashed border-slate-300 rounded-[32px] p-10">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-sm border border-slate-100"><i class="fas fa-user-clock"></i></div>
                                <h4 class="text-lg font-black text-slate-800 uppercase tracking-widest mb-2 font-poppins">Pangkalan Data Kosong</h4>
                                <p class="text-[13px] text-slate-500 font-medium leading-relaxed">Belum ada data peserta Lansia yang tercatat. Gunakan tombol Registrasi Baru atau Import Excel untuk memulai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Custom Pagination --}}
        @if($lansias->hasPages())
        <div class="p-4 border-t border-slate-100 bg-white">
            {{ $lansias->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. Loader Logic
    window.onload = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100','pointer-events-auto'); l.classList.add('opacity-0','pointer-events-none'); setTimeout(()=> l.style.display = 'none', 500); }
    };

    // 2. LIVE SEARCH FILTER
    document.addEventListener('DOMContentLoaded', () => {
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
                        const cb = row.querySelector('.row-checkbox');
                        if(cb && cb.checked) { cb.checked = false; checkBulkStatus(); }
                    }
                });
            });
        }
    });

    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 4000, timerProgressBar: true
    });

    @if(session('success')) Toast.fire({ icon: 'success', title: 'Berhasil!', text: "{!! addslashes(session('success')) !!}" }); @endif
    @if(session('error')) Toast.fire({ icon: 'error', title: 'Gagal', text: "{!! addslashes(session('error')) !!}" }); @endif

    function confirmSingleDelete(id, name) {
        Swal.fire({
            title: 'Hapus Data?',
            html: `Data profil dan catatan rekam medis untuk <b>${name}</b> akan <b style="color:#f43f5e">dihapus permanen</b> dari sistem.`,
            icon: 'warning', iconColor: '#f43f5e', showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Eksekusi Hapus', cancelButtonText: 'Batalkan',
            reverseButtons: true, buttonsStyling: false, 
            customClass: { confirmButton: 'btn-nexus-danger', cancelButton: 'btn-nexus-cancel' }
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById('delete-form-' + id).submit(); }
        });
    }

    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            if(row.style.display !== 'none') { cb.checked = source.checked; }
        });
        checkBulkStatus();
    }

    function checkBulkStatus() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const bulkForm = document.getElementById('bulkDeleteForm');
        const bulkCountSpan = document.getElementById('bulkCount');
        
        if (checkedBoxes.length > 0) {
            bulkForm.classList.remove('hidden');
            bulkForm.classList.add('fade-in-up');
            bulkCountSpan.innerText = checkedBoxes.length;
        } else {
            bulkForm.classList.add('hidden');
            bulkForm.classList.remove('fade-in-up');
        }
    }

    function confirmBulkDelete() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if(checkedBoxes.length === 0) return;

        Swal.fire({
            title: `Hapus ${checkedBoxes.length} Data?`,
            html: `Aksi ini akan <b style="color:#f43f5e">menghapus masal</b> profil peserta Lansia beserta seluruh log pemeriksaannya. Tindakan mutlak.`,
            icon: 'error', iconColor: '#f43f5e', showCancelButton: true,
            confirmButtonText: '<i class="fas fa-skull-crossbones mr-2"></i> Ya, Hapus Masal', cancelButtonText: 'Batalkan',
            reverseButtons: true, buttonsStyling: false,
            customClass: { confirmButton: 'btn-nexus-danger', cancelButton: 'btn-nexus-cancel' }
        }).then((result) => {
            if (result.isConfirmed) {
                const inputContainer = document.getElementById('bulkDeleteInputs');
                inputContainer.innerHTML = ''; 
                
                checkedBoxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden'; input.name = 'ids[]'; input.value = cb.value;
                    inputContainer.appendChild(input);
                });
                
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    }
</script>
@endpush
@endsection