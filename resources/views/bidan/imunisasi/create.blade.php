@extends('layouts.bidan')
@section('title', 'Input Imunisasi Baru')
@section('page-name', 'Tambah Imunisasi')

@section('content')
<div class="max-w-4xl mx-auto animate-pop">

    <div class="bg-white rounded-[32px] p-8 md:p-10 mb-8 border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-full bg-cyan-500/5 blur-3xl rounded-full pointer-events-none"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-black text-slate-800 font-poppins mb-2">Form Registrasi Vaksin</h2>
            <p class="text-slate-500 text-sm font-medium max-w-md">Catat pemberian imunisasi kepada pasien (Balita & Remaja). Data akan terintegrasi secara real-time.</p>
        </div>
        <div class="w-16 h-16 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shrink-0 shadow-sm border border-cyan-100 relative z-10">
            <i class="fas fa-syringe"></i>
        </div>
    </div>

    <form action="{{ route('bidan.imunisasi.store') }}" method="POST" id="imunisasiForm">
        @csrf
        <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden mb-8">
            <div class="bg-slate-50/80 px-8 py-5 border-b border-slate-100">
                <h5 class="font-black text-slate-800 text-sm uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-clipboard-user text-cyan-500"></i> Detail Imunisasi
                </h5>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Pilih Pasien (Dari Kunjungan Terbaru) <span class="text-rose-500">*</span></label>
                        <select name="kunjungan_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/10 outline-none transition-all cursor-pointer">
                            <option value="">-- Pilih Pasien Balita / Remaja --</option>
                            @foreach($kunjungans as $k)
                                <option value="{{ $k->id }}">
                                    {{ \Carbon\Carbon::parse($k->tanggal_kunjungan)->format('d/m') }} - {{ $k->pasien->nama_lengkap ?? 'Unknown' }} ({{ class_basename($k->pasien_type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Nama Vaksin <span class="text-rose-500">*</span></label>
                        <input type="text" name="vaksin" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-cyan-500 transition-all" placeholder="Contoh: BCG / Polio / HPV">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Kategori Imunisasi <span class="text-rose-500">*</span></label>
                        <select name="jenis_imunisasi" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-cyan-500 transition-all">
                            <option value="Dasar">Imunisasi Dasar</option>
                            <option value="Lanjutan">Imunisasi Lanjutan</option>
                            <option value="Tambahan">Imunisasi Tambahan (Booster / WUS)</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Dosis <span class="text-rose-500">*</span></label>
                        <input type="text" name="dosis" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-cyan-500 transition-all" placeholder="Contoh: 0.5 ml">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Tanggal Pemberian <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_imunisasi" value="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:bg-white focus:border-cyan-500 transition-all">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pb-10">
            <a href="{{ route('bidan.imunisasi.index') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl font-bold text-slate-500 bg-white border border-slate-200 hover:bg-slate-50 transition-all shadow-sm text-sm text-center smooth-route">Batal</a>
            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl font-bold text-white bg-cyan-600 hover:bg-cyan-500 hover:-translate-y-1 transition-all shadow-[0_4px_20px_rgba(6,182,212,0.4)] text-sm flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Simpan Imunisasi
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