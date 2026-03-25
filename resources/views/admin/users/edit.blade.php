@extends('layouts.admin')
@section('title', 'Edit User Warga')
@section('page-name', 'Edit Warga')

@section('content')
<div class="max-w-4xl mx-auto" style="animation: menuPop 0.4s ease-out forwards;">

    {{-- Hero (Simetri Rata Tengah) --}}
    <div class="bg-gradient-to-br from-obsidian-900 to-slate-800 rounded-[32px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-xl border border-slate-700 flex flex-col items-center justify-center text-center group">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute top-0 right-0 w-48 h-48 bg-blue-500/10 blur-[60px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 w-full flex flex-col items-center">
            <div class="inline-flex items-center gap-2 text-amber-500 text-[10px] font-black uppercase tracking-widest mb-3">
                <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-amber-500 transition-colors smooth-route">Daftar Warga</a>
                <i class="fas fa-chevron-right text-[8px] text-slate-600"></i>
                <span>Edit Profil</span>
            </div>
            
            <h2 class="text-3xl font-black text-white font-poppins tracking-tight flex items-center justify-center gap-3">
                <i class="fas fa-user-edit text-amber-500"></i> Edit Data: {{ $user->name }}
            </h2>
        </div>
    </div>

    {{-- PESAN ERROR DIPERBAIKI (Akan menampilkan detail error dari Controller) --}}
    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 mb-6 text-sm font-bold text-rose-600 shadow-sm">
        <div class="flex items-center justify-center gap-2 mb-2">
            <i class="fas fa-exclamation-circle text-lg"></i> 
            <span>Gagal update! Periksa kesalahan berikut:</span>
        </div>
        <ul class="list-disc list-inside text-xs text-center">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="bg-slate-50/80 px-8 py-5 border-b border-slate-100 flex items-center justify-center">
                <h5 class="font-black text-obsidian-900 text-sm uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-id-card-alt text-amber-500"></i> Update Data Pribadi
                </h5>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2 space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" maxlength="16" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-obsidian-900 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all" placeholder="Masukkan 16 digit NIK">
                    </div>

                    {{-- DIPERBAIKI: name="full_name" agar dikenali Controller --}}
                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name', $user->profile->full_name ?? $user->name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-obsidian-900 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all" placeholder="Sesuai KTP">
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-obsidian-900 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $user->profile->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->profile->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    {{-- DIPERBAIKI: Ditambahkan atribut required --}}
                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Tempat Lahir <span class="text-rose-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user->profile->tempat_lahir ?? '') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-obsidian-900 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all" placeholder="Kota Kelahiran">
                    </div>

                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->profile->tanggal_lahir ?? '') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-obsidian-900 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all">
                    </div>

                    {{-- DIPERBAIKI: Ditambahkan atribut required --}}
                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nomor Telepon/WA <span class="text-rose-500">*</span></label>
                        <input type="text" name="telepon" value="{{ old('telepon', $user->profile->telepon ?? '') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-obsidian-900 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all" placeholder="08xx...">
                    </div>

                    {{-- DIPERBAIKI: Ditambahkan atribut required --}}
                    <div class="space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="alamat" value="{{ old('alamat', $user->profile->alamat ?? '') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-obsidian-900 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all" placeholder="Jalan, RT/RW, Desa">
                    </div>
                    
                    <div class="col-span-1 md:col-span-2 space-y-2 text-center md:text-left">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Status Akun <span class="text-rose-500">*</span></label>
                        <select name="status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-black text-obsidian-900 focus:bg-white focus:border-amber-500 outline-none transition-all">
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>🟢 AKTIF</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>🔴 NONAKTIF</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center gap-4 pb-10">
            <a href="{{ route('admin.users.index') }}" class="px-8 py-3.5 rounded-2xl font-bold text-slate-500 bg-white border border-slate-200 hover:bg-slate-50 hover:text-obsidian-900 transition-all shadow-sm text-sm smooth-route">
                <i class="fas fa-times mr-1"></i> Batal
            </a>
            <button type="submit" class="px-8 py-3.5 rounded-2xl font-bold text-obsidian-900 bg-amber-500 hover:bg-amber-400 hover:-translate-y-1 transition-all shadow-[0_4px_20px_rgba(245,158,11,0.4)] text-sm">
                <i class="fas fa-save mr-1"></i> Update Data
            </button>
        </div>
    </form>
</div>
@endsection