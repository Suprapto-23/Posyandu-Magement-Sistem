@extends('layouts.user')

@section('content')
<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen flex items-center justify-center">
    
    <div class="max-w-md w-full bg-white rounded-[2rem] border border-pink-100 shadow-[0_10px_40px_-10px_rgba(236,72,153,0.15)] p-8 text-center relative overflow-hidden">
        
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-pink-50 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-rose-50 rounded-full blur-2xl"></div>

        <div class="relative z-10">
            <div class="w-24 h-24 bg-pink-50 text-pink-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-sm border border-pink-100">
                <i class="fas fa-female"></i>
            </div>
            
            <h2 class="text-xl font-black text-slate-800 mb-2">Buku KIA Kandungan Kosong</h2>
            <p class="text-sm font-medium text-slate-500 leading-relaxed mb-8">
                {{ $message ?? 'Sistem tidak menemukan data kehamilan aktif untuk NIK Anda. Jika Anda sedang mengandung, silakan lapor ke Kader Posyandu untuk pendataan.' }}
            </p>

            <div class="space-y-3">
                <a href="{{ route('user.dashboard') }}" class="block w-full py-3 bg-pink-500 text-white text-sm font-bold rounded-xl hover:bg-pink-600 transition-colors shadow-sm">
                    Kembali ke Beranda
                </a>
                <a href="{{ route('user.profile.edit') }}" class="block w-full py-3 bg-white text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 border border-slate-200 transition-colors">
                    Cek NIK di Profil
                </a>
            </div>
        </div>
    </div>

</div>
@endsection