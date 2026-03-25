@extends('layouts.admin')
@section('title', 'Manajemen User Warga')
@section('page-name', 'Data Warga')

@section('content')
<div class="max-w-6xl mx-auto" style="animation: menuPop 0.4s ease-out forwards;">

    {{-- Hero Section (Simetri Rata Tengah) --}}
    <div class="bg-gradient-to-br from-obsidian-900 to-slate-800 rounded-[32px] p-10 mb-8 relative overflow-hidden shadow-[0_20px_40px_-10px_rgba(15,23,42,0.4)] border border-slate-700 flex flex-col items-center justify-center text-center group">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-amber-500/20 blur-[80px] rounded-full pointer-events-none transition-all duration-700 group-hover:bg-amber-500/30"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-md border border-white/10 text-amber-400 text-[11px] font-black px-4 py-1.5 rounded-full mb-4 uppercase tracking-widest shadow-sm">
                <i class="fas fa-users"></i> Manajemen Data
            </div>
            <h2 class="text-3xl md:text-4xl font-black text-white mb-3 font-poppins tracking-tight">Daftar Akun Warga</h2>
            <p class="text-slate-400 text-sm font-medium max-w-lg mx-auto mb-6">Kelola seluruh akun warga Posyandu. Gunakan NIK 16 digit yang valid sebagai kredensial utama sistem.</p>
            
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-obsidian-900 font-bold px-6 py-3 rounded-xl transition-all shadow-[0_4px_20px_rgba(245,158,11,0.4)] hover:shadow-[0_4px_25px_rgba(245,158,11,0.6)] hover:-translate-y-1 smooth-route">
                <i class="fas fa-plus"></i> Daftarkan Warga Baru
            </a>
        </div>
    </div>

    {{-- Menampilkan Informasi Kredensial / Password Baru --}}
    @if(session('generated_password') || session('reset_password'))
    <div class="bg-amber-50 border border-amber-300 rounded-[24px] p-6 mb-8 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-amber-200 text-amber-700 flex items-center justify-center text-xl shrink-0">
                <i class="fas fa-key"></i>
            </div>
            <div>
                <h4 class="text-amber-900 font-black text-sm mb-1 uppercase tracking-widest">Kredensial Login Dibuat!</h4>
                <p class="text-xs font-medium text-amber-700">Berikan password ini kepada: <strong class="text-amber-900">{{ session('user_name') ?? session('reset_name') }}</strong> (NIK: {{ session('user_nik') ?? session('reset_nik') }})</p>
            </div>
        </div>
        <div class="bg-white border-2 border-amber-200 rounded-xl px-5 py-3 flex items-center gap-4 shadow-inner">
            <code class="text-xl font-mono font-black text-obsidian-900 tracking-wider" id="passwordText">{{ session('generated_password') ?? session('reset_password') }}</code>
            <button onclick="copyPassword()" class="text-xs bg-amber-100 hover:bg-amber-500 hover:text-white text-amber-800 px-3 py-1.5 rounded-lg font-bold transition-all" title="Copy Password">
                <i class="fas fa-copy"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- Alert Success Reguler --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 mb-8 flex items-center justify-center text-center gap-3 text-emerald-700 font-bold shadow-sm">
        <i class="fas fa-check-circle text-xl"></i> {{ session('success') }}
    </div>
    @endif

    {{-- KARTU STATISTIK (Mengambil data dari $stats Controller) --}}
    @if(isset($stats))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-[24px] p-6 border border-slate-200 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl"><i class="fas fa-users"></i></div>
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Total Warga</p>
                <h3 class="text-2xl font-black text-obsidian-900">{{ $stats['total'] }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-[24px] p-6 border border-slate-200 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl"><i class="fas fa-user-check"></i></div>
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Akun Aktif</p>
                <h3 class="text-2xl font-black text-obsidian-900">{{ $stats['aktif'] }}</h3>
            </div>
        </div>
        <div class="bg-white rounded-[24px] p-6 border border-slate-200 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl"><i class="fas fa-user-lock"></i></div>
            <div>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Akun Nonaktif</p>
                <h3 class="text-2xl font-black text-obsidian-900">{{ $stats['nonaktif'] }}</h3>
            </div>
        </div>
    </div>
    @endif

    {{-- Data Table --}}
    <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="px-8 py-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/50">
            <h3 class="text-lg font-black text-obsidian-900 font-poppins flex items-center justify-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white text-obsidian-900 border border-slate-200 flex items-center justify-center text-sm shadow-sm"><i class="fas fa-list"></i></div>
                Direktori Warga
            </h3>
            
            <form method="GET" class="flex relative w-full md:w-auto">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIK / Nama..." class="w-full md:w-80 bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-3 text-sm font-medium focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all shadow-sm">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">
                        <th class="py-4 px-6 text-left">Informasi Warga</th>
                        <th class="py-4 px-6">NIK KTP</th>
                        <th class="py-4 px-6">Kontak / Telp</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium text-slate-700">
                    @forelse($users ?? [] as $u)
                    <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                        
                        {{-- Kolom 1: Nama Warga & Email --}}
                        <td class="py-4 px-6 text-left">
                            <div class="flex items-center gap-4">
                                <div class="w-11 h-11 rounded-full bg-obsidian-900 text-amber-500 flex items-center justify-center font-black shadow-sm shrink-0 text-lg">
                                    {{ strtoupper(substr($u->profile->full_name ?? $u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-base mb-0.5">{{ $u->profile->full_name ?? $u->name }}</div>
                                    <div class="text-[11px] text-slate-500 flex items-center gap-1.5">
                                        <i class="fas fa-envelope text-slate-400"></i> {{ $u->email }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Kolom 2: NIK --}}
                        <td class="py-4 px-6 text-center">
                            <span class="font-mono text-xs font-bold bg-white px-3 py-1.5 rounded-lg border border-slate-200 text-slate-600 tracking-widest shadow-sm">
                                {{ $u->nik ?? $u->profile?->nik ?? '-' }}
                            </span>
                        </td>

                        {{-- Kolom 3: Kontak Telepon --}}
                        <td class="py-4 px-6 text-center text-slate-600 font-semibold">
                            {{ $u->profile?->telepon ?? '-' }}
                        </td>

                        {{-- Kolom 4: Status Akun --}}
                        <td class="py-4 px-6 text-center">
                            @if($u->status === 'active')
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-200 px-3 py-1.5 rounded-lg text-[10px] font-black tracking-widest uppercase"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                            @else
                                <span class="bg-rose-50 text-rose-600 border border-rose-200 px-3 py-1.5 rounded-lg text-[10px] font-black tracking-widest uppercase"><i class="fas fa-ban mr-1"></i> Nonaktif</span>
                            @endif
                        </td>

                        {{-- Kolom 5: Aksi --}}
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.show', $u->id) }}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Edit Data"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data warga ini beserta riwayatnya?')" class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 text-slate-300 mb-4 border border-slate-100 shadow-inner"><i class="fas fa-users-slash text-3xl"></i></div>
                            <h4 class="text-sm font-black text-slate-500 uppercase tracking-widest mb-2">Tidak ada data warga</h4>
                            <p class="text-xs font-medium text-slate-400">Silakan daftarkan warga baru atau ubah kata kunci pencarian Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Pagination --}}
    @if(isset($users) && $users->hasPages())
    <div class="mt-6 flex justify-center pb-8">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif

</div>

{{-- Script untuk tombol Copy Password --}}
<script>
    function copyPassword() {
        var passwordText = document.getElementById("passwordText").innerText;
        navigator.clipboard.writeText(passwordText).then(function() {
            alert("Password disalin: " + passwordText);
        }, function(err) {
            console.error('Gagal menyalin text: ', err);
        });
    }
</script>
@endsection