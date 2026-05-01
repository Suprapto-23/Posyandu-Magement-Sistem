@extends('layouts.bidan')

@section('title', 'Kelola Jadwal Posyandu')
@section('page-name', 'Manajemen Jadwal')

@push('styles')
<style>
    /* ANIMASI MASUK HALUS */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    
    /* EFEK HOVER BARIS TABEL (SUPER SMOOTH NEXUS STYLE) */
    .nexus-table-row { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border-bottom: 1px solid #f8fafc; background: #ffffff; }
    .nexus-table-row:last-child { border-bottom: none; }
    .nexus-table-row:hover { 
        background-color: #fcfcfd; 
        box-shadow: 0 15px 35px -5px rgba(6, 182, 212, 0.1); 
        transform: translateY(-2px);
        position: relative; z-index: 10; border-radius: 20px;
        border-color: transparent;
    }

    /* KARTU TANGGAL DI DALAM TABEL */
    .date-card { transition: all 0.3s ease; }
    .nexus-table-row:hover .date-card { border-color: #a5f3fc; background-color: #ecfeff; transform: scale(1.05); }

    /* ==========================================================
       SWEETALERT NEXUS ULTIMATE (ANTI-KAKU / 3D EFFECT)
       ========================================================== */
    .swal2-popup.nexus-swal {
        border-radius: 40px !important; 
        padding: 3.5rem 2.5rem 3rem !important;
        background: rgba(255, 255, 255, 0.98) !important; 
        backdrop-filter: blur(24px) !important;
        border: 1px solid rgba(255,255,255,0.9) !important; 
        box-shadow: 0 40px 80px -15px rgba(15, 23, 42, 0.2) !important;
    }
    
    /* Modifikasi Ikon Success agar terlihat Premium */
    .swal2-icon.swal2-success { 
        border-color: #10b981 !important; color: #10b981 !important; 
        background-color: #ecfdf5 !important; 
        box-shadow: 0 0 0 12px #f0fdf4 !important; /* Efek Halo / Cincin */
        margin-bottom: 2.5rem !important;
    }
    .swal2-icon.swal2-success [class^=swal2-success-line] { background-color: #10b981 !important; }
    .swal2-icon.swal2-success .swal2-success-ring { border-color: rgba(16, 185, 129, 0.15) !important; }
    
    /* Modifikasi Ikon Warning (Hapus) */
    .swal2-icon.swal2-warning { 
        border-color: #f43f5e !important; color: #f43f5e !important; 
        background-color: #fff1f2 !important;
        box-shadow: 0 0 0 12px #fff1f2 !important;
        margin-bottom: 2.5rem !important;
    }

    /* Tipografi SweetAlert */
    .swal2-title.nexus-title { font-family: 'Poppins', sans-serif !important; font-weight: 900 !important; font-size: 26px !important; color: #0f172a !important; margin-bottom: 0.5rem !important; }
    .swal2-html-container.nexus-text { font-weight: 500 !important; font-size: 15px !important; color: #64748b !important; line-height: 1.6 !important; }

    /* KUSTOMISASI SCROLLBAR TABEL */
    .custom-scrollbar::-webkit-scrollbar { height: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; border: 2px solid #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    /* Sembunyikan notifikasi bawaan template */
    #toast-container, .toast, .alert-success, .alert-danger { display: none !important; }
</style>
@endpush

@section('content')
{{-- Loader Sistem --}}
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-20 h-20 flex items-center justify-center mb-5">
        <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
            <i class="fas fa-calendar-alt text-cyan-600 text-xl animate-pulse"></i>
        </div>
    </div>
    <div class="bg-white px-6 py-2.5 rounded-full shadow-sm border border-slate-100 flex items-center gap-3">
        <div class="w-2 h-2 rounded-full bg-cyan-500 animate-ping"></div>
        <p class="text-[11px] font-black text-cyan-700 uppercase tracking-[0.2em] font-poppins" id="loaderText">MEMUAT DATA...</p>
    </div>
</div>

<div class="max-w-[1300px] mx-auto animate-slide-up pb-16">

    {{-- HERO HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-8 bg-white p-6 md:p-8 rounded-[32px] border border-slate-100 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.04)] relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-cyan-50 rounded-bl-full pointer-events-none opacity-60 transition-transform duration-700 hover:scale-110"></div>
        
        <div class="flex items-center gap-5 relative z-10">
            <div class="w-16 h-16 rounded-[20px] bg-gradient-to-br from-cyan-400 to-blue-600 text-white flex items-center justify-center text-3xl shadow-[0_10px_25px_rgba(6,182,212,0.3)] border border-cyan-300 shrink-0">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-[28px] font-black text-slate-800 tracking-tight font-poppins mb-1 leading-none">Jadwal Kegiatan</h1>
                <p class="text-slate-500 font-medium text-[13px] max-w-lg leading-relaxed mt-1">Kelola agenda posyandu dan sistem akan menginformasikannya secara otomatis melalui Notifikasi Push ke warga.</p>
            </div>
        </div>
        <a href="{{ route('bidan.jadwal.create') }}" class="smooth-route inline-flex items-center justify-center gap-3 px-8 py-4 bg-slate-900 text-white font-black text-[12px] uppercase tracking-widest rounded-2xl hover:bg-cyan-600 hover:shadow-[0_15px_30px_rgba(6,182,212,0.3)] hover:-translate-y-1 transition-all duration-300 shrink-0 relative z-10">
            <i class="fas fa-plus-circle text-lg"></i> Buat Jadwal Baru
        </a>
    </div>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-[36px] border border-slate-100 shadow-[0_15px_50px_-15px_rgba(0,0,0,0.05)] flex flex-col overflow-hidden relative z-10">
        
        <div class="px-6 md:px-10 py-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-[14px] bg-white border border-slate-200 text-cyan-600 flex items-center justify-center shadow-sm text-lg"><i class="fas fa-list-ul"></i></div>
                <h3 class="font-black text-slate-800 text-[18px] font-poppins tracking-tight">Daftar Agenda Tersimpan</h3>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar flex-1 p-3 md:p-6">
            <table class="w-full text-left border-collapse min-w-[1050px]">
                <thead>
                    <tr>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 w-16 text-center">No</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Waktu & Tanggal</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Informasi Kegiatan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Target Layanan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Manajemen Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($jadwals as $index => $jadwal)
                    <tr class="nexus-table-row group">
                        <td class="px-6 py-6 text-[13px] font-black text-slate-400 align-middle text-center">{{ $jadwals->firstItem() + $index }}</td>
                        
                        <td class="px-6 py-6 align-middle">
                            <div class="flex items-center gap-5">
                                <div class="date-card w-16 h-16 rounded-[18px] bg-slate-50 border border-slate-100 flex flex-col items-center justify-center shrink-0 shadow-sm">
                                    <span class="text-[11px] font-black text-cyan-600 uppercase tracking-widest">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('M') }}</span>
                                    <span class="text-[22px] font-black text-slate-800 leading-none mt-0.5">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d') }}</span>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 text-[15px] mb-1.5 font-poppins group-hover:text-cyan-600 transition-colors">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, Y') }}</p>
                                    <p class="text-[11.5px] font-bold text-slate-500 bg-white inline-flex items-center px-2.5 py-1 rounded-lg border border-slate-100 shadow-sm">
                                        <i class="fas fa-clock text-cyan-500 mr-2"></i> {{ date('H:i', strtotime($jadwal->waktu_mulai)) }} - {{ date('H:i', strtotime($jadwal->waktu_selesai)) }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-6 align-middle">
                            <p class="font-black text-slate-800 text-[16px] mb-1.5 font-poppins text-wrap">{{ $jadwal->judul }}</p>
                            <p class="text-[12.5px] font-medium text-slate-500 line-clamp-1 mb-2.5 max-w-sm">{{ $jadwal->deskripsi ?? 'Tidak ada deskripsi tambahan.' }}</p>
                            <p class="text-[11.5px] font-bold text-slate-600 flex items-center gap-2"><i class="fas fa-map-marker-alt text-rose-500"></i> {{ $jadwal->lokasi }}</p>
                        </td>

                        <td class="px-6 py-6 align-middle">
                            <div class="flex flex-col items-start gap-2.5">
                                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-indigo-50 text-indigo-700 text-[10.5px] font-black rounded-xl border border-indigo-100 uppercase tracking-widest shadow-sm">
                                    <i class="fas fa-tags text-indigo-400"></i> {{ $jadwal->kategori }}
                                </span>
                                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-emerald-50 text-emerald-700 text-[10.5px] font-black rounded-xl border border-emerald-100 uppercase tracking-widest shadow-sm">
                                    <i class="fas fa-users text-emerald-400"></i> {{ str_replace('_', ' ', $jadwal->target_peserta) }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-6 text-center align-middle">
                            @php
                                $statusConf = match($jadwal->status) {
                                    'aktif' => ['bg-cyan-50 text-cyan-700 border-cyan-200 shadow-[0_4px_10px_rgba(6,182,212,0.1)]', 'Agenda Aktif', 'fa-calendar-check text-cyan-500'],
                                    'selesai' => ['bg-slate-50 text-slate-500 border-slate-200', 'Selesai', 'fa-check-circle text-slate-400'],
                                    'dibatalkan' => ['bg-rose-50 text-rose-600 border-rose-200 shadow-[0_4px_10px_rgba(244,63,94,0.1)]', 'Dibatalkan', 'fa-times-circle text-rose-500'],
                                    default => ['bg-slate-50 text-slate-600', $jadwal->status, 'fa-info-circle']
                                };
                            @endphp
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[10.5px] font-black uppercase tracking-widest border {{ $statusConf[0] }}">
                                <i class="fas {{ $statusConf[2] }} {{ $jadwal->status == 'aktif' ? 'animate-pulse' : '' }} text-[12px]"></i> {{ $statusConf[1] }}
                            </span>
                        </td>

                        <td class="px-6 py-6 text-right align-middle">
                            {{-- Aksi Edit & Hapus (BUG FIXED: Pastikan type="button" untuk Swal) --}}
                            <div class="flex items-center justify-end gap-3 opacity-100 lg:opacity-30 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="{{ route('bidan.jadwal.edit', $jadwal->id) }}" class="smooth-route w-11 h-11 rounded-[14px] bg-white text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-white hover:border-amber-400 transition-all border border-slate-200 shadow-sm" title="Edit Jadwal">
                                    <i class="fas fa-edit text-[15px]"></i>
                                </a>
                                <form action="{{ route('bidan.jadwal.destroy', $jadwal->id) }}" method="POST" class="m-0 p-0">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="w-11 h-11 rounded-[14px] bg-white text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white hover:border-rose-400 transition-all border border-slate-200 shadow-sm" title="Hapus Jadwal">
                                        <i class="fas fa-trash-alt text-[15px]"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-24">
                            <div class="w-28 h-28 bg-slate-50 rounded-[28px] flex items-center justify-center text-slate-300 mx-auto mb-6 text-6xl shadow-inner border border-slate-100"><i class="fas fa-calendar-times"></i></div>
                            <h4 class="font-black text-slate-800 text-[20px] font-poppins mb-2">Database Jadwal Kosong</h4>
                            <p class="text-[14px] font-medium text-slate-500 max-w-md mx-auto leading-relaxed">Klik tombol "Buat Jadwal Baru" di atas untuk mulai merencanakan kegiatan medis bulan ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jadwals->hasPages())
        <div class="px-10 py-6 border-t border-slate-100 bg-slate-50/50 pagination-wrapper">
            {{ $jadwals->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const showLoader = (text = 'MEMUAT SISTEM...') => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            document.getElementById('loaderText').innerText = text;
            loader.style.display = 'flex';
            loader.offsetHeight; 
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100');
        }
    };
    
    window.addEventListener('pageshow', () => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loader.style.display = 'none', 300);
        }
    });

    document.querySelectorAll('.smooth-route, .pagination-wrapper a').forEach(link => {
        link.addEventListener('click', function(e) {
            if(!this.classList.contains('target-blank') && this.target !== '_blank' && !e.ctrlKey) {
                showLoader('MEMUAT HALAMAN...');
            }
        });
    });

    // ====================================================================
    // 1. SWEETALERT NEXUS: KONFIRMASI HAPUS (ANTI-KAKU)
    // ====================================================================
    function confirmDelete(button) {
        const form = button.closest('form');
        
        Swal.fire({
            title: 'Hapus Agenda?',
            text: "Menghapus jadwal ini akan menarik Notifikasi Push dari HP Warga secara permanen. Lanjutkan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-trash-alt mr-2"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
                popup: 'nexus-swal',
                title: 'nexus-title',
                htmlContainer: 'nexus-text',
                // Tombol Hapus (Rose Gradient + Glowing)
                confirmButton: 'bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-400 hover:to-rose-500 text-white px-10 py-4 rounded-2xl font-black text-[13px] uppercase tracking-widest transition-all duration-300 shadow-[0_15px_30px_-5px_rgba(244,63,94,0.4)] hover:shadow-[0_20px_40px_-5px_rgba(244,63,94,0.5)] hover:-translate-y-1 outline-none border-none mx-2',
                // Tombol Batal (Slate Clean)
                cancelButton: 'bg-slate-100 hover:bg-slate-200 text-slate-600 px-10 py-4 rounded-2xl font-black text-[13px] uppercase tracking-widest transition-all duration-300 outline-none border-none mx-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoader('MENGHAPUS AGENDA...');
                form.submit();
            }
        });
    }

    // ====================================================================
    // 2. SWEETALERT NEXUS: NOTIFIKASI SUKSES (ANTI-KAKU)
    // ====================================================================
    @if(session('success'))
        document.querySelectorAll('.alert, .toast').forEach(el => el.remove());

        Swal.fire({
            title: 'Tindakan Berhasil!',
            html: {!! json_encode(session('success')) !!},
            icon: 'success',
            showConfirmButton: true,
            confirmButtonText: '<i class="fas fa-check-circle mr-2"></i> Mengerti',
            buttonsStyling: false,
            customClass: {
                popup: 'nexus-swal',
                title: 'nexus-title',
                htmlContainer: 'nexus-text',
                // Tombol Sukses (Emerald/Teal Gradient + Glowing)
                confirmButton: 'bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white px-12 py-4 rounded-2xl font-black text-[13px] uppercase tracking-widest transition-all duration-300 shadow-[0_15px_30px_-5px_rgba(16,185,129,0.4)] hover:shadow-[0_20px_40px_-5px_rgba(16,185,129,0.5)] hover:-translate-y-1 outline-none border-none mt-2'
            }
        });
    @endif

    // ====================================================================
    // 3. SWEETALERT NEXUS: NOTIFIKASI ERROR
    // ====================================================================
    @if(session('error'))
        document.querySelectorAll('.alert, .toast').forEach(el => el.remove());
        
        Swal.fire({
            title: 'Terjadi Kesalahan!',
            html: {!! json_encode(session('error')) !!},
            icon: 'error',
            showConfirmButton: true,
            confirmButtonText: '<i class="fas fa-times-circle mr-2"></i> Tutup',
            buttonsStyling: false,
            customClass: {
                popup: 'nexus-swal',
                title: 'nexus-title',
                htmlContainer: 'nexus-text',
                confirmButton: 'bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-400 hover:to-rose-500 text-white px-12 py-4 rounded-2xl font-black text-[13px] uppercase tracking-widest transition-all duration-300 shadow-[0_15px_30px_-5px_rgba(244,63,94,0.4)] hover:shadow-[0_20px_40px_-5px_rgba(244,63,94,0.5)] hover:-translate-y-1 outline-none border-none mt-2'
            }
        });
    @endif
</script>
@endpush