@extends('layouts.kader')

@section('title', 'Data Lansia')
@section('page-name', 'Database Lansia')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

    .glass-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.6); box-shadow: 0 10px 40px -10px rgba(16, 185, 129, 0.08); }
    
    .checkbox-modern {
        appearance: none; width: 22px; height: 22px; border: 2px solid #cbd5e1; border-radius: 6px; 
        background: #f8fafc; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); position: relative; display: inline-block;
    }
    .checkbox-modern:hover { border-color: #34d399; background: #ecfdf5; }
    .checkbox-modern:checked { background: #10b981; border-color: #10b981; transform: scale(1.05); }
    .checkbox-modern:checked::after {
        content: '\f00c'; font-family: 'Font Awesome 5 Free'; font-weight: 900; position: absolute; 
        color: white; font-size: 11px; top: 50%; left: 50%; transform: translate(-50%, -50%);
    }

    .table-container { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100 pointer-events-auto">
    <div class="relative w-20 h-20 mb-4">
        <div class="absolute inset-0 border-4 border-emerald-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-emerald-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="absolute inset-0 flex items-center justify-center text-emerald-500"><i class="fas fa-user-clock text-2xl animate-pulse"></i></div>
    </div>
    <p class="text-sm font-black tracking-widest text-emerald-500 uppercase animate-pulse">Memuat Database...</p>
</div>

<div class="max-w-[1400px] mx-auto animate-slide-up">
    
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center text-3xl shadow-[0_8px_20px_rgba(16,185,129,0.3)] shrink-0 group hover:scale-105 transition-transform">
                <i class="fas fa-user-clock group-hover:-translate-y-1 transition-transform"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight mb-1">Database Lansia</h1>
                <p class="text-sm font-bold text-slate-500">Kelola data peserta Posyandu Lansia, riwayat penyakit, dan kemandirian.</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <a href="{{ route('kader.import.index') }}?type=lansia" class="flex-1 md:flex-none justify-center flex items-center gap-2 px-5 py-3 bg-white text-emerald-600 font-black text-[13px] rounded-xl hover:bg-emerald-50 transition-all border border-emerald-200 shadow-sm hover:shadow-emerald-100 uppercase tracking-widest">
                <i class="fas fa-file-excel text-lg"></i> Import
            </a>
            <a href="{{ route('kader.data.lansia.create') }}" class="flex-1 md:flex-none justify-center flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-black text-[13px] rounded-xl hover:from-emerald-400 hover:to-teal-500 shadow-[0_8px_15px_rgba(16,185,129,0.3)] hover:-translate-y-0.5 transition-all uppercase tracking-widest">
                <i class="fas fa-plus-circle text-lg"></i> Registrasi Baru
            </a>
        </div>
    </div>

    {{-- KENDALI NAVIGASI (SEARCH & BULK DELETE) --}}
    <div class="glass-card rounded-[24px] p-3 mb-6 flex flex-col xl:flex-row items-center gap-4 justify-between relative z-20">
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full xl:w-auto">
            <form action="{{ route('kader.data.lansia.bulk-delete') }}" method="POST" id="bulkDeleteForm" class="hidden w-full sm:w-auto">
                @csrf
                <div id="bulkDeleteInputs"></div>
                <button type="button" onclick="confirmBulkDelete()" class="w-full sm:w-auto px-6 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 font-black text-[13px] uppercase tracking-widest rounded-full hover:bg-rose-500 hover:text-white shadow-sm transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-trash-alt"></i> Hapus (<span id="bulkCount">0</span>)
                </button>
            </form>
        </div>
        
        <div class="w-full xl:w-80 relative group">
            <input type="text" id="liveSearchInput" placeholder="Cari Nama / NIK Lansia..." 
                   class="w-full bg-white border-2 border-slate-200 rounded-full py-3 pl-12 pr-4 text-sm font-bold text-slate-700 outline-none transition-all focus:border-emerald-400 focus:ring-4 focus:ring-emerald-50 shadow-sm placeholder:text-slate-400">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center bg-slate-100 rounded-full group-focus-within:bg-emerald-100 transition-colors">
                <i class="fas fa-search text-xs text-slate-400 group-focus-within:text-emerald-500"></i>
            </div>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-[24px] border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="table-container overflow-x-auto max-h-[65vh]">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[1000px]">
                <thead class="sticky top-0 z-10 bg-slate-50/90 backdrop-blur-sm border-b border-slate-200 shadow-sm">
                    <tr class="text-[10px] uppercase tracking-widest text-slate-500 font-black">
                        <th class="px-5 py-4 text-center w-12 border-r border-slate-200/50"><input type="checkbox" class="checkbox-modern select-all-btn" onclick="toggleSelectAll(this)"></th>
                        <th class="px-5 py-4 border-r border-slate-200/50">Identitas Lansia</th>
                        <th class="px-5 py-4 border-r border-slate-200/50">Kesehatan Dasar</th>
                        <th class="px-5 py-4 border-r border-slate-200/50">Keluarga & Kontak</th>
                        <th class="px-5 py-4 text-center border-r border-slate-200/50">Status Akun</th>
                        <th class="px-5 py-4 text-center">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($lansias as $item)
                    @php 
                        $diff = \Carbon\Carbon::parse($item->tanggal_lahir)->diff(now());
                        $strUmur = $diff->y . ' Thn';
                        $avatarColor = $item->jenis_kelamin == 'L' ? 'bg-sky-100 text-sky-600' : 'bg-rose-100 text-rose-600';
                        $jkBadge = $item->jenis_kelamin == 'L' ? 'bg-sky-50 text-sky-600 border-sky-200' : 'bg-rose-50 text-rose-600 border-rose-200';
                        
                        $imtClass = 'bg-slate-100 text-slate-500';
                        if($item->imt) {
                            if($item->imt < 18.5) $imtClass = 'bg-amber-100 text-amber-700 border-amber-200';
                            elseif($item->imt < 25) $imtClass = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                            else $imtClass = 'bg-rose-100 text-rose-700 border-rose-200';
                        }
                    @endphp
                    <tr class="hover:bg-emerald-50/40 transition-colors pasien-row" data-search="{{ strtolower($item->nama_lengkap . ' ' . $item->nik . ' ' . $item->penyakit_bawaan) }}">
                        <td class="px-5 py-4 text-center border-r border-slate-200/50"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="checkbox-modern row-checkbox" onchange="checkBulkStatus()"></td>
                        
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
                                        <span class="text-[10px] font-bold text-slate-500 font-mono tracking-wide bg-slate-100 px-2 py-0.5 rounded"><i class="far fa-id-card text-slate-400"></i> {{ $item->nik ?? 'TIDAK ADA' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Kesehatan & Fisik --}}
                        <td class="px-5 py-4 border-r border-slate-200/50">
                            <div class="mb-1.5 flex items-center gap-2">
                                <span class="text-[10px] font-bold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm">Usia: <span class="text-indigo-600 font-black">{{ $strUmur }}</span></span>
                                <span class="text-[9px] font-black uppercase tracking-widest border px-2 py-0.5 rounded {{ $imtClass }}">IMT: {{ $item->imt ?? '-' }}</span>
                            </div>
                            <p class="text-[10px] font-bold text-slate-600 bg-slate-50 px-2 py-1 rounded border border-slate-100 inline-block line-clamp-1 max-w-[200px]" title="{{ $item->penyakit_bawaan ?? 'Sehat' }}">
                                <i class="fas fa-notes-medical text-rose-400 mr-1"></i> {{ $item->penyakit_bawaan ?? 'Tidak ada riwayat penyakit' }}
                            </p>
                        </td>

                        {{-- Data Keluarga & Kemandirian --}}
                        <td class="px-5 py-4 border-r border-slate-200/50">
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded bg-emerald-50 flex items-center justify-center text-emerald-500 text-[10px]"><i class="fas fa-wheelchair"></i></div>
                                    <p class="text-[11px] font-black text-slate-700 uppercase tracking-widest">{{ $item->kemandirian ?? 'MANDIRI' }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded bg-slate-100 flex items-center justify-center text-slate-500 text-[10px]"><i class="fas fa-phone-alt"></i></div>
                                    <p class="text-[11px] font-semibold text-slate-500">{{ $item->telepon_keluarga ?? 'Tidak ada kontak' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Status Akun --}}
                        <td class="px-5 py-4 text-center border-r border-slate-200/50">
                            <div class="block">
                                @if($item->user_id)
                                    <span class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200 shadow-sm"><i class="fas fa-link"></i> Terhubung Akun Warga</span>
                                @else
                                    <a href="{{ route('kader.data.lansia.sync', $item->id) }}" class="inline-flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest text-amber-600 bg-white border border-amber-300 px-2.5 py-1 rounded-md hover:bg-amber-50 hover:border-amber-400 transition-all shadow-sm"><i class="fas fa-satellite-dish animate-pulse"></i> Deteksi Akun (NIK)</a>
                                @endif
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('kader.data.lansia.show', $item->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-indigo-500 hover:bg-indigo-50 hover:text-indigo-600 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Buka Rekam Medis"><i class="fas fa-book-medical"></i></a>
                                <a href="{{ route('kader.data.lansia.edit', $item->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:bg-slate-50 hover:text-slate-800 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Edit Profil"><i class="fas fa-edit"></i></a>
                                
                                <form action="{{ route('kader.data.lansia.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmSingleDelete('{{ $item->id }}', '{{ addslashes($item->nama_lengkap) }}')" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-rose-400 hover:bg-rose-500 hover:text-white hover:border-rose-500 flex items-center justify-center transition-all shadow-sm hover:shadow-md" title="Hapus Permanen"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-3xl shadow-inner border border-slate-100"><i class="fas fa-user-clock"></i></div>
                            <h3 class="font-black text-slate-800 text-lg">Database Lansia Kosong</h3>
                            <p class="text-sm text-slate-500 mt-1">Gunakan tombol Registrasi Baru atau Import Excel untuk mendata peserta.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($lansias->hasPages())
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50">
            {{ $lansias->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.hideLoader = function() {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100', 'pointer-events-auto'); l.classList.add('opacity-0', 'pointer-events-none'); setTimeout(() => l.style.display = 'none', 300); }
    };
    document.addEventListener('DOMContentLoaded', window.hideLoader);
    window.addEventListener('load', window.hideLoader);
    setTimeout(window.hideLoader, 2000);

    // LIVE SEARCH LOGIC
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

    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
        timerProgressBar: true, didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
    });

    @if(session('success')) Toast.fire({ icon: 'success', title: 'Berhasil!', text: "{!! addslashes(session('success')) !!}" }); @endif
    @if(session('error')) Toast.fire({ icon: 'error', title: 'Oops...', text: "{!! addslashes(session('error')) !!}" }); @endif

    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            if(row.style.display !== 'none') cb.checked = source.checked;
        });
        checkBulkStatus();
    }

    function checkBulkStatus() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        const bulkForm = document.getElementById('bulkDeleteForm');
        const bulkCountSpan = document.getElementById('bulkCount');
        
        if (checkedBoxes.length > 0) {
            bulkForm.classList.remove('hidden');
            bulkCountSpan.innerText = checkedBoxes.length;
        } else {
            bulkForm.classList.add('hidden');
        }
    }

    function confirmSingleDelete(id, name) {
        Swal.fire({
            title: 'Hapus ' + name + '?',
            html: "Data master dan rekam medis lansia ini akan <b>hilang permanen!</b>",
            icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus', cancelButtonText: 'Batal',
            reverseButtons: true, customClass: { popup: 'rounded-3xl border border-slate-100' }
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
        });
    }

    function confirmBulkDelete() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if(checkedBoxes.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checkedBoxes.length + ' Lansia Terpilih?',
            html: "Aksi ini akan menghapus data masal beserta seluruh log kehadiran dan pemeriksaannya.",
            icon: 'error', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fas fa-skull-crossbones"></i> Eksekusi Hapus', cancelButtonText: 'Batal',
            reverseButtons: true, customClass: { popup: 'rounded-3xl border-4 border-rose-100 shadow-2xl' }
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