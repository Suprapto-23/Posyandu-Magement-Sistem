@extends('layouts.kader')

@section('title', 'Kader Identity Center')
@section('page-name', 'Pusat Identitas & Akreditasi')

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
        box-shadow: 0 10px 40px -10px rgba(79, 70, 229, 0.05); 
        border-radius: 32px;
        transition: all 0.4s ease;
    }
    
    /* INPUT NEXUS (ANTI-FLAT) */
    .input-nexus { 
        background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 18px; 
        padding: 14px 16px 14px 48px; width: 100%; font-size: 13px; font-weight: 700; 
        color: #1e293b; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); outline: none; 
    }
    .input-nexus:focus { 
        background-color: #ffffff; border-color: #6366f1; 
        box-shadow: 0 8px 25px -5px rgba(99, 102, 241, 0.15); 
        transform: translateY(-2px);
    }
    .input-icon { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; transition: all 0.3s ease; }
    .input-group:focus-within .input-icon { color: #6366f1; scale: 1.1; }

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
<div class="max-w-[1250px] mx-auto relative pb-16 fade-in-up">

    {{-- Latar Belakang Dekoratif --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-br from-indigo-50/50 to-transparent rounded-full blur-3xl pointer-events-none z-0"></div>

    {{-- 1. HEADER BANNER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 relative z-10">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-[24px] bg-indigo-600 text-white flex items-center justify-center text-3xl shadow-[0_10px_25px_rgba(79,70,229,0.35)] transform -rotate-3">
                <i class="fas fa-id-badge"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight font-poppins">Identity Center</h1>
                <p class="text-slate-500 font-medium text-sm">Otorisasi & kredensial petugas medis Posyandu Bantarkulon.</p>
            </div>
        </div>
        <a href="{{ route('kader.profile.password') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white border border-slate-200 text-slate-700 font-black text-[11px] uppercase tracking-widest rounded-2xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all shadow-sm group">
            <i class="fas fa-shield-alt text-slate-400 group-hover:text-rose-500 transition-colors"></i> Keamanan & Akses
        </a>
    </div>

    {{-- 2. GRID UTAMA (KIRI DAN KANAN AKAN SEJAJAR TINGGINYA) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10 items-stretch">
        
        {{-- KIRI: VIRTUAL ID CARD (Di-stretch setinggi kolom kanan) --}}
        <div class="lg:col-span-4 nexus-glass relative overflow-hidden flex flex-col h-full">
            
            {{-- Banner Atas (Ungu) --}}
            <div class="absolute top-0 left-0 w-full h-36 bg-gradient-to-br from-indigo-600 via-indigo-500 to-violet-600 z-0"></div>
            <div class="absolute top-4 right-4 text-white/20 text-3xl z-0"><i class="fas fa-qrcode"></i></div>
            
            {{-- Bagian Atas: Avatar & Nama --}}
            <div class="relative z-10 p-8 flex flex-col items-center flex-1">
                <div class="w-36 h-36 mx-auto rounded-[28px] bg-white p-2.5 shadow-2xl mb-6 mt-6 transform rotate-2 hover:rotate-0 transition-transform duration-500 border border-white/50 shrink-0">
                    <div class="w-full h-full rounded-[20px] bg-slate-100 flex items-center justify-center text-6xl font-black text-indigo-300 border border-slate-200">
                        {{ strtoupper(substr($user->profile->full_name ?? $user->name ?? 'K', 0, 1)) }}
                    </div>
                </div>

                <h3 class="text-2xl font-black text-slate-800 font-poppins leading-tight mb-2 text-center">{{ $user->profile->full_name ?? $user->name }}</h3>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.1em]">Kader Aktif</span>
                </div>
            </div>

            {{-- Bagian Bawah: Info Kontak (Didorong ke paling bawah dengan mt-auto) --}}
            <div class="relative z-10 p-8 pt-0 mt-auto w-full">
                <div class="space-y-4">
                    <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100 flex items-center gap-4 hover:bg-white hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-[14px] bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm shrink-0 border border-indigo-100"><i class="fas fa-envelope text-[15px]"></i></div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Email Terdaftar</p>
                            <p class="text-[13px] font-bold text-slate-700 truncate mt-0.5">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100 flex items-center gap-4 hover:bg-white hover:shadow-md transition-all">
                        <div class="w-12 h-12 rounded-[14px] bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm shrink-0 border border-emerald-100"><i class="fas fa-id-badge text-[15px]"></i></div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nomor Petugas</p>
                            <p class="text-[13px] font-bold text-slate-700 truncate mt-0.5">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: FORMULIR BIODATA LENGKAP --}}
        <div class="lg:col-span-8 nexus-glass overflow-hidden flex flex-col h-full">
            <div class="px-8 py-6 border-b border-slate-100 bg-white/50 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl border border-indigo-100 shadow-sm"><i class="fas fa-user-edit"></i></div>
                <div>
                    <h3 class="text-xl font-black text-slate-800 font-poppins leading-none">Biodata Petugas</h3>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1.5">Informasi Profil Mendalam</p>
                </div>
            </div>

            <form action="{{ route('kader.profile.update') }}" method="POST" class="flex flex-col flex-1">
                @csrf @method('PUT')
                
                <div class="p-8 space-y-8 flex-1">
                    {{-- Row 1: Nama & NIK --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="input-group">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2.5 pl-1">Nama Lengkap (Sesuai KTP)</label>
                            <div class="relative">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" name="name" value="{{ old('name', $user->profile->full_name ?? $user->name) }}" required class="input-nexus" placeholder="Masukkan nama lengkap">
                            </div>
                        </div>
                        <div class="input-group">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2.5 pl-1">Nomor Induk Kependudukan (NIK)</label>
                            <div class="relative">
                                <i class="fas fa-id-card input-icon"></i>
                                <input type="number" name="nik" value="{{ old('nik', $user->profile->nik ?? '') }}" class="input-nexus" placeholder="16 Digit NIK Petugas">
                            </div>
                        </div>
                    </div>
                    
                    {{-- Row 2: Kontak --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="input-group">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2.5 pl-1">Email Terdaftar</label>
                            <div class="relative">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="input-nexus" placeholder="alamat@email.com">
                            </div>
                        </div>
                        <div class="input-group">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2.5 pl-1">Nomor Seluler / WhatsApp</label>
                            <div class="relative">
                                <i class="fas fa-phone-alt input-icon"></i>
                                <input type="number" name="telepon" value="{{ old('telepon', $user->profile->telepon ?? '') }}" class="input-nexus" placeholder="Contoh: 0812xxxxxxxx">
                            </div>
                        </div>
                    </div>

                    <div class="h-px w-full border-t border-dashed border-slate-200"></div>

                    {{-- Row 3: TTL & JK --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="input-group">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2.5 pl-1">Tempat Lahir</label>
                            <div class="relative">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user->profile->tempat_lahir ?? '') }}" class="input-nexus" placeholder="Kota Kelahiran">
                            </div>
                        </div>
                        <div class="input-group">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2.5 pl-1">Tanggal Lahir</label>
                            <div class="relative">
                                <i class="fas fa-calendar-alt input-icon"></i>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', optional($user->profile->tanggal_lahir)->format('Y-m-d') ?? '') }}" class="input-nexus">
                            </div>
                        </div>
                        <div class="input-group">
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2.5 pl-1">Jenis Kelamin</label>
                            <div class="relative">
                                <i class="fas fa-venus-mars input-icon"></i>
                                <select name="jenis_kelamin" class="input-nexus appearance-none cursor-pointer">
                                    <option value="">-- Pilih --</option>
                                    <option value="P" {{ old('jenis_kelamin', $user->profile->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    <option value="L" {{ old('jenis_kelamin', $user->profile->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Row 4: Alamat --}}
                    <div class="input-group">
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2.5 pl-1">Domisili Lengkap</label>
                        <div class="relative">
                            <i class="fas fa-home input-icon" style="top: 24px;"></i>
                            <textarea name="alamat" rows="3" class="input-nexus resize-none leading-relaxed" placeholder="Tuliskan alamat tinggal saat ini...">{{ old('alamat', $user->profile->alamat ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- FOOTER KANAN --}}
                <div class="px-8 py-6 bg-slate-50/80 border-t border-slate-100 rounded-b-[32px] flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex-1 flex items-center gap-3 text-slate-500">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center border border-slate-200 shadow-sm shrink-0">
                            <i class="fas fa-info-circle text-[11px] text-indigo-500"></i>
                        </div>
                        <p class="text-[11px] font-bold italic leading-relaxed">Pastikan NIK valid untuk sinkronisasi data laporan Kemenkes.</p>
                    </div>
                    <button type="submit" class="w-full md:w-auto shrink-0 inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-violet-700 text-white font-black text-[12px] uppercase tracking-widest rounded-xl hover:shadow-[0_10px_20px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all duration-300">
                        <i class="fas fa-save"></i> Perbarui Identitas
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
    @if(session('success'))
        Swal.fire({
            html: `
                <div class="flex flex-col items-center p-2 text-center">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-6 border border-emerald-100 shadow-inner">
                        <i class="fas fa-check-circle text-emerald-500 text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 font-poppins tracking-tight mb-2">Pembaruan Berhasil</h2>
                    <p class="text-[13px] text-slate-500 font-medium leading-relaxed">{{ session("success") }}</p>
                </div>
            `,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { container: 'nexus-backdrop', popup: 'nexus-popup' }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            html: `
                <div class="flex flex-col items-center p-2 text-center">
                    <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mb-6 border border-rose-100">
                        <i class="fas fa-exclamation-triangle text-rose-500 text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 font-poppins tracking-tight mb-2">Terjadi Galat</h2>
                    <p class="text-[13px] text-slate-500 font-medium leading-relaxed">{{ session("error") }}</p>
                </div>
            `,
            confirmButtonText: 'Koreksi Data',
            buttonsStyling: false,
            customClass: { 
                container: 'nexus-backdrop', 
                popup: 'nexus-popup', 
                confirmButton: 'bg-slate-900 text-white font-black text-[11px] uppercase tracking-widest px-10 py-4 rounded-full mt-4 shadow-lg' 
            }
        });
    @endif
</script>
@endpush