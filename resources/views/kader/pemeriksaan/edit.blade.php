@extends('layouts.kader')
@section('title', 'Edit Pemeriksaan')
@section('page-name', 'Koreksi Data')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .form-label { display: block; font-size: 0.70rem; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; text-align: left;}
    .form-input { width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a; font-size: 0.875rem; border-radius: 1rem; padding: 1rem 1.25rem; outline: none; transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02); text-align: left; }
    .form-input:focus { background-color: #ffffff; border-color: #f59e0b; box-shadow: 0 4px 20px -3px rgba(245, 158, 11, 0.15); transform: translateY(-2px); }
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }
</style>
@endpush

@section('content')
<div class="max-w-[1000px] mx-auto animate-slide-up relative pb-12 text-center sm:text-left">
    
    <div class="mb-6 flex items-center justify-center sm:justify-start gap-3 relative z-10">
        <a href="{{ route('kader.pemeriksaan.index') }}" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-amber-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <div class="bg-gradient-to-br from-amber-400 via-orange-500 to-amber-600 rounded-[32px] p-8 md:p-12 mb-8 relative overflow-hidden shadow-lg flex flex-col md:flex-row items-center justify-center sm:justify-start gap-8 z-10">
        <div class="w-24 h-24 rounded-[24px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-4xl shrink-0 shadow-lg transform rotate-3"><i class="fas fa-pen-nib"></i></div>
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white text-[10px] font-black px-3 py-1.5 rounded-full mb-3 uppercase tracking-widest mx-auto sm:mx-0">Mode Koreksi Aktif</div>
            <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">Edit Data Pengukuran</h1>
            <p class="text-amber-50 font-medium text-[14px] max-w-xl mx-auto sm:mx-0">Lakukan koreksi pada data antropometri yang telah dimasukkan jika terdapat kesalahan.</p>
        </div>
    </div>

    <form action="{{ route('kader.pemeriksaan.update', $pemeriksaan->id) }}" method="POST" id="formPemeriksaan">
        @csrf @method('PUT')
        
        <div class="glass-panel rounded-[32px] shadow-sm overflow-hidden flex flex-col mb-8 text-left">
            <div class="p-8 md:p-10 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-center sm:justify-between gap-6 text-center sm:text-left">
                <div class="flex flex-col sm:flex-row items-center gap-5">
                    <div class="w-16 h-16 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-2xl font-black border border-slate-300">{{ strtoupper(substr($pemeriksaan->nama_pasien, 0, 1)) }}</div>
                    <div>
                        <h4 class="text-xl font-black text-slate-800">{{ $pemeriksaan->nama_pasien }}</h4>
                        <p class="text-[12px] font-bold text-slate-400 mt-1"><i class="far fa-calendar-check text-amber-500 mr-1"></i> Tgl: {{ \Carbon\Carbon::parse($pemeriksaan->tanggal_periksa)->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="px-5 py-2 bg-white border border-slate-200 rounded-xl text-center shadow-sm">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Kategori Warga</span>
                    <span class="text-[14px] font-black text-amber-600 uppercase tracking-wider">{{ str_replace('_', ' ', $pemeriksaan->kategori_pasien) }}</span>
                </div>
            </div>

            <div class="p-8 md:p-12 bg-white">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mb-8 text-left">
                    <div class="col-span-1">
                        <label class="form-label">Berat B. (kg) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="berat_badan" value="{{ $pemeriksaan->berat_badan }}" required class="form-input text-center text-lg bg-white">
                    </div>
                    <div class="col-span-1">
                        <label class="form-label">Tinggi B. (cm) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="tinggi_badan" value="{{ $pemeriksaan->tinggi_badan }}" required class="form-input text-center text-lg bg-white">
                    </div>

                    @if(in_array($pemeriksaan->kategori_pasien, ['balita', 'remaja', 'ibu_hamil']))
                    <div class="col-span-1">
                        <label class="form-label text-indigo-500">L. Lengan (cm)</label>
                        <input type="number" step="0.1" name="lingkar_lengan" value="{{ $pemeriksaan->lingkar_lengan }}" class="form-input text-center">
                    </div>
                    @endif

                    @if($pemeriksaan->kategori_pasien == 'balita')
                    <div class="col-span-1"><label class="form-label text-rose-500">L. Kepala (cm)</label><input type="number" step="0.1" name="lingkar_kepala" value="{{ $pemeriksaan->lingkar_kepala }}" class="form-input text-center"></div>
                    @endif

                    @if(in_array($pemeriksaan->kategori_pasien, ['remaja', 'lansia', 'ibu_hamil']))
                    <div class="col-span-2">
                        <label class="form-label text-rose-500">Tensi Darah</label>
                        <input type="text" name="tekanan_darah" value="{{ $pemeriksaan->tekanan_darah }}" placeholder="120/80" class="form-input text-center bg-rose-50/30 border-rose-200 focus:border-rose-500">
                    </div>
                    @endif
                </div>

                <div class="mt-6 border-t border-slate-100 pt-6 text-left">
                    <label class="form-label flex items-center gap-2"><i class="fas fa-comment-medical text-amber-500"></i> Keluhan Pasien</label>
                    <textarea name="keluhan" rows="2" class="form-input resize-none bg-white">{{ $pemeriksaan->keluhan }}</textarea>
                </div>
            </div>
            
            <div class="p-6 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('kader.pemeriksaan.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white border border-slate-200 text-slate-600 font-black text-[13px] rounded-xl hover:bg-slate-100 transition-colors text-center uppercase tracking-widest shadow-sm">Batal</a>
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-3.5 bg-amber-500 text-white font-black text-[13px] rounded-xl hover:bg-amber-600 shadow-md transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                    <i class="fas fa-save"></i> Update Koreksi
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('formPemeriksaan').addEventListener('submit', async function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Update Data...', html: 'Mengirim pembaruan ke sistem.',
            allowOutsideClick: false, showConfirmButton: false,
            willOpen: () => { Swal.showLoading(); }
        });

        try {
            const response = await fetch(this.action, {
                method: 'POST', body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const result = await response.json();
            if (response.ok && result.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Tersimpan!', text: result.message, timer: 1500, showConfirmButton: false })
                .then(() => { window.location.href = result.redirect; });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: result.message });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error Koneksi', text: 'Gagal menghubungi server.' });
        }
    });
</script>
@endpush
@endsection