@extends('layouts.kader')
@section('title', 'Edit Pemeriksaan Fisik')
@section('page-name', 'Koreksi Antropometri')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .form-label { display: block; font-size: 0.70rem; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; text-align: left;}
    .form-input { width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a; font-size: 0.875rem; border-radius: 1rem; padding: 1rem 1.25rem; outline: none; transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02); }
    .form-input:focus { background-color: #ffffff; border-color: #f59e0b; box-shadow: 0 4px 20px -3px rgba(245, 158, 11, 0.15); transform: translateY(-2px); }
    .form-input:disabled { background-color: #e2e8f0; color: #94a3b8; cursor: not-allowed; }
    .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.8); }
    
    .input-group { position: relative; display: flex; align-items: center; }
    .input-group input { padding-right: 3.5rem; }
    .input-group .unit { position: absolute; right: 1rem; font-size: 0.75rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; pointer-events: none; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto animate-slide-up relative z-10 pb-10">
    <div class="absolute top-0 right-0 w-96 h-96 bg-amber-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.pemeriksaan.index') }}" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-amber-50 hover:text-amber-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    {{-- WARNING HEADER --}}
    <div class="bg-gradient-to-br from-amber-400 via-orange-500 to-amber-600 rounded-[32px] p-8 md:p-10 mb-8 relative overflow-hidden shadow-[0_15px_40px_-10px_rgba(245,158,11,0.4)] flex flex-col md:flex-row items-center gap-8 z-10">
        <div class="absolute inset-0 opacity-[0.15] pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="w-20 h-20 rounded-[24px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-4xl shrink-0 shadow-lg transform rotate-3 hover:rotate-0 transition-transform">
            <i class="fas fa-pen-nib"></i>
        </div>
        <div class="text-center md:text-left relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/20 border border-white/30 text-white text-[10px] font-black px-4 py-1.5 rounded-full mb-3 uppercase tracking-widest backdrop-blur-sm">
                <i class="fas fa-exclamation-triangle text-amber-200"></i> Mode Koreksi Data
            </div>
            <h1 class="text-3xl font-black text-white tracking-tight font-poppins mb-2">Edit Pengukuran: {{ $pemeriksaan->nama_pasien }}</h1>
            <p class="text-amber-50 font-medium text-[14px]">Kategori: <b>{{ strtoupper(str_replace('_', ' ', $pemeriksaan->kategori_pasien)) }}</b>. Kategori dan Nama Pasien terkunci.</p>
        </div>
    </div>

    <form action="{{ route('kader.pemeriksaan.update', $pemeriksaan->id) }}" method="POST" id="formPemeriksaan" class="relative z-10">
        @csrf @method('PUT')
        
        <div class="glass-panel rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 md:p-10 relative overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-5 border-r border-slate-100 pr-0 md:pr-8">
                    <div>
                        <label class="form-label text-slate-400">ID Pasien Terkunci</label>
                        <input type="text" value="{{ $pemeriksaan->nama_pasien }}" disabled class="form-input">
                        <input type="hidden" name="kategori_pasien" value="{{ $pemeriksaan->kategori_pasien }}">
                        <input type="hidden" name="pasien_id" value="{{ $pemeriksaan->pasien_id }}">
                    </div>
                    <div>
                        <label class="form-label">Tanggal Pengukuran <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_periksa" value="{{ $pemeriksaan->tanggal_periksa->format('Y-m-d') }}" required class="form-input focus:ring-4 focus:ring-amber-50 cursor-pointer">
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label text-amber-600">Berat Badan <span class="text-rose-500">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="berat_badan" id="berat_badan" value="{{ $pemeriksaan->berat_badan }}" required class="form-input focus:border-amber-400">
                                <span class="unit">kg</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-amber-600">Tinggi / Panjang <span class="text-rose-500">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" value="{{ $pemeriksaan->tinggi_badan }}" required class="form-input focus:border-amber-400">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    @if($pemeriksaan->kategori_pasien != 'balita')
                    <div id="imt-widget" class="flex items-center justify-between bg-slate-800 text-white p-4 rounded-2xl shadow-sm mt-4">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Indeks Massa Tubuh</p>
                            <p class="text-2xl font-black font-poppins" id="imt-val">{{ $pemeriksaan->imt ?? '0.0' }}</p>
                        </div>
                        <div id="imt-kat" class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-slate-600">-</div>
                    </div>
                    @endif
                </div>
            </div>

            <hr class="border-slate-100 my-8">

            {{-- DYNAMIC RENDER BASED ON CATEGORY --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @if($pemeriksaan->kategori_pasien == 'balita')
                    <div>
                        <label class="form-label">Lingkar Kepala</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="lingkar_kepala" value="{{ $pemeriksaan->lingkar_kepala }}" class="form-input focus:ring-4 focus:ring-amber-50">
                            <span class="unit">cm</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Suhu Tubuh</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="suhu_tubuh" value="{{ $pemeriksaan->suhu_tubuh }}" class="form-input focus:ring-4 focus:ring-amber-50">
                            <span class="unit">°C</span>
                        </div>
                    </div>
                @endif

                @if($pemeriksaan->kategori_pasien == 'ibu_hamil')
                    <div class="bg-pink-50 p-4 rounded-2xl border border-pink-100">
                        <label class="form-label text-pink-600">Lingkar Lengan (LILA)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="lingkar_lengan" id="lila_bumil" value="{{ $pemeriksaan->lingkar_lengan }}" class="form-input bg-white focus:border-pink-400">
                            <span class="unit">cm</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-rose-600">Tensi Darah</label>
                        <input type="text" name="tekanan_darah" value="{{ $pemeriksaan->tekanan_darah }}" placeholder="120/80" class="form-input font-mono text-center focus:ring-4 focus:ring-amber-50">
                    </div>
                    <div>
                        <label class="form-label text-rose-500">Hemoglobin (HB)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="hemoglobin" value="{{ $pemeriksaan->hemoglobin }}" class="form-input focus:ring-4 focus:ring-amber-50">
                            <span class="unit">g/dL</span>
                        </div>
                    </div>
                @endif

                @if(in_array($pemeriksaan->kategori_pasien, ['remaja', 'lansia']))
                    <div>
                        <label class="form-label text-rose-600">Tensi (Darah)</label>
                        <input type="text" name="tekanan_darah" value="{{ $pemeriksaan->tekanan_darah }}" placeholder="120/80" class="form-input focus:ring-4 focus:ring-amber-50 font-mono text-center">
                    </div>
                    
                    <div>
                        <label class="form-label text-sky-600">Lingkar Perut (LP)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="lingkar_perut" value="{{ $pemeriksaan->lingkar_perut }}" class="form-input focus:ring-4 focus:ring-amber-50">
                            <span class="unit">cm</span>
                        </div>
                    </div>

                    @if($pemeriksaan->kategori_pasien == 'remaja')
                        <div>
                            <label class="form-label text-pink-600">Lingkar Lengan (LILA)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="lingkar_lengan" value="{{ $pemeriksaan->lingkar_lengan }}" class="form-input focus:ring-4 focus:ring-amber-50">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-rose-600">Hemoglobin (HB)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="hemoglobin" value="{{ $pemeriksaan->hemoglobin }}" class="form-input focus:ring-4 focus:ring-amber-50">
                                <span class="unit">g/dL</span>
                            </div>
                        </div>
                    @endif
                    
                    <div>
                        <label class="form-label text-sky-500">Gula Darah</label>
                        <div class="input-group">
                            <input type="number" step="1" name="gula_darah" value="{{ $pemeriksaan->gula_darah }}" class="form-input focus:ring-4 focus:ring-amber-50">
                            <span class="unit">mg/dL</span>
                        </div>
                    </div>

                    @if($pemeriksaan->kategori_pasien == 'lansia')
                    <div>
                        <label class="form-label text-amber-600">Asam Urat</label>
                        <div class="input-group">
                            <input type="number" step="0.1" name="asam_urat" value="{{ $pemeriksaan->asam_urat }}" class="form-input focus:ring-4 focus:ring-amber-50">
                            <span class="unit">mg/dL</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-purple-600">Kolesterol</label>
                        <div class="input-group">
                            <input type="number" step="1" name="kolesterol" value="{{ $pemeriksaan->kolesterol }}" class="form-input focus:ring-4 focus:ring-amber-50">
                            <span class="unit">mg/dL</span>
                        </div>
                    </div>
                    @endif
                @endif
                
            </div>
        </div>
        
        <div class="p-8 mt-8 border-t border-slate-100 bg-white/80 backdrop-blur-xl rounded-[32px] shadow-lg flex items-center justify-end gap-4">
            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-black text-[13px] rounded-xl hover:from-amber-600 hover:to-orange-600 shadow-[0_8px_20px_rgba(245,158,11,0.3)] transition-all">
                <i class="fas fa-save text-lg mr-2"></i> Update Koreksi Medis
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const bbInput = document.getElementById('berat_badan');
    const tbInput = document.getElementById('tinggi_badan');
    if(bbInput && tbInput && document.getElementById('imt-widget')) {
        const hitungIMT = () => {
            const bb = parseFloat(bbInput.value);
            const tb = parseFloat(tbInput.value);
            if (!bb || !tb || tb < 50) return;
            const imt = (bb / Math.pow(tb/100, 2)).toFixed(2);
            let label = imt < 18.5 ? 'Kurus' : (imt < 25 ? 'Normal' : (imt < 27 ? 'Gemuk' : 'Obesitas'));
            let color = imt < 18.5 ? 'bg-amber-500 text-white' : (imt < 25 ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white');
            document.getElementById('imt-val').textContent = imt;
            const imtKatEl = document.getElementById('imt-kat');
            imtKatEl.textContent = label;
            imtKatEl.className = `px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider ${color}`;
        };
        bbInput.addEventListener('input', hitungIMT); tbInput.addEventListener('input', hitungIMT); hitungIMT();
    }

    document.getElementById('formPemeriksaan').addEventListener('submit', async function(e) {
        e.preventDefault();
        Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, showConfirmButton: false, willOpen: () => Swal.showLoading() });
        try {
            const response = await fetch(this.action, { method: 'POST', body: new FormData(this), headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }});
            const result = await response.json();
            if (response.ok && result.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Tersimpan!', text: result.message, timer: 1500, showConfirmButton: false })
                .then(() => window.location.href = result.redirect);
            } else Swal.fire({ icon: 'error', title: 'Gagal', text: result.message });
        } catch (error) { Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi terputus.' }); }
    });
</script>
@endpush
@endsection