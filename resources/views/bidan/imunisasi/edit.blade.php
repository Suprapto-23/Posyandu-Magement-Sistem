@extends('layouts.bidan')
@section('title', 'Edit Imunisasi')
@section('page-name', 'Edit Imunisasi')

@section('content')
<div class="max-w-4xl mx-auto animate-pop">

    <div class="bg-white rounded-[32px] p-8 md:p-10 mb-8 border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-full bg-amber-500/5 blur-3xl rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-black text-slate-800 font-poppins mb-2">Edit Data Vaksin</h2>
            <p class="text-slate-500 text-sm font-medium max-w-md">Lakukan perubahan jika terdapat kesalahan input. Perubahan ini akan otomatis sinkron dengan akun Kader.</p>
        </div>
        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-amber-100 relative z-10">
            <i class="fas fa-edit"></i>
        </div>
    </div>

    <form action="{{ route('bidan.imunisasi.update', $imunisasi->id) }}" method="POST" id="imunisasiForm">
        @csrf @method('PUT')
        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Pasien Terkait <span class="text-rose-500">*</span></label>
                        <select name="kunjungan_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-cyan-500 outline-none transition-all cursor-pointer">
                            @foreach($kunjungans as $k)
                                <option value="{{ $k->id }}" {{ $imunisasi->kunjungan_id == $k->id ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($k->tanggal_kunjungan)->format('d/m') }} - {{ $k->pasien->nama_lengkap ?? 'Unknown' }} ({{ class_basename($k->pasien_type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nama Vaksin <span class="text-rose-500">*</span></label>
                        <input type="text" name="vaksin" value="{{ $imunisasi->vaksin }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:border-cyan-500 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Kategori Imunisasi <span class="text-rose-500">*</span></label>
                        <select name="jenis_imunisasi" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:border-cyan-500 transition-all">
                            <option value="Dasar" {{ $imunisasi->jenis_imunisasi == 'Dasar' ? 'selected' : '' }}>Imunisasi Dasar</option>
                            <option value="Lanjutan" {{ $imunisasi->jenis_imunisasi == 'Lanjutan' ? 'selected' : '' }}>Imunisasi Lanjutan</option>
                            <option value="Tambahan" {{ $imunisasi->jenis_imunisasi == 'Tambahan' ? 'selected' : '' }}>Tambahan (Booster / WUS)</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Dosis <span class="text-rose-500">*</span></label>
                        <input type="text" name="dosis" value="{{ $imunisasi->dosis }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:border-cyan-500 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Tanggal Pemberian <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_imunisasi" value="{{ \Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->format('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:border-cyan-500 transition-all">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 pb-10">
            <a href="{{ route('bidan.imunisasi.index') }}" class="px-8 py-3.5 rounded-2xl font-bold text-slate-500 bg-white border border-slate-200 hover:bg-slate-50 smooth-route">Batal</a>
            <button type="submit" id="btnSubmit" class="px-8 py-3.5 rounded-2xl font-bold text-obsidian-900 bg-amber-400 hover:bg-amber-500 transition-all shadow-[0_4px_20px_rgba(245,158,11,0.4)]">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<script>
    document.getElementById('imunisasiForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        btn.classList.add('opacity-75', 'cursor-not-allowed');
    });
</script>
@endsection