@extends('layouts.bidan')

@section('title', 'Edit Jadwal Posyandu')
@section('page-name', 'Perbarui Agenda')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    
    .premium-input { 
        width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; border-radius: 16px; 
        padding: 14px 16px 14px 46px; font-size: 13px; font-weight: 700; color: #1e293b; 
        outline: none; transition: all 0.3s ease; 
    }
    .premium-input:focus { background-color: #ffffff; border-color: #f59e0b; box-shadow: 0 0 0 4px rgba(245,158,11,0.1); }
    .input-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 15px; transition: color 0.3s; z-index: 10; }
    .group-focus-within .input-icon { color: #f59e0b; }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-20 h-20 flex items-center justify-center mb-5">
        <div class="absolute inset-0 border-4 border-amber-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-amber-500 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
            <i class="fas fa-save text-amber-500 text-xl animate-pulse"></i>
        </div>
    </div>
    <div class="bg-white px-5 py-2 rounded-full shadow-sm border border-slate-100 flex items-center gap-2">
        <div class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></div>
        <p class="text-[10px] font-black text-amber-700 uppercase tracking-[0.2em] font-poppins" id="loaderText">MENYIMPAN PERUBAHAN...</p>
    </div>
</div>

<div class="max-w-[800px] mx-auto animate-slide-up pb-10">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight font-poppins">Edit Agenda Tersimpan</h1>
            <p class="text-slate-500 mt-1 font-medium text-[13px]">Perubahan status (Aktif/Dibatalkan) akan langsung meng-update *database* Warga.</p>
        </div>
        <a href="{{ route('bidan.jadwal.index') }}" class="smooth-route inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold text-[12px] uppercase tracking-widest rounded-xl hover:bg-slate-50 hover:text-amber-600 transition-colors shadow-sm shrink-0">
            <i class="fas fa-arrow-left"></i> Batal Edit
        </a>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex flex-col">
        
        <div class="px-8 py-6 border-b border-slate-100 bg-amber-50/30 flex items-center gap-3">
            <div class="w-10 h-10 rounded-[12px] bg-white border border-amber-100 text-amber-500 flex items-center justify-center text-lg shadow-sm"><i class="fas fa-pen-nib"></i></div>
            <h3 class="font-black text-slate-800 text-[16px] font-poppins">Koreksi Data Jadwal</h3>
        </div>

        <form id="formJadwal" action="{{ route('bidan.jadwal.update', $jadwal->id) }}" method="POST" class="flex flex-col flex-1">
            @csrf @method('PUT')
            
            <div class="p-8 space-y-6 flex-1">
                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Judul Agenda <span class="text-rose-500">*</span></label>
                    <div class="relative group-focus-within">
                        <i class="fas fa-heading input-icon"></i>
                        <input type="text" name="judul" value="{{ old('judul', $jadwal->judul) }}" required class="premium-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Tanggal Eksekusi <span class="text-rose-500">*</span></label>
                        <div class="relative group-focus-within">
                            <i class="fas fa-calendar-day input-icon"></i>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $jadwal->tanggal) }}" required class="premium-input cursor-pointer" style="padding-right: 16px;">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Mulai <span class="text-rose-500">*</span></label>
                            <div class="relative group-focus-within">
                                <i class="far fa-clock input-icon"></i>
                                <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', date('H:i', strtotime($jadwal->waktu_mulai))) }}" required class="premium-input cursor-pointer" style="padding-left: 42px; padding-right: 10px;">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Selesai <span class="text-rose-500">*</span></label>
                            <div class="relative group-focus-within">
                                <i class="far fa-clock input-icon"></i>
                                <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', date('H:i', strtotime($jadwal->waktu_selesai))) }}" required class="premium-input cursor-pointer" style="padding-left: 42px; padding-right: 10px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Lokasi Gedung / Posyandu <span class="text-rose-500">*</span></label>
                    <div class="relative group-focus-within">
                        <i class="fas fa-map-marked-alt input-icon"></i>
                        <input type="text" name="lokasi" value="{{ old('lokasi', $jadwal->lokasi) }}" required class="premium-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Ubah Status Jadwal <span class="text-rose-500">*</span></label>
                        <div class="relative group-focus-within">
                            <i class="fas fa-toggle-on input-icon text-amber-500"></i>
                            <select name="status" required class="premium-input cursor-pointer appearance-none border-amber-200 bg-amber-50 focus:bg-white text-amber-800 focus:border-amber-400 focus:ring-amber-50" style="padding-right: 16px;">
                                <option value="aktif" {{ $jadwal->status == 'aktif' ? 'selected' : '' }}>Aktif Berjalan</option>
                                <option value="selesai" {{ $jadwal->status == 'selesai' ? 'selected' : '' }}>Sudah Selesai</option>
                                <option value="dibatalkan" {{ $jadwal->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan / Ditunda</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-black text-[13px] uppercase tracking-widest rounded-2xl hover:shadow-[0_10px_20px_rgba(245,158,11,0.3)] hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-save text-lg"></i> Simpan Pembaruan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const showLoader = (text = 'MEMPROSES...') => {
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
        const btn = document.getElementById('btnSubmit');
        if(loader) {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loader.style.display = 'none', 300);
        }
        if(btn) {
            btn.innerHTML = '<i class="fas fa-save text-lg"></i> Simpan Pembaruan';
            btn.classList.remove('opacity-75', 'cursor-wait');
        }
    });

    document.querySelectorAll('.smooth-route').forEach(link => {
        link.addEventListener('click', function(e) {
            if(this.target !== '_blank' && !e.ctrlKey) showLoader('MEMBATALKAN...');
        });
    });

    document.getElementById('formJadwal').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-wait');
        showLoader('MENYIMPAN PERUBAHAN JADWAL...');
    });
</script>
@endpush