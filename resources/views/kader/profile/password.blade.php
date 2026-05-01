@extends('layouts.kader')

@section('title', 'Security Vault')
@section('page-name', 'Keamanan Sandi')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    /* NEXUS ANIMATION SYSTEM */
    .fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    /* GLASS CARD PREMIUM */
    .nexus-glass { 
        background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(20px); 
        border: 1px solid rgba(255, 255, 255, 0.8); 
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05); 
        border-radius: 32px;
        transition: all 0.4s ease;
    }
    
    /* INPUT NEXUS (ANTI-FLAT) */
    .input-nexus { 
        background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; 
        padding: 14px 48px 14px 44px; /* Ruang khusus ikon mata di kanan */
        width: 100%; font-size: 13px; font-weight: 700; 
        color: #1e293b; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); outline: none; 
    }
    .input-nexus:focus { 
        background-color: #ffffff; border-color: #f43f5e; 
        box-shadow: 0 8px 25px -5px rgba(244, 63, 94, 0.15); 
    }
    
    /* IKON KIRI & KANAN */
    .input-icon-left { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; transition: all 0.3s ease; pointer-events: none; }
    .input-icon-right { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; transition: all 0.3s ease; cursor: pointer; padding: 4px; }
    
    .input-group:focus-within .input-icon-left { color: #f43f5e; scale: 1.1; }
    .input-icon-right:hover { color: #f43f5e; }

    /* SWEETALERT NEXUS OVERRIDE MUTLAK */
    .swal2-container.nexus-backdrop { backdrop-filter: blur(10px) !important; background: rgba(15, 23, 42, 0.5) !important; }
    .swal2-popup.nexus-popup {
        border-radius: 36px !important; padding: 2.5rem 2rem !important;
        background: rgba(255, 255, 255, 0.98) !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        box-shadow: 0 25px 60px -15px rgba(0,0,0,0.2) !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-[1000px] mx-auto relative pb-16 fade-in-up">

    {{-- Latar Belakang Dekoratif --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-br from-rose-50/50 to-transparent rounded-full blur-3xl pointer-events-none z-0"></div>

    {{-- 1. HEADER BANNER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 relative z-10">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[24px] bg-rose-600 text-white flex items-center justify-center text-3xl shadow-[0_10px_25px_rgba(225,29,72,0.35)] transform -rotate-3">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight font-poppins">Security Vault</h1>
                <p class="text-slate-500 font-medium text-sm">Perbarui kredensial sandi untuk mengamankan data medis Posyandu.</p>
            </div>
        </div>
        <a href="{{ route('kader.profile.index') }}" class="inline-flex items-center justify-center gap-3 px-8 py-3.5 bg-white border border-slate-200 text-slate-600 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm group">
            <i class="fas fa-arrow-left text-slate-400 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Profil
        </a>
    </div>

    {{-- 2. KARTU UTAMA (GRID LAYOUT MUTLAK SEJAJAR) --}}
    <div class="nexus-glass overflow-hidden grid grid-cols-1 md:grid-cols-12 relative z-10 shadow-2xl">
        
        {{-- Sisi Kiri (Ilustrasi Keamanan) --}}
        <div class="md:col-span-5 bg-gradient-to-br from-rose-500 via-rose-600 to-rose-700 p-10 flex flex-col items-center justify-center text-center relative overflow-hidden h-full">
            {{-- Aksen Latar Belakang Kiri --}}
            <div class="absolute -left-16 -bottom-16 w-64 h-64 border-[30px] border-white/10 rounded-full z-0"></div>
            <div class="absolute right-10 top-10 w-24 h-24 bg-white/20 rounded-full blur-2xl z-0"></div>
            
            {{-- Ikon Gembok Raksasa --}}
            <div class="w-32 h-32 bg-white/10 backdrop-blur-md rounded-[28px] border border-white/20 flex items-center justify-center text-6xl text-white shadow-2xl mb-8 relative z-10 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                <i class="fas fa-lock"></i>
            </div>
            
            <h3 class="text-2xl font-black text-white font-poppins mb-3 relative z-10">Enkripsi Tingkat Tinggi</h3>
            <p class="text-rose-100 text-[12px] font-medium leading-relaxed relative z-10 max-w-[250px] mx-auto">Kami menggunakan teknologi <strong>Bcrypt Hashing</strong>. Tidak ada satupun administrator sistem yang dapat melihat sandi murni Anda.</p>
        </div>

        {{-- Sisi Kanan (Formulir) --}}
        <div class="md:col-span-7 flex flex-col bg-white/50 h-full">
            <div class="px-10 py-8 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl border border-rose-100 shadow-sm shrink-0"><i class="fas fa-key"></i></div>
                <div>
                    <h3 class="text-xl font-black text-slate-800 font-poppins leading-none">Ubah Kredensial</h3>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1.5">Gunakan Kombinasi Sandi Yang Kuat</p>
                </div>
            </div>

            <form action="{{ route('kader.profile.update-password') }}" method="POST" class="flex flex-col flex-1">
                @csrf @method('PUT')
                
                <div class="p-10 space-y-7 flex-1">
                    
                    {{-- Input: Sandi Lama --}}
                    <div class="input-group relative">
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2.5 pl-1">Sandi Saat Ini</label>
                        <div class="relative">
                            <i class="fas fa-unlock-alt input-icon-left"></i>
                            <input type="password" name="current_password" required class="input-nexus" placeholder="Masukkan sandi lama Anda">
                            <i class="fas fa-eye input-icon-right toggle-password" title="Tampilkan Sandi"></i>
                        </div>
                    </div>
                    
                    <div class="h-px w-full border-t border-dashed border-slate-200 my-2"></div>

                    {{-- Input: Sandi Baru --}}
                    <div class="input-group relative">
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2.5 pl-1">Sandi Keamanan Baru</label>
                        <div class="relative">
                            <i class="fas fa-shield-alt input-icon-left"></i>
                            <input type="password" name="password" required minlength="8" class="input-nexus" placeholder="Buat sandi baru (Min. 8 Karakter)">
                            <i class="fas fa-eye input-icon-right toggle-password" title="Tampilkan Sandi"></i>
                        </div>
                    </div>

                    {{-- Input: Konfirmasi Sandi Baru --}}
                    <div class="input-group relative">
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2.5 pl-1">Konfirmasi Sandi Baru</label>
                        <div class="relative">
                            <i class="fas fa-check-double input-icon-left"></i>
                            <input type="password" name="password_confirmation" required minlength="8" class="input-nexus" placeholder="Ketik ulang sandi baru di atas">
                            <i class="fas fa-eye input-icon-right toggle-password" title="Tampilkan Sandi"></i>
                        </div>
                    </div>

                </div>

                {{-- Footer Action (Perbaikan Warna Teks Tombol) --}}
                <div class="px-10 py-7 bg-slate-50/80 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-3 px-10 py-4 bg-gradient-to-r from-rose-500 to-rose-600 !text-white font-black text-[13px] uppercase tracking-widest rounded-2xl hover:scale-105 hover:shadow-[0_12px_25px_rgba(225,29,72,0.3)] hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-fingerprint text-lg !text-white"></i> Terapkan Sandi Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // LOGIKA FITUR HIDE/SHOW PASSWORD YANG SUPER MULUS
    document.addEventListener("DOMContentLoaded", function() {
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                
                if (input.type === "password") {
                    input.type = "text";
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash', 'text-rose-500');
                } else {
                    input.type = "password";
                    this.classList.remove('fa-eye-slash', 'text-rose-500');
                    this.classList.add('fa-eye');
                }
            });
        });
    });

    // PENANGANAN NOTIFIKASI NEXUS CRM
    @if(session('success'))
        Swal.fire({
            html: `
                <div class="flex flex-col items-center p-2 text-center">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-6 border border-emerald-100 shadow-inner">
                        <i class="fas fa-shield-check text-emerald-500 text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 font-poppins tracking-tight mb-2">Keamanan Diperbarui</h2>
                    <p class="text-[13px] text-slate-500 font-medium leading-relaxed">{{ session("success") }}</p>
                </div>
            `,
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            customClass: { container: 'nexus-backdrop', popup: 'nexus-popup' }
        });
    @endif

    @if($errors->any() || session('error'))
        Swal.fire({
            html: `
                <div class="flex flex-col items-center p-2 text-center">
                    <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mb-6 border border-rose-100">
                        <i class="fas fa-lock text-rose-500 text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 font-poppins tracking-tight mb-2">Akses Ditolak</h2>
                    <p class="text-[13px] text-slate-500 font-medium leading-relaxed">{{ session("error") ?? $errors->first() }}</p>
                </div>
            `,
            confirmButtonText: 'Koreksi Sandi',
            buttonsStyling: false,
            customClass: { 
                container: 'nexus-backdrop', 
                popup: 'nexus-popup', 
                confirmButton: 'bg-rose-600 text-white font-black text-[11px] uppercase tracking-widest px-10 py-4 rounded-full mt-4 shadow-lg hover:bg-rose-700 transition-colors' 
            }
        });
    @endif
</script>
@endpush