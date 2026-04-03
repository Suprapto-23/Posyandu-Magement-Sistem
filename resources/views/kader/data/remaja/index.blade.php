@extends('layouts.kader')

@section('title', 'Data Remaja')
@section('page-name', 'Database Remaja')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.05); }
    
    /* Custom Checkbox */
    .checkbox-modern {
        appearance: none; width: 20px; height: 20px; border: 2px solid #cbd5e1; border-radius: 6px; 
        background: #f8fafc; cursor: pointer; transition: all 0.2s; position: relative; display: inline-block;
    }
    .checkbox-modern:checked { background: #6366f1; border-color: #6366f1; }
    .checkbox-modern:checked::after {
        content: '✔'; position: absolute; color: white; font-size: 12px; top: 50%; left: 50%; transform: translate(-50%, -50%);
    }

    .badge-jk-L { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .badge-jk-P { background: #fdf2f8; color: #db2777; border: 1px solid #fbcfe8; }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-white/90 backdrop-blur-sm z-[9999] flex flex-col items-center justify-center transition-all duration-200 opacity-100 pointer-events-auto">
    <div class="relative w-16 h-16 mb-4">
        <div class="absolute inset-0 border-4 border-slate-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-indigo-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="absolute inset-0 flex items-center justify-center text-indigo-500"><i class="fas fa-user-graduate text-lg animate-pulse"></i></div>
    </div>
</div>

<div class="max-w-[1400px] mx-auto animate-slide-up">
    
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-8">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center text-3xl shadow-[0_8px_20px_rgba(79,70,229,0.3)] shrink-0">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 font-poppins tracking-tight mb-1">Database Remaja</h1>
                <p class="text-sm font-bold text-slate-500">Kelola profil, pendidikan, dan kontak usia produktif (10-19 th).</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('kader.import.index') }}" class="px-5 py-2.5 bg-emerald-50 text-emerald-600 font-extrabold text-sm rounded-xl hover:bg-emerald-100 transition-colors border border-emerald-200 shadow-sm">
                <i class="fas fa-file-excel mr-1.5"></i> Import Excel
            </a>
            <a href="{{ route('kader.data.remaja.create') }}" class="px-5 py-2.5 bg-indigo-600 text-white font-extrabold text-sm rounded-xl hover:bg-indigo-700 shadow-[0_4px_12px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all">
                <i class="fas fa-plus mr-1.5"></i> Tambah Data
            </a>
        </div>
    </div>

    {{-- STATS, SEARCH & BULK ACTION --}}
    <div class="glass-card rounded-3xl p-4 mb-6 flex flex-col xl:flex-row items-center gap-4 justify-between">
        
        <div class="flex items-center gap-4 w-full xl:w-auto">
            <div class="flex items-center gap-3 px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-2xl">
                <i class="fas fa-chart-pie text-indigo-400 text-xl"></i>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-wider text-indigo-400">Total Terdaftar</p>
                    <p class="text-lg font-black text-indigo-700 leading-none">{{ $remajas->total() }} <span class="text-xs font-bold text-indigo-500">Anak</span></p>
                </div>
            </div>
            
            {{-- Tombol Hapus Banyak (Tersembunyi by default) --}}
            <form action="{{ route('kader.data.remaja.bulk-delete') }}" method="POST" id="bulkDeleteForm" class="hidden">
                @csrf
                <div id="bulkDeleteInputs"></div>
                <button type="button" onclick="confirmBulkDelete()" class="px-5 py-3 bg-rose-500 text-white font-black text-sm rounded-xl hover:bg-rose-600 shadow-[0_4px_12px_rgba(225,29,72,0.3)] transition-all flex items-center gap-2 animate-pulse">
                    <i class="fas fa-trash-alt"></i> Hapus <span id="bulkCount">0</span> Terpilih
                </button>
            </form>
        </div>
        
        <form action="{{ route('kader.data.remaja.index') }}" method="GET" class="w-full xl:w-1/3 relative group">
            <input type="text" name="search" id="liveSearchInput" value="{{ request('search') }}" placeholder="Cari Nama, NIK, atau Sekolah..." 
                   class="w-full bg-slate-50 border border-slate-200 rounded-full py-3 pl-12 pr-4 text-sm font-bold text-slate-700 outline-none transition-all focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 shadow-sm placeholder:text-slate-400">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
        </form>
    </div>

    {{-- TABEL DATA REMAJA --}}
    <div id="tableContainer">
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase tracking-widest text-slate-500 font-black">
                            <th class="px-4 py-4 text-center w-10"><input type="checkbox" class="checkbox-modern select-all-remaja" onclick="toggleSelectAll(this)"></th>
                            <th class="px-4 py-4">Identitas Remaja</th>
                            <th class="px-4 py-4">Info Sekolah & TTL</th>
                            <th class="px-4 py-4">Kontak Keluarga</th>
                            <th class="px-4 py-4 text-center">Usia & Akun</th>
                            <th class="px-4 py-4 text-right pr-6">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($remajas as $item)
                        @php 
                            $umur = \Carbon\Carbon::parse($item->tanggal_lahir)->age;
                        @endphp
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-4 py-4 text-center"><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="checkbox-modern row-checkbox" onchange="checkBulkStatus()"></td>
                            
                            {{-- Identitas --}}
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-black text-lg shrink-0 {{ $item->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600' }}">
                                        {{ substr($item->nama_lengkap, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-extrabold text-slate-800">{{ $item->nama_lengkap }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] font-black tracking-wider px-2 py-0.5 rounded-md {{ $item->jenis_kelamin == 'L' ? 'badge-jk-L' : 'badge-jk-P' }}">
                                                {{ $item->jenis_kelamin == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}
                                            </span>
                                            <span class="text-[11px] font-bold text-slate-500"><i class="far fa-id-card"></i> {{ $item->nik ?? 'Belum Ada NIK' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Sekolah & Kelahiran --}}
                            <td class="px-4 py-4">
                                <p class="text-xs font-bold text-slate-700 mb-1 flex items-center gap-1.5"><i class="fas fa-school text-indigo-400"></i> {{ $item->sekolah ?? 'Tidak Sekolah' }} {!! $item->kelas ? "<span class='text-slate-400'>| Kls: {$item->kelas}</span>" : '' !!}</p>
                                <p class="text-[11px] font-bold text-slate-500"><i class="fas fa-map-marker-alt text-rose-400 w-3 text-center"></i> {{ $item->tempat_lahir }}, {{ \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d M Y') }}</p>
                            </td>

                            {{-- Orang Tua & Kontak --}}
                            <td class="px-4 py-4">
                                <p class="text-xs font-bold text-slate-800 mb-0.5"><i class="fas fa-user-friends text-emerald-400 w-4"></i> {{ $item->nama_ortu ?? 'Data Ortu Kosong' }}</p>
                                @if($item->telepon_ortu)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->telepon_ortu) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 hover:bg-emerald-100 transition-colors">
                                        <i class="fab fa-whatsapp"></i> {{ $item->telepon_ortu }}
                                    </a>
                                @else
                                    <p class="text-[10px] font-bold text-slate-400"><i class="fas fa-phone-slash"></i> Tidak ada telepon</p>
                                @endif
                            </td>

                            {{-- Usia & Akun --}}
                            <td class="px-4 py-4 text-center">
                                <span class="block mb-2 w-max mx-auto px-3 py-1 rounded-lg text-[11px] font-black bg-indigo-100 text-indigo-700 border border-indigo-200 shadow-sm transition-transform hover:scale-105">
                                    <i class="fas fa-birthday-cake text-indigo-400 mr-1"></i> {{ $umur }} TAHUN
                                </span>
                                @if($item->user_id)
                                    <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-100"><i class="fas fa-check-circle"></i> Akun Tertaut</span>
                                @else
                                    <a href="{{ route('kader.data.remaja.sync', $item->id) }}" class="text-[10px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-md border border-amber-200 hover:bg-amber-100 transition-colors"><i class="fas fa-sync"></i> Tarik Akun</a>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-4 pr-6">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('kader.data.remaja.show', $item->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-indigo-500 hover:bg-indigo-50 hover:border-indigo-300 flex items-center justify-center transition-all shadow-sm" title="Lihat Rekam Medis"><i class="fas fa-file-medical"></i></a>
                                    <a href="{{ route('kader.data.remaja.edit', $item->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-amber-500 hover:bg-amber-50 hover:border-amber-300 flex items-center justify-center transition-all shadow-sm" title="Edit Data"><i class="fas fa-pen"></i></a>
                                    
                                    <form action="{{ route('kader.data.remaja.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmSingleDelete('{{ $item->id }}', '{{ $item->nama_lengkap }}')" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 flex items-center justify-center transition-all shadow-sm" title="Hapus Data"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 text-4xl shadow-inner border border-slate-100"><i class="fas fa-user-slash"></i></div>
                                <h3 class="font-black text-slate-800 text-xl">Database Kosong</h3>
                                <p class="text-sm text-slate-500 mt-2">Belum ada data atau hasil pencarian tidak ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($remajas->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $remajas->links() }}
            </div>
            @endif
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Matikan Loader Cepat
    window.onload = () => {
        const l = document.getElementById('smoothLoader');
        if(l) { l.classList.remove('opacity-100','pointer-events-auto'); l.classList.add('opacity-0','pointer-events-none'); setTimeout(()=> l.style.display = 'none', 200); }
    };

    // ==========================================
    // SISTEM NOTIFIKASI MODERN & HAPUS DATA
    // ==========================================
    const Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
        timerProgressBar: true, didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
    });

    @if(session('success'))
        Toast.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}" });
    @endif
    @if(session('error'))
        Toast.fire({ icon: 'error', title: 'Oops...', text: "{{ session('error') }}" });
    @endif

    function confirmSingleDelete(id, name) {
        Swal.fire({
            title: 'Hapus ' + name + '?',
            html: "Data rekam medis dan absensi remaja ini akan <b>hilang permanen!</b>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // ==========================================
    // SISTEM CHECKBOX & HAPUS BANYAK (BULK DELETE)
    // ==========================================
    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll(`.row-checkbox`);
        checkboxes.forEach(cb => cb.checked = source.checked);
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

    function confirmBulkDelete() {
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if(checkedBoxes.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checkedBoxes.length + ' Data Terpilih?',
            html: "Anda akan menghapus data remaja dalam jumlah banyak sekaligus. Ini tidak bisa dibatalkan!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fas fa-skull-crossbones"></i> Eksekusi Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-2xl border-4 border-rose-100' }
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

    // ==========================================
    // LIVE SEARCH AJAX (Sangat Ngebut)
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchInput');
        if(!searchInput) return;
        const form = searchInput.closest('form');
        let timeout = null;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const query = this.value;
                const url = `${form.action}?search=${encodeURIComponent(query)}`;
                
                document.getElementById('tableContainer').style.opacity = '0.5';
                
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    document.getElementById('tableContainer').innerHTML = doc.getElementById('tableContainer').innerHTML;
                    document.getElementById('tableContainer').style.opacity = '1';
                })
                .catch(err => {
                    console.error('Error fetching data:', err);
                    document.getElementById('tableContainer').style.opacity = '1';
                });
            }, 400);
        });
    });
</script>
@endsection