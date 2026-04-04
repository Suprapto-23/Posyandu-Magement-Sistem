@extends('layouts.kader')

@section('title', 'Security Vault')
@section('page-name', 'Keamanan Sandi')

@section('content')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.06); transition: all 0.4s ease; }
    .input-premium { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 14px 16px 14px 44px; width: 100%; font-size: 13px; font-weight: 600; color: #1e293b; transition: all 0.3s ease; outline: none; }
    .input-premium:focus { background-color: #ffffff; border-color: #fb7185; box-shadow: 0 0 0 4px rgba(251, 113, 133, 0.15); }
    .input-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; transition: all 0.3s ease; }
    .input-group:focus-within .input-icon { color: #e11d48; }
</style>

<div class="max-w-[800px] mx-auto relative pb-10">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 animate-slide-up">
        <div>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight font-poppins mb-1">Security Vault</h1>
            <p class="text-slate-500 font-medium text-[14px]">Perbarui kredensial sandi untuk mengamankan data medis Posyandu.</p>
        </div>
        <a href="{{ route('kader.profile.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold text-[13px] rounded-xl hover:bg-slate-50 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="glass-card rounded-[32px] overflow-hidden flex flex-col md:flex-row animate-slide-up" style="animation-delay: 0.1s;">
        
        {{-- Sisi Kiri (Ilustrasi Keamanan) --}}
        <div class="md:w-5/12 bg-gradient-to-br from-rose-500 to-rose-700 p-8 flex flex-col items-center justify-center text-center relative overflow-hidden">
            <div class="absolute -left-10 -bottom-10 w-40 h-40 border-[20px] border-white/10 rounded-full"></div>
            <div class="absolute right-10 top-10 w-10 h-10 bg-white/20 rounded-full blur-sm"></div>
            
            <div class="w-32 h-32 bg-white/10 backdrop-blur-md rounded-[24px] border border-white/20 flex items-center justify-center text-6xl text-white shadow-2xl mb-6 relative z-10 transform rotate-3">
                <i class="fas fa-shield-check"></i>
            </div>
            
            <h3 class="text-2xl font-black text-white font-poppins mb-2 relative z-10">Enkripsi Kuat</h3>
            <p class="text-rose-100 text-[12px] font-medium leading-relaxed relative z-10 px-4">Kami menggunakan teknologi enkripsi Bcrypt. Staf maupun Administrator Posyandu tidak dapat melihat sandi murni Anda.</p>
        </div>

        {{-- Sisi Kanan (Form Input) --}}
        <div class="md:w-7/12 flex flex-col bg-white">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg border border-rose-100"><i class="fas fa-key"></i></div>
                <div>
                    <h3 class="text-lg font-black text-slate-800 font-poppins leading-none">Ubah Kredensial</h3>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Harap gunakan kombinasi yang sulit</p>
                </div>
            </div>

            <form action="{{ route('kader.profile.update-password') }}" method="POST" class="flex flex-col flex-1">
                @csrf @method('PUT')
                
                <div class="p-8 space-y-6 flex-1">
                    
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Sandi Saat Ini</label>
                        <div class="relative input-group">
                            <i class="fas fa-unlock-alt input-icon"></i>
                            <input type="password" name="current_password" required class="input-premium" placeholder="Masukkan sandi lama Anda">
                        </div>
                    </div>
                    
                    <div class="h-px w-full border-t border-dashed border-slate-200 my-2"></div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Sandi Keamanan Baru</label>
                        <div class="relative input-group">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" required minlength="8" class="input-premium" placeholder="Buat sandi baru (Min. 8 karakter)">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 pl-1">Konfirmasi Sandi Baru</label>
                        <div class="relative input-group">
                            <i class="fas fa-check-double input-icon"></i>
                            <input type="password" name="password_confirmation" required class="input-premium" placeholder="Ketik ulang sandi baru">
                        </div>
                    </div>

                </div>

                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-rose-500 to-rose-600 text-white font-black text-[13px] uppercase tracking-widest rounded-xl hover:from-rose-600 hover:to-rose-700 shadow-[0_8px_20px_rgba(225,29,72,0.25)] hover:shadow-[0_10px_25px_rgba(225,29,72,0.35)] hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-fingerprint"></i> Perbarui Akses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection