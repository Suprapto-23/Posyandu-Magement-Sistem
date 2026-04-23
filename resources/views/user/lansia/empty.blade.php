@extends('layouts.user')

@section('content')
<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen flex items-center justify-center">
    
    <div class="max-w-md w-full bg-white rounded-[2rem] border border-orange-100 shadow-[0_10px_40px_-10px_rgba(249,115,22,0.15)] p-8 text-center relative overflow-hidden">
        
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-50 rounded-full blur-2xl"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-amber-50 rounded-full blur-2xl"></div>

        <div class="relative z-10">
            <div class="w-24 h-24 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-sm border border-orange-100">
                <i class="fas fa-wheelchair"></i>
            </div>
            
            <h2 class="text-xl font-black text-slate-800 mb-2">Data Lansia Belum Terdaftar</h2>
            <p class="text-sm font-medium text-slate-500 leading-relaxed mb-8">
                Kami tidak menemukan data rekam medis Lansia (Lanjut Usia) yang terhubung dengan NIK Anda.
            </p>

            <div class="space-y-3">
                <a href="{{ route('user.dashboard') }}" class="block w-full py-3 bg-orange-500 text-white text-sm font-bold rounded-xl hover:bg-orange-600 transition-colors shadow-sm">
                    Kembali ke Beranda
                </a>
                <a href="{{ route('user.profile.edit') }}" class="block w-full py-3 bg-white text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-50 border border-slate-200 transition-colors">
                    Periksa NIK di Profil
                </a>
            </div>
        </div>
    </div>

</div>
@endsection