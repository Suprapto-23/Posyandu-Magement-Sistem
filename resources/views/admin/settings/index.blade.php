@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')
@section('page-name', 'Setelan Web')

@section('content')
<div class="max-w-6xl mx-auto" style="animation: menuPop 0.4s ease-out forwards;">

    {{-- Hero Section (Executive Obsidian) --}}
    <div class="bg-gradient-to-br from-[#0f172a] to-slate-800 rounded-[32px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_20px_40px_-10px_rgba(15,23,42,0.4)] border border-slate-700 flex flex-col items-center justify-center text-center group">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 blur-[80px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 w-full flex flex-col items-center">
            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-amber-500 flex items-center justify-center text-3xl shadow-inner mb-4 transition-transform group-hover:rotate-180 duration-700">
                <i class="fas fa-cog"></i>
            </div>
            <h2 class="text-3xl font-black text-white font-poppins tracking-tight">
                Konfigurasi Sistem Utama
            </h2>
            <p class="text-slate-400 text-sm font-medium mt-2 max-w-lg">
                Kelola identitas Posyandu untuk keperluan kop surat laporan (Kader & Bidan) dan perbarui kata sandi administrator Anda secara berkala.
            </p>
        </div>
    </div>

    {{-- Notifikasi Sukses / Error --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-6 text-sm font-bold text-emerald-700 flex justify-center items-center text-center gap-3 shadow-sm">
        <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
    </div>
    @endif
    
    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 mb-6 text-sm font-bold text-rose-600 flex justify-center items-center text-center gap-3 shadow-sm">
        <i class="fas fa-exclamation-circle text-xl"></i> Terdapat kesalahan pada input form. Silakan periksa kembali isian Anda.
    </div>
    @endif

    {{-- Grid Layout 2 Kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        
        {{-- ========================================================= --}}
        {{-- CARD 1: PROFIL POSYANDU (Untuk Kop Surat Laporan PDF)     --}}
        {{-- ========================================================= --}}
        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="bg-slate-50/80 px-8 py-5 border-b border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-[#0f172a] text-amber-500 flex items-center justify-center text-lg shadow-sm shrink-0">
                    <i class="fas fa-clinic-medical"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-[#0f172a] uppercase tracking-widest font-poppins">Profil Posyandu</h4>
                    <p class="text-[11px] font-bold text-slate-500 mt-0.5">Terintegrasi dengan Laporan PDF</p>
                </div>
            </div>
            
            <form action="{{ route('admin.settings.update') }}" method="POST" id="formProfil" class="p-8 flex-1 flex flex-col">
                @csrf 
                @method('PUT')
                
                <div class="space-y-5 flex-1">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nama Posyandu <span class="text-rose-500">*</span></label>
                        <input type="text" name="posyandu_name" value="{{ old('posyandu_name', $settings['posyandu_name'] ?? '') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all" placeholder="Contoh: Posyandu Melati 1">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Email (Opsional)</label>
                            <input type="email" name="posyandu_email" value="{{ old('posyandu_email', $settings['posyandu_email'] ?? '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all" placeholder="email@posyandu.com">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Telepon (Opsional)</label>
                            <input type="text" name="posyandu_telepon" value="{{ old('posyandu_telepon', $settings['posyandu_telepon'] ?? '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all" placeholder="08xx...">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Kelurahan / Desa</label>
                        <input type="text" name="posyandu_kelurahan" value="{{ old('posyandu_kelurahan', $settings['posyandu_kelurahan'] ?? '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all" placeholder="Nama Desa">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Alamat Lengkap</label>
                        <textarea name="posyandu_alamat" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 outline-none transition-all resize-none" placeholder="Jalan, RT/RW, Kecamatan">{{ old('posyandu_alamat', $settings['posyandu_alamat'] ?? '') }}</textarea>
                    </div>
                </div>

                <div class="mt-8">
                    <button type="submit" id="btnProfil" class="w-full py-3.5 rounded-2xl font-bold text-slate-900 bg-amber-500 hover:bg-amber-400 hover:-translate-y-1 transition-all shadow-[0_4px_20px_rgba(245,158,11,0.4)] text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Profil Posyandu
                    </button>
                </div>
            </form>
        </div>

        {{-- ========================================================= --}}
        {{-- CARD 2: KEAMANAN (Ganti Password Administrator)           --}}
        {{-- ========================================================= --}}
        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="bg-slate-50/80 px-8 py-5 border-b border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 border border-slate-200 flex items-center justify-center text-lg shadow-sm shrink-0">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <h4 class="text-sm font-black text-[#0f172a] uppercase tracking-widest font-poppins">Keamanan Akun</h4>
                    <p class="text-[11px] font-bold text-slate-500 mt-0.5">Ubah kata sandi Administrator Anda</p>
                </div>
            </div>
            
            {{-- Form Password dengan Padding yang sudah disejajarkan --}}
            <form action="{{ route('admin.settings.change-password') }}" method="POST" id="formPassword" class="p-8 flex-1 flex flex-col">
                @csrf 
                @method('PUT')
                
                <div class="space-y-5 flex-1">
                    {{-- Password Lama --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Password Saat Ini</label>
                        <input type="password" name="current_password" required 
                               class="w-full bg-slate-50 border @error('current_password') border-rose-500 bg-rose-50 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all">
                        @error('current_password')
                            <p class="text-[10px] text-rose-500 font-bold mt-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Password Baru (Min 8 Karakter)</label>
                        <input type="password" name="new_password" required 
                               class="w-full bg-slate-50 border @error('new_password') border-rose-500 bg-rose-50 @else border-slate-200 @enderror rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all">
                        @error('new_password')
                            <p class="text-[10px] text-rose-500 font-bold mt-1"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Ulangi Password Baru</label>
                        <input type="password" name="new_password_confirmation" required 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all">
                    </div>
                </div>

                <div class="mt-8">
                    {{-- PERBAIKAN: Penambahan id="btnPassword" agar animasi JS jalan --}}
                    <button type="submit" id="btnPassword" class="w-full py-3.5 rounded-2xl font-bold text-white bg-slate-800 hover:bg-[#0f172a] hover:-translate-y-1 transition-all shadow-[0_4px_20px_rgba(15,23,42,0.2)] text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-lock"></i> Perbarui Password Admin
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

{{-- Script untuk animasi loading pada tombol submit --}}
<script>
    document.getElementById('formProfil').addEventListener('submit', function() {
        const btn = document.getElementById('btnProfil');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    });

    document.getElementById('formPassword').addEventListener('submit', function() {
        const btn = document.getElementById('btnPassword');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg"></i> Memvalidasi...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    });
</script>
@endsection