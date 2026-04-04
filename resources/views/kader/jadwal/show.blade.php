@extends('layouts.kader')
@section('title', 'Detail Agenda')
@section('page-name', 'Detail Agenda Acara')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .poster-bg { background-image: radial-gradient(circle at top right, rgba(124, 58, 237, 0.05), transparent 40%), radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.05), transparent 40%); }
    .radar-pulse { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite; }
    @keyframes pulse-ring { 0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); } 70% { box-shadow: 0 0 0 20px rgba(79, 70, 229, 0); } 100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); } }
</style>
@endpush

@section('content')
<div class="max-w-[800px] mx-auto animate-slide-up text-center sm:text-left">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('kader.jadwal.index') }}" class="w-12 h-12 bg-white border border-slate-200 text-slate-500 rounded-xl flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Undangan Posyandu</h1>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('kader.jadwal.edit', $jadwal->id) }}" class="px-5 py-2.5 bg-amber-50 text-amber-600 border border-amber-200 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-amber-100 transition-colors flex items-center justify-center gap-2 flex-1 sm:flex-auto">
                <i class="fas fa-pen"></i> Edit
            </a>
            <form action="{{ route('kader.jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus agenda ini secara permanen?');" class="flex-1 sm:flex-auto m-0">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-5 py-2.5 bg-white border border-slate-200 text-rose-500 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-rose-50 hover:border-rose-200 transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-trash-alt"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_10px_40px_-10px_rgba(0,0,0,0.06)] overflow-hidden mb-8 relative poster-bg">
        
        <div class="absolute right-0 top-0 w-48 h-48 bg-indigo-500/10 rounded-bl-full pointer-events-none"></div>

        <div class="p-8 md:p-12 text-center border-b border-slate-100 relative z-10">
            @if($jadwal->status == 'aktif') <span class="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-full mb-4 border border-emerald-200 shadow-sm"><i class="fas fa-circle text-[8px] animate-pulse mr-1"></i> Mendatang</span>
            @elseif($jadwal->status == 'selesai') <span class="inline-block px-4 py-1.5 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-full mb-4 border border-slate-200">Selesai</span>
            @else <span class="inline-block px-4 py-1.5 bg-rose-100 text-rose-700 text-[10px] font-black uppercase tracking-widest rounded-full mb-4 border border-rose-200">Dibatalkan</span> @endif
            
            <h2 class="text-3xl md:text-5xl font-black text-slate-800 font-poppins mb-3 tracking-tight">{{ $jadwal->judul }}</h2>
            <p class="text-indigo-600 font-black uppercase tracking-widest text-[12px]"><i class="fas fa-users mr-1"></i> Kepada: {{ str_replace('_', ' ', $jadwal->target_peserta) }}</p>
        </div>

        <div class="p-8 md:p-12 grid grid-cols-1 sm:grid-cols-2 gap-8 text-left relative z-10 bg-slate-50/50">
            
            <div class="flex items-start gap-4 p-5 bg-white border border-slate-100 rounded-[20px] shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center text-xl shrink-0"><i class="far fa-calendar-check"></i></div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Pelaksanaan</p>
                    <p class="text-[15px] font-black text-slate-800">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-5 bg-white border border-slate-100 rounded-[20px] shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-xl shrink-0"><i class="far fa-clock"></i></div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Waktu (WIB)</p>
                    <p class="text-[15px] font-black text-slate-800">{{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }}</p>
                </div>
            </div>

            <div class="flex items-start gap-4 p-5 bg-white border border-slate-100 rounded-[20px] shadow-sm sm:col-span-2">
                <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-500 flex items-center justify-center text-xl shrink-0"><i class="fas fa-map-marker-alt"></i></div>
                <div class="flex-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi Kegiatan</p>
                    <p class="text-[15px] font-black text-slate-800">{{ $jadwal->lokasi }}</p>
                </div>
            </div>
            
            @if($jadwal->deskripsi)
            <div class="sm:col-span-2 p-6 bg-amber-50/50 border border-amber-100 rounded-[20px]">
                <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2"><i class="fas fa-info-circle"></i> Catatan & Persyaratan</p>
                <p class="text-[13px] font-bold text-amber-900 leading-relaxed italic">"{{ $jadwal->deskripsi }}"</p>
            </div>
            @endif

        </div>

        @if($jadwal->status == 'aktif')
        <div class="p-8 md:p-12 text-center border-t border-slate-100 bg-white">
            <h4 class="text-lg font-black text-slate-800 mb-2">Sebarkan Undangan?</h4>
            <p class="text-[13px] font-medium text-slate-500 max-w-md mx-auto mb-6 leading-relaxed">Ketuk tombol di bawah untuk membunyikan notifikasi langsung ke aplikasi warga yang terdaftar sebagai Target Peserta.</p>
            
            <form action="{{ route('kader.jadwal.broadcast', $jadwal->id) }}" method="POST" id="formBroadcast">
                @csrf
                <button type="submit" id="btnBroadcast" class="inline-flex items-center justify-center gap-3 px-10 py-4 bg-indigo-600 text-white font-black text-sm rounded-2xl hover:bg-indigo-700 transition-all uppercase tracking-widest w-full sm:w-auto shadow-lg shadow-indigo-200 radar-pulse hover:scale-105">
                    <i class="fas fa-satellite-dish text-lg"></i> Mulai Broadcast Ke Warga
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
                html: '<p class="text-sm text-slate-500">Sistem sedang mendistribusikan notifikasi ke HP warga.</p>',
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
                    Swal.fire({ icon: 'success', title: 'Broadcast Terkirim!', text: data.message, confirmButtonColor: '#4f46e5' });
                    document.getElementById('btnBroadcast').classList.remove('radar-pulse');
                    document.getElementById('btnBroadcast').innerHTML = '<i class="fas fa-check-double text-lg"></i> Berhasil Terkirim';
                    document.getElementById('btnBroadcast').classList.replace('bg-indigo-600', 'bg-emerald-500');
                    document.getElementById('btnBroadcast').disabled = true;
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                }
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'Koneksi Terputus', text: 'Gagal menghubungi server server.' });
            });
        });
    }
</script>
@endpush
@endsection