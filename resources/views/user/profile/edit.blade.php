@extends('layouts.user')

@section('content')
@php $userAuth = auth()->user(); @endphp
<div class="p-4 md:p-8 font-poppins bg-[#f8fafc] min-h-screen">
    
    <div class="mb-8">
        <div class="inline-flex items-center gap-3 px-4 py-2 bg-slate-100 rounded-full shadow-sm border border-slate-200 mb-4">
            <i class="fas fa-user-shield text-slate-500"></i>
            <span class="text-[11px] font-black tracking-widest uppercase text-slate-700">Pengaturan & Keamanan</span>
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Data Profil Anda ⚙️</h1>
        <p class="text-sm font-medium text-slate-500 mt-2 max-w-2xl leading-relaxed">Kelola informasi pribadi, NIK, dan keamanan akun Anda. Pastikan data yang dimasukkan sesuai dengan KTP.</p>
    </div>

    @if(empty($userAuth->nik) && empty($userAuth->profile->nik))
        <div class="mb-8 bg-rose-50 border border-rose-200 rounded-2xl p-5 flex gap-4 items-start shadow-sm relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-rose-100 rounded-full blur-3xl pointer-events-none"></div>
            <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center shrink-0 shadow-sm z-10">
                <i class="fas fa-exclamation-circle text-xl animate-pulse"></i>
            </div>
            <div class="z-10 flex-1">
                <h3 class="text-base font-black text-rose-800 tracking-tight">Akses Rekam Medis Terkunci!</h3>
                <p class="text-xs font-medium text-rose-700 mt-1 leading-relaxed">Anda belum memasukkan Nomor Induk Kependudukan (NIK). Silakan isi NIK Anda pada formulir di bawah ini agar sistem dapat menyinkronkan data KMS Balita, Lansia, dan riwayat kesehatan keluarga Anda.</p>
            </div>
        </div>
    @endif

    @if(session('status') === 'profile-updated')
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="mb-8 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex gap-3 items-center shadow-sm">
            <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
            <p class="text-sm font-bold text-emerald-700">Profil berhasil diperbarui!</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-8">
            
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Informasi Identitas</h3>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5">Data diri dan kunci sinkronisasi Posyandu</p>
                    </div>
                </div>

                <div class="p-6 md:p-8">
                  <form method="post" action="{{ route('user.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <div class="bg-teal-50/50 border border-teal-100 rounded-2xl p-5 relative overflow-hidden">
                            <div class="absolute right-0 top-0 bottom-0 w-1 bg-teal-500"></div>
                            <label for="nik" class="block text-[11px] font-black text-teal-700 uppercase tracking-widest mb-2">Nomor Induk Kependudukan (NIK) <span class="text-rose-500">*</span></label>
                            <input id="nik" name="nik" type="text" class="w-full px-4 py-3 bg-white border border-teal-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all placeholder:text-slate-300" placeholder="Masukkan 16 Digit NIK KTP Anda" value="{{ old('nik', $userAuth->nik ?? ($userAuth->profile->nik ?? '')) }}" required maxlength="16">
                            @error('nik') <p class="text-xs text-rose-500 mt-2 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                                <input id="name" name="name" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" value="{{ old('name', $userAuth->profile->full_name ?? $userAuth->name) }}" required>
                                @error('name') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Alamat Email</label>
                                <input id="email" name="email" type="email" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" value="{{ old('email', $userAuth->email) }}" required>
                                @error('email') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="tempat_lahir" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Tempat Lahir</label>
                                <input id="tempat_lahir" name="tempat_lahir" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" value="{{ old('tempat_lahir', $userAuth->profile->tempat_lahir ?? '') }}">
                            </div>

                            <div>
                                <label for="tanggal_lahir" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Tanggal Lahir</label>
                                <input id="tanggal_lahir" name="tanggal_lahir" type="date" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" value="{{ old('tanggal_lahir', $userAuth->profile->tanggal_lahir ?? '') }}">
                            </div>

                            <div>
                                <label for="jenis_kelamin" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all cursor-pointer">
                                    <option value="L" {{ old('jenis_kelamin', $userAuth->profile->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $userAuth->profile->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label for="telepon" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">No. WhatsApp / Telepon</label>
                                <input id="telepon" name="telepon" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all" value="{{ old('telepon', $userAuth->profile->telepon ?? '') }}" placeholder="Contoh: 08123456789">
                            </div>
                        </div>

                        <div>
                            <label for="alamat" class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                            <textarea id="alamat" name="alamat" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all">{{ old('alamat', $userAuth->profile->alamat ?? '') }}</textarea>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                            <button type="submit" class="px-6 py-3 bg-teal-600 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-teal-700 transition-colors shadow-sm flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="lg:col-span-1 space-y-8">
            
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Keamanan</h3>
                        <p class="text-[11px] font-medium text-slate-400 mt-0.5">Ubah kata sandi akun</p>
                    </div>
                </div>

                <div class="p-6">
                    <form method="post" action="{{ route('user.password.update') }}" class="space-y-5">
                        @csrf
                        @method('put')

                        <div>
                            <label for="current_password" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Password Saat Ini</label>
                            <input id="current_password" name="current_password" type="password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            @error('current_password', 'updatePassword') <p class="text-[10px] text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Password Baru</label>
                            <input id="password" name="password" type="password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                            @error('password', 'updatePassword') <p class="text-[10px] text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                        </div>

                        <div class="pt-4 mt-2 border-t border-slate-100">
                            <button type="submit" class="w-full py-2.5 bg-slate-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-slate-900 transition-colors shadow-sm">
                                Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-gradient-to-br from-sky-500 to-blue-600 rounded-3xl p-6 text-white shadow-md relative overflow-hidden">
                <i class="fas fa-headset absolute -right-4 -bottom-4 text-7xl text-white opacity-10"></i>
                <div class="relative z-10">
                    <h3 class="text-sm font-black uppercase tracking-widest mb-2">Butuh Bantuan?</h3>
                    <p class="text-xs font-medium text-sky-50 leading-relaxed mb-4">Jika Anda mengalami kendala saat mengubah NIK atau data tidak muncul, silakan hubungi Kader Posyandu di desa Anda.</p>
                    <a href="{{ route('user.konseling.index') }}" class="inline-block px-4 py-2 bg-white text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-lg shadow-sm hover:bg-slate-50 transition-colors">
                        Hubungi via Chat
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection