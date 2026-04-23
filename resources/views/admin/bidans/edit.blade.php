@extends('layouts.admin')
@section('title', 'Edit Data Bidan')
@section('page-name', 'Edit Bidan')

@section('content')
<style>
    .animate-pop-in { animation: popIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes popIn { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
</style>

<div class="max-w-4xl mx-auto animate-pop-in">

    {{-- Hero Section --}}
    <div class="bg-gradient-to-br from-sky-500 to-teal-500 rounded-[2.5rem] p-8 md:p-10 mb-8 relative overflow-hidden shadow-lg border border-white/20 flex flex-col items-center justify-center text-center group">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 blur-[60px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 w-full flex flex-col items-center">
            <div class="inline-flex items-center gap-2 text-white/80 text-[10px] font-black uppercase tracking-widest mb-3">
                <a href="{{ route('admin.bidans.index') }}" class="hover:text-white transition-colors smooth-route">Daftar Bidan</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-white">Edit Data</span>
            </div>
            
            <h2 class="text-3xl font-black text-white font-poppins tracking-tight flex items-center justify-center gap-3 text-shadow-sm">
                <i class="fas fa-user-edit"></i> Edit: {{ $bidan->profile->full_name ?? $bidan->name }}
            </h2>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 mb-6 text-sm font-bold text-rose-600 flex justify-center text-center gap-3 shadow-sm">
        <i class="fas fa-exclamation-circle text-lg"></i> Mohon periksa kembali isian form Anda.
    </div>
    @endif

    <form action="{{ route('admin.bidans.update', $bidan->id) }}" method="POST" id="bidanForm">
        @csrf @method('PUT')
        
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-8">
            <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-50 flex items-center justify-center">
                <h5 class="font-black text-slate-700 text-sm uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-id-badge text-teal-500"></i> Perbarui Data Bidan
                </h5>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Readonly Inputs --}}
                    <div class="space-y-2 text-center md:text-left opacity-70">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" value="{{ $bidan->profile?->nik ?? $bidan->nik ?? '-' }}" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-400 cursor-not-allowed">
                    </div>

                    <div class="space-y-2 text-center md:text-left opacity-70">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Email (Username)</label>
                        <input type="email" value="{{ $bidan->email }}" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-400 cursor-not-allowed">
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-2 text-center md:text-left border-t border-slate-100 mt-2 pt-6">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $bidan->profile?->full_name ?? $bidan->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-teal-400 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-teal-400 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all cursor-pointer">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $bidan->profile?->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin', $bidan->profile?->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nomor Telepon/WA</label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $bidan->profile?->telepon) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-teal-400 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $bidan->profile?->tempat_lahir) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-teal-400 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $bidan->profile?->tanggal_lahir) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-teal-400 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Alamat Lengkap</label>
                        <input type="text" name="alamat" value="{{ old('alamat', $bidan->profile?->alamat) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-teal-400 focus:ring-4 focus:ring-teal-500/10 outline-none transition-all">
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-2 text-center md:text-left mt-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Status Akun <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-black text-slate-800 focus:bg-white focus:border-teal-400 outline-none transition-all cursor-pointer">
                            <option value="active" {{ old('status', $bidan->status) == 'active' ? 'selected' : '' }}>🟢 AKTIF</option>
                            <option value="inactive" {{ old('status', $bidan->status) == 'inactive' ? 'selected' : '' }}>🔴 NONAKTIF</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pb-10">
            <a href="{{ route('admin.bidans.index') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-slate-500 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-700 transition-all shadow-sm text-sm text-center smooth-route">
                <i class="fas fa-times mr-1"></i> Batal
            </a>
            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-white bg-teal-500 hover:bg-teal-600 hover:-translate-y-0.5 transition-all shadow-[0_4px_15px_rgba(20,184,166,0.3)] text-sm flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('telepon').addEventListener('input', function(e) { this.value = this.value.replace(/[^0-9]/g, ''); });
    
    document.getElementById('bidanForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    });
</script>
@endsection