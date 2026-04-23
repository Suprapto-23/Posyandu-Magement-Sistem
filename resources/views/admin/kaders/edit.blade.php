@extends('layouts.admin')
@section('title', 'Edit Data Kader')
@section('page-name', 'Edit Kader')

@section('content')
<style>
    .animate-pop-in { animation: popIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes popIn { 0% { opacity: 0; transform: scale(0.95) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
</style>

<div class="max-w-4xl mx-auto animate-pop-in">

    {{-- Hero Section --}}
    <div class="bg-gradient-to-br from-sky-500 to-indigo-500 rounded-[2.5rem] p-8 md:p-10 mb-8 relative overflow-hidden shadow-lg border border-white/20 flex flex-col items-center justify-center text-center group">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 blur-[60px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 w-full flex flex-col items-center">
            <div class="inline-flex items-center gap-2 text-white/80 text-[10px] font-black uppercase tracking-widest mb-3">
                <a href="{{ route('admin.kaders.index') }}" class="hover:text-white transition-colors smooth-route">Daftar Kader</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-white">Edit Data</span>
            </div>
            
            <h2 class="text-3xl font-black text-white font-poppins tracking-tight flex items-center justify-center gap-3 text-shadow-sm">
                <i class="fas fa-user-edit"></i> Edit: {{ $kader->profile->full_name ?? $kader->name }}
            </h2>
        </div>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 mb-6 text-sm font-bold text-rose-600 flex justify-center text-center gap-3 shadow-sm">
        <i class="fas fa-exclamation-circle text-lg"></i> Mohon periksa kembali isian form Anda.
    </div>
    @endif

    <form action="{{ route('admin.kaders.update', $kader->id) }}" method="POST" id="kaderForm">
        @csrf @method('PUT')
        
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-8">
            <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-50 flex items-center justify-center">
                <h5 class="font-black text-slate-700 text-sm uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-id-badge text-indigo-500"></i> Perbarui Data Kader
                </h5>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Readonly Inputs --}}
                    <div class="space-y-2 text-center md:text-left opacity-70">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" value="{{ $kader->profile?->nik ?? $kader->nik ?? '-' }}" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-400 cursor-not-allowed">
                    </div>

                    <div class="space-y-2 text-center md:text-left opacity-70">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Email (Username)</label>
                        <input type="email" value="{{ $kader->email }}" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-400 cursor-not-allowed">
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-2 text-center md:text-left border-t border-slate-100 mt-2 pt-6">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name', $kader->profile?->full_name ?? $kader->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all">
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all cursor-pointer">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $kader->profile?->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin', $kader->profile?->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Jabatan Posyandu <span class="text-rose-500">*</span></label>
                        <select name="jabatan" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all cursor-pointer">
                            <option value="Kader Biasa" {{ old('jabatan', $kader->kader?->jabatan) == 'Kader Biasa' ? 'selected' : '' }}>Kader Biasa</option>
                            <option value="Ketua Kader" {{ old('jabatan', $kader->kader?->jabatan) == 'Ketua Kader' ? 'selected' : '' }}>Ketua Kader</option>
                            <option value="Sekretaris" {{ old('jabatan', $kader->kader?->jabatan) == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                            <option value="Bendahara" {{ old('jabatan', $kader->kader?->jabatan) == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                        </select>
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $kader->profile?->tempat_lahir) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all">
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $kader->profile?->tanggal_lahir) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all">
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nomor Telepon/WA <span class="text-rose-500">*</span></label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $kader->profile?->telepon) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all">
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Tanggal Bergabung <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_bergabung" value="{{ old('tanggal_bergabung', $kader->kader?->tanggal_bergabung) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all">
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="alamat" value="{{ old('alamat', $kader->profile?->alamat) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all">
                    </div>

                    <div class="space-y-2 text-center md:text-left mt-2 border-t border-slate-100 pt-6">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Status Tugas <span class="text-rose-500">*</span></label>
                        <select name="status_kader" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-indigo-400 outline-none transition-all cursor-pointer">
                            <option value="aktif" {{ old('status_kader', $kader->kader?->status_kader) == 'aktif' ? 'selected' : '' }}>Aktif Bertugas</option>
                            <option value="nonaktif" {{ old('status_kader', $kader->kader?->status_kader) == 'nonaktif' ? 'selected' : '' }}>Cuti / Pensiun</option>
                        </select>
                    </div>

                    <div class="space-y-2 text-center md:text-left mt-2 border-t border-slate-100 pt-6">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Akses Login App <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-black text-slate-800 focus:bg-white focus:border-indigo-400 outline-none transition-all cursor-pointer">
                            <option value="active" {{ old('status', $kader->status) == 'active' ? 'selected' : '' }}>🟢 DIIZINKAN LOGIN</option>
                            <option value="inactive" {{ old('status', $kader->status) == 'inactive' ? 'selected' : '' }}>🔴 BLOKIR LOGIN</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pb-10">
            <a href="{{ route('admin.kaders.index') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-slate-500 bg-white border border-slate-200 hover:bg-slate-50 hover:text-slate-700 transition-all shadow-sm text-sm text-center smooth-route">
                <i class="fas fa-times mr-1"></i> Batal
            </a>
            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold text-white bg-indigo-500 hover:bg-indigo-600 hover:-translate-y-0.5 transition-all shadow-[0_4px_15px_rgba(99,102,241,0.3)] text-sm flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('telepon').addEventListener('input', function(e) { this.value = this.value.replace(/[^0-9]/g, ''); });
    
    document.getElementById('kaderForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    });
</script>
@endsection