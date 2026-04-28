@extends('layouts.kader')
@section('title', 'Presensi Berhasil')
@section('page-name', 'Status Sistem')

@push('styles')
<style>
    /* Animasi Masuk Halaman yang Sangat Lembut */
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }

    /* Desain Kartu Glassmorphism (Kaca) 3D */
    .glass-card {
        background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 
            0 30px 60px -15px rgba(0, 0, 0, 0.05), 
            0 0 0 1px rgba(16, 185, 129, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 1);
    }

    /* Efek Gradient pada Teks Heading */
    .gradient-text {
        background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Tombol Utama 3D */
    .btn-primary-3d {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4), inset 0 2px 0 rgba(255, 255, 255, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-primary-3d:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px -5px rgba(16, 185, 129, 0.5), inset 0 2px 0 rgba(255, 255, 255, 0.2);
    }

    /* Tombol Sekunder 3D */
    .btn-secondary-3d {
        background: #ffffff;
        color: #475569;
        border: 2px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-secondary-3d:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 8px 15px -3px rgba(0, 0, 0, 0.05);
    }

    /* Pola Latar Belakang Lingkaran Bercahaya */
    .glow-circle {
        position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.5; z-index: -1; pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="relative max-w-4xl mx-auto animate-slide-up pb-10 flex flex-col items-center justify-center min-h-[75vh] z-10">
    
    {{-- Latar Belakang Abstrak Bercahaya --}}
    <div class="glow-circle w-[400px] h-[400px] bg-emerald-200 top-0 left-1/4 transform -translate-x-1/2 -translate-y-1/4"></div>
    <div class="glow-circle w-[300px] h-[300px] bg-indigo-100 bottom-0 right-1/4 transform translate-x-1/4 translate-y-1/4"></div>

    {{-- KARTU UTAMA GLASSMORPHISM --}}
    <div class="glass-card rounded-[40px] p-10 md:p-16 w-full text-center relative overflow-hidden">
        
        {{-- ANIMASI LOTTIE (Link CDN Permanen & Stabil) --}}
        <div class="flex justify-center mb-2 relative z-10">
            <lottie-player 
                src="https://assets8.lottiefiles.com/packages/lf20_touohxv0.json" 
                background="transparent" 
                speed="1" 
                style="width: 220px; height: 220px;" 
                autoplay
                keep-last-frame>
            </lottie-player>
        </div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50/80 border border-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-widest mb-4 shadow-sm">
                <i class="fas fa-shield-check"></i> Enkripsi Database Aman
            </div>

            <h1 class="text-3xl md:text-4xl font-black gradient-text tracking-tight font-poppins mb-4">
                Presensi Berhasil Disimpan!
            </h1>
            
            <p class="text-slate-500 font-medium text-[15px] leading-relaxed max-w-md mx-auto mb-10">
                Luar biasa! Data kehadiran sesi Posyandu hari ini telah terekam dan disinkronisasi dengan aman ke dalam sistem <span class="font-bold text-slate-700">PosyanduCare</span>.
            </p>

            {{-- TOMBOL AKSI --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('kader.absensi.index') }}" class="btn-secondary-3d w-full sm:w-auto px-8 py-4 text-[13px] font-black uppercase tracking-widest rounded-[20px] flex items-center justify-center gap-2">
                    <i class="fas fa-redo-alt text-slate-400"></i> Input Kategori Lain
                </a>
                
                <a href="{{ route('kader.absensi.riwayat') }}" class="btn-primary-3d w-full sm:w-auto px-10 py-4 text-white text-[13px] font-black uppercase tracking-widest rounded-[20px] flex items-center justify-center gap-2">
                    <i class="fas fa-list-ul"></i> Lihat Riwayat Absensi
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Library Lottie Player --}}
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

{{-- Library Canvas Confetti untuk Efek Kembang Api --}}
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<script>
    window.onload = function() {
        var duration = 3 * 1000;
        var animationEnd = Date.now() + duration;
        var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 100 };

        function randomInRange(min, max) {
            return Math.random() * (max - min) + min;
        }

        var interval = setInterval(function() {
            var timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            var particleCount = 50 * (timeLeft / duration);
            
            // Konfeti menyembur dari dua sisi (kiri dan kanan bawah)
            confetti(Object.assign({}, defaults, { 
                particleCount, 
                origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } 
            }));
            confetti(Object.assign({}, defaults, { 
                particleCount, 
                origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } 
            }));
        }, 250);
    };
</script>
@endpush
@endsection