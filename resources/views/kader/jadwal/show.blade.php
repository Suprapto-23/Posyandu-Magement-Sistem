@extends('layouts.kader')
@section('title', 'Detail Agenda')
@section('page-name', 'Detail Agenda Acara')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    .poster-bg { background-image: radial-gradient(circle at top right, rgba(139, 92, 246, 0.05), transparent 50%), radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.05), transparent 50%); }
    
    /* Radar Broadcast Effect */
    .radar-pulse { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); animation: pulse-ring 2.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite; }
    @keyframes pulse-ring { 
        0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.6); transform: scale(1); } 
        70% { box-shadow: 0 0 0 25px rgba(79, 70, 229, 0); transform: scale(1.02); } 
        100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); transform: scale(1); } 
    }
</style>
@endpush

@section('content')
<div class="max-w-[850px] mx-auto animate-slide-up pb-10">
    
    {{-- TOMBOL NAVIGASI --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('kader.jadwal.index') }}" class="loader-trigger w-12 h-12 bg-white border border-slate-200 text-slate-500 rounded-[14px] flex items-center justify-center hover:bg-violet-50 hover:text-violet-600 hover:border-violet-200 transition-all shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Undangan Posyandu</h1>
            </div>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('kader.jadwal.edit', $jadwal->id) }}" class="loader-trigger px-5 py-3 bg-amber-50 text-amber-600 border border-amber-200 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-amber-100 transition-colors flex items-center justify-center gap-2 flex-1 sm:flex-auto shadow-sm">
                <i class="fas fa-pen"></i> Edit
            </a>
            <form action="{{ route('kader.jadwal.destroy', $jadwal->id) }}" method="POST" id="deleteForm" class="flex-1 sm:flex-auto m-0">
                @csrf @method('DELETE')
                <button type="button" onclick="confirmDelete()" class="w-full px-5 py-3 bg-white border border-slate-200 text-rose-500 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-rose-50 hover:border-rose-200 transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i class="fas fa-trash-alt"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- KARTU POSTER DIGITAL --}}
    <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_15px_50px_-10px_rgba(0,0,0,0.06)] overflow-hidden mb-8 relative poster-bg">
        
        <div class="absolute right-0 top-0 w-64 h-64 bg-violet-500/10 rounded-bl-full pointer-events-none blur-3xl"></div>

        <div class="p-8 md:p-14 text-center border-b border-slate-100 relative z-10">
            @if($jadwal->status == 'aktif') 
                <span class="inline-flex items-center gap-2 px-5 py-2 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-full mb-6 border border-emerald-200 shadow-sm"><div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div> Segera Dilaksanakan</span>
            @elseif($jadwal->status == 'selesai') 
                <span class="inline-flex items-center gap-2 px-5 py-2 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-full mb-6 border border-slate-200 shadow-sm"><i class="fas fa-check-circle"></i> Sudah Selesai</span>
            @else 
                <span class="inline-flex items-center gap-2 px-5 py-2 bg-rose-100 text-rose-700 text-[10px] font-black uppercase tracking-widest rounded-full mb-6 border border-rose-200 shadow-sm"><i class="fas fa-times-circle"></i> Dibatalkan</span> 
            @endif
            
            <h2 class="text-4xl md:text-5xl font-black text-slate-800 font-poppins mb-4 tracking-tight leading-tight">{{ $jadwal->judul }}</h2>
            
            <div class="inline-flex items-center gap-2 bg-indigo-50 px-4 py-1.5 rounded-lg border border-indigo-100">
                <i class="fas fa-users text-indigo-400"></i>
                <p class="text-indigo-700 font-black uppercase tracking-widest text-[11px]">Kepada: {{ str_replace('_', ' ', $jadwal->target_peserta) }}</p>
            </div>
        </div>

        <div class="p-8 md:p-12 grid grid-cols-1 sm:grid-cols-2 gap-8 text-left relative z-10 bg-slate-50/50">
            
            <div class="flex items-start gap-4 p-5 bg-white border border-slate-200/80 rounded-[24px] shadow-sm hover:border-violet-300 transition-colors group">
                <div class="w-14 h-14 rounded-[16px] bg-violet-100 text-violet-600 flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="far fa-calendar-check"></i></div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Pelaksanaan</p>
                    <p class="text-[16px] font-black text-slate-800">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-5 bg-white border border-slate-200/80 rounded-[24px] shadow-sm hover:border-sky-300 transition-colors group">
                <div class="w-14 h-14 rounded-[16px] bg-sky-100 text-sky-600 flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="far fa-clock"></i></div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Waktu (WIB)</p>
                    <p class="text-[16px] font-black text-slate-800">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-5 bg-white border border-slate-200/80 rounded-[24px] shadow-sm hover:border-rose-300 transition-colors group sm:col-span-2">
                <div class="w-14 h-14 rounded-[16px] bg-rose-100 text-rose-500 flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 transition-transform"><i class="fas fa-map-marker-alt"></i></div>
                <div class="flex-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi Kegiatan</p>
                    <p class="text-[16px] font-black text-slate-800">{{ $jadwal->lokasi }}</p>
                </div>
            </div>
            
            @if($jadwal->deskripsi)
            <div class="sm:col-span-2 p-6 bg-amber-50 border border-amber-200 rounded-[24px] shadow-sm">
                <p class="text-[11px] font-black text-amber-700 uppercase tracking-widest mb-2"><i class="fas fa-info-circle mr-1"></i> Catatan & Persyaratan</p>
                <p class="text-[14px] font-bold text-amber-900 leading-relaxed">"{{ $jadwal->deskripsi }}"</p>
            </div>
            @endif
        </div>

        {{-- BROADCAST SECTION (Hanya muncul jika jadwal masih aktif) --}}
        @if($jadwal->status == 'aktif')
        <div class="p-8 md:p-14 text-center border-t border-slate-100 bg-white">
            <div class="w-20 h-20 rounded-full bg-indigo-50 flex items-center justify-center mx-auto mb-4 border border-indigo-100 text-indigo-500 text-3xl"><i class="fas fa-satellite-dish"></i></div>
            <h4 class="text-xl font-black text-slate-800 font-poppins mb-2">Sebarkan Undangan Sekarang?</h4>
            <p class="text-[13px] font-medium text-slate-500 max-w-md mx-auto mb-8 leading-relaxed">Ketuk tombol di bawah untuk mengirimkan notifikasi *Push* langsung ke aplikasi warga yang terdaftar sebagai Target Peserta.</p>
            
            <form action="{{ route('kader.jadwal.broadcast', $jadwal->id) }}" method="POST" id="formBroadcast">
                @csrf
                <button type="submit" id="btnBroadcast" class="inline-flex items-center justify-center gap-3 px-10 py-4 bg-indigo-600 text-white font-black text-[14px] rounded-[18px] hover:bg-indigo-700 transition-all uppercase tracking-widest w-full sm:w-auto shadow-lg shadow-indigo-200 radar-pulse border border-indigo-500">
                    <i class="fas fa-paper-plane text-lg"></i> Siarkan ke Warga
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const broadcastForm = document.getElementById('formBroadcast');
    if(broadcastForm) {
        broadcastForm.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Menyiarkan Sinyal...',
                html: '<p class="text-sm font-medium text-slate-500 mt-2">Sistem sedang mendistribusikan notifikasi push ke aplikasi warga. Harap tunggu...</p>',
                allowOutsideClick: false, showConfirmButton: false,
                willOpen: () => { Swal.showLoading(); }
            });

            fetch(this.action, {
                method: 'POST', body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Broadcast Terkirim!', text: data.message, confirmButtonColor: '#4f46e5', customClass: { popup: 'rounded-3xl' }});
                    const btn = document.getElementById('btnBroadcast');
                    btn.classList.remove('radar-pulse');
                    btn.innerHTML = '<i class="fas fa-check-double text-lg"></i> Berhasil Terkirim';
                    btn.classList.replace('bg-indigo-600', 'bg-emerald-500');
                    btn.classList.replace('hover:bg-indigo-700', 'hover:bg-emerald-600');
                    btn.classList.replace('border-indigo-500', 'border-emerald-500');
                    btn.classList.replace('shadow-indigo-200', 'shadow-emerald-200');
                    btn.disabled = true;
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                }
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'Koneksi Terputus', text: 'Gagal menghubungi server.' });
            });
        });
    }

    function confirmDelete() {
        Swal.fire({
            title: 'Hapus Agenda?', text: "Jadwal yang dihapus tidak bisa dikembalikan.",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', reverseButtons: true,
            customClass: { popup: 'rounded-3xl' }
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('deleteForm').submit();
        });
    }
</script>
@endpush
@endsection