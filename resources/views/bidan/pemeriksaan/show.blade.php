@extends('layouts.bidan')

@section('title', 'Validasi Pemeriksaan')
@section('page-name', 'Detail & Validasi')

@push('styles')
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Custom Radio Button for Medical Decision */
    .radio-card input:checked + div { border-color: #06b6d4; background-color: #ecfeff; box-shadow: 0 4px 12px rgba(6,182,212,0.15); }
    .radio-card input:checked + div .icon-check { color: #06b6d4; transform: scale(1.1); }
    
    .radio-card-reject input:checked + div { border-color: #f43f5e; background-color: #fff1f2; box-shadow: 0 4px 12px rgba(244,63,94,0.15); }
    .radio-card-reject input:checked + div .icon-times { color: #f43f5e; transform: scale(1.1); }
</style>
@endpush

@section('content')
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-100">
    <div class="relative w-20 h-20 flex items-center justify-center mb-4">
        <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-cyan-500 rounded-full border-t-transparent animate-spin"></div>
        <i class="fas fa-file-medical text-cyan-500 text-2xl animate-pulse"></i>
    </div>
    <p class="text-cyan-800 font-black font-poppins tracking-widest text-[11px] animate-pulse uppercase" id="loaderText">MEMUAT REKAM MEDIS...</p>
</div>

<div class="max-w-7xl mx-auto animate-slide-up">

    <div class="mb-6">
        <a href="{{ route('bidan.pemeriksaan.index') }}" class="smooth-route inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold text-[13px] rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Antrian
        </a>
    </div>

    @php
        $sv = $pemeriksaan->status_verifikasi ?? 'pending';
        $svConfig = [
            'verified' => ['emerald', 'check-circle',  'Pemeriksaan Selesai Diverifikasi'],
            'rejected' => ['rose',  'times-circle',  'Data Pemeriksaan Ditolak'],
            'pending'  => ['amber', 'hourglass-half', 'Menunggu Diagnosa Bidan'],
        ];
        [$svColor, $svIcon, $svLabel] = $svConfig[$sv] ?? $svConfig['pending'];
    @endphp

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: DATA FISIK DARI KADER --}}
        <div class="xl:col-span-2 space-y-6">
            
            <div class="bg-{{ $svColor }}-50 border border-{{ $svColor }}-200 p-6 rounded-[24px] flex items-center justify-between shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 w-32 h-32 bg-{{ $svColor }}-500/10 rounded-bl-[100px] pointer-events-none"></div>
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-14 h-14 rounded-[18px] bg-white text-{{ $svColor }}-500 flex items-center justify-center text-3xl shrink-0 shadow-sm">
                        <i class="fas fa-{{ $svIcon }}"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-{{ $svColor }}-800 font-poppins tracking-tight">{{ $svLabel }}</h3>
                        @if($pemeriksaan->verified_at)
                            <p class="text-[13px] font-bold text-{{ $svColor }}-600 mt-1">Divalidasi oleh <span class="uppercase tracking-wider border-b border-{{ $svColor }}-300">{{ $pemeriksaan->verifikator?->name ?? 'Sistem' }}</span> pada {{ \Carbon\Carbon::parse($pemeriksaan->verified_at)->format('d M Y, H:i') }}</p>
                        @else
                            <p class="text-[13px] font-bold text-{{ $svColor }}-600 mt-1">Silakan analisis data ukur dari Kader di bawah dan berikan keputusan medis.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_4px_20px_rgba(0,0,0,0.02)] overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center"><i class="fas fa-id-card"></i></div>
                    <h3 class="font-black text-slate-800 text-[15px]">Informasi Pasien</h3>
                </div>
                <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-6">
                    {{-- BUG FIXED: ibuHamil camelCase --}}
                    <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Lengkap</p><p class="font-bold text-slate-800 text-sm">{{ $pemeriksaan->nama_pasien ?? ($pemeriksaan->balita->nama_lengkap ?? ($pemeriksaan->remaja->nama_lengkap ?? ($pemeriksaan->lansia->nama_lengkap ?? ($pemeriksaan->ibuHamil->nama_lengkap ?? '-')))) }}</p></div>
                    <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kategori</p><span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-md uppercase border border-slate-200 tracking-wider">{{ $pemeriksaan->kategori_pasien }}</span></div>
                    <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Periksa</p><p class="font-bold text-slate-800 text-sm">{{ $pemeriksaan->tanggal_periksa?->format('d M Y') ?? '-' }}</p></div>
                    <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Petugas (Kader)</p><p class="font-bold text-slate-800 text-sm">{{ $pemeriksaan->pemeriksa?->name ?? 'Sistem' }}</p></div>
                </div>
            </div>

            <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-[0_4px_20px_rgba(0,0,0,0.02)] overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center"><i class="fas fa-weight"></i></div>
                    <h3 class="font-black text-slate-800 text-[15px]">Hasil Pengukuran Fisik</h3>
                </div>
                <div class="p-6 text-slate-800">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @php
                            $fields = [
                                'berat_badan'   => ['Berat Badan', 'kg', 'fa-weight'],
                                'tinggi_badan'  => ['Tinggi Badan', 'cm', 'fa-ruler-vertical'],
                                'suhu_tubuh'    => ['Suhu Tubuh', '°C', 'fa-thermometer-half'],
                                'tekanan_darah' => ['Tekanan Darah', 'mmHg', 'fa-heartbeat'],
                                'hemoglobin'    => ['Hemoglobin', 'g/dL', 'fa-tint'],
                                'gula_darah'    => ['Gula Darah', 'mg/dL', 'fa-cubes'],
                                'kolesterol'    => ['Kolesterol', 'mg/dL', 'fa-bacon'],
                                'asam_urat'     => ['Asam Urat', 'mg/dL', 'fa-bone'],
                                'lingkar_kepala'=> ['L. Kepala', 'cm', 'fa-child'],
                                'lingkar_lengan'=> ['L. Lengan', 'cm', 'fa-child'],
                            ];
                        @endphp
                        @foreach($fields as $col => [$label, $satuan, $icon])
                            @if(!empty($pemeriksaan->$col))
                            <div class="bg-white border border-slate-200 p-4 rounded-2xl flex items-center gap-4 shadow-sm hover:border-cyan-300 hover:shadow-md transition-all">
                                <div class="w-11 h-11 rounded-full bg-slate-50 text-slate-400 border border-slate-100 flex items-center justify-center shrink-0 text-lg"><i class="fas {{ $icon }}"></i></div>
                                <div><p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">{{ $label }}</p><p class="font-black text-slate-800 text-xl">{{ $pemeriksaan->$col }} <span class="text-xs font-bold text-slate-500">{{ $satuan }}</span></p></div>
                            </div>
                            @endif
                        @endforeach
                    </div>

                    @if($pemeriksaan->keluhan)
                    <div class="mt-6 p-5 bg-rose-50 border border-rose-100 rounded-2xl relative overflow-hidden">
                        <div class="absolute right-[-10px] top-[-10px] text-rose-500/10 text-6xl"><i class="fas fa-comment-medical"></i></div>
                        <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-1 relative z-10"><i class="fas fa-exclamation-circle mr-1"></i> Keluhan Pasien (Catatan Kader)</p>
                        <p class="font-bold text-rose-800 text-[14px] leading-relaxed relative z-10">"{{ $pemeriksaan->keluhan }}"</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: FORMULIR VALIDASI BIDAN --}}
        <div class="xl:col-span-1">
            <div class="bg-white rounded-[24px] border-2 border-cyan-500 shadow-[0_8px_30px_rgba(6,182,212,0.15)] overflow-hidden sticky top-28">
                <div class="px-6 py-5 border-b border-cyan-500 bg-cyan-50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-cyan-500 text-white flex items-center justify-center shadow-sm"><i class="fas fa-stethoscope"></i></div>
                    <h3 class="font-black text-cyan-900 text-[15px] font-poppins">Formulir Medis</h3>
                </div>
                
                <div class="p-6">
                    @if($sv === 'pending')
                        <form id="formValidasi" action="{{ route('bidan.pemeriksaan.verifikasi', $pemeriksaan->id) }}" method="POST" class="space-y-6">
                            @csrf @method('PUT')
                            
                            {{-- INJEKSI FORM KHUSUS IBU HAMIL --}}
                            @if(in_array(strtolower($pemeriksaan->kategori_pasien), ['ibu_hamil', 'bumil']))
                                <div class="p-4 bg-pink-50 rounded-xl border border-pink-100">
                                    <p class="text-[10px] font-black text-pink-600 uppercase tracking-widest mb-3"><i class="fas fa-female mr-1"></i> Data Kandungan</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 mb-1">TFU (cm)</label>
                                            <input type="text" name="tfu" value="{{ $pemeriksaan->tfu }}" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs outline-none focus:border-pink-400">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 mb-1">DJJ (x/m)</label>
                                            <input type="text" name="djj" value="{{ $pemeriksaan->djj }}" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs outline-none focus:border-pink-400">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-[10px] font-bold text-slate-500 mb-1">Posisi Janin</label>
                                            <input type="text" name="posisi_janin" value="{{ $pemeriksaan->posisi_janin }}" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs outline-none focus:border-pink-400" placeholder="Kepala / Sungsang">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Diagnosa Bidan <span class="text-rose-500">*</span></label>
                                <textarea name="diagnosa" rows="3" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-[13px] rounded-xl px-4 py-3 outline-none font-medium focus:border-cyan-500 focus:bg-white focus:ring-4 focus:ring-cyan-500/10 transition-all placeholder:text-slate-400" placeholder="Ketik hasil analisa klinis Anda di sini..."></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Tindakan / Resep Obat</label>
                                <textarea name="tindakan" rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-[13px] rounded-xl px-4 py-3 outline-none font-medium focus:border-cyan-500 focus:bg-white focus:ring-4 focus:ring-cyan-500/10 transition-all placeholder:text-slate-400" placeholder="Contoh: Berikan Paracetamol, Rujuk ke RS..."></textarea>
                            </div>

                            <div class="pt-2 border-t border-slate-100">
                                <label class="block text-[11px] font-black text-slate-800 uppercase tracking-widest mb-3">Keputusan Akhir <span class="text-rose-500">*</span></label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="radio-card cursor-pointer">
                                        <input type="radio" name="status_verifikasi" value="verified" class="hidden" required checked>
                                        <div class="border-2 border-slate-200 bg-white rounded-xl p-4 text-center transition-all flex flex-col items-center justify-center gap-2 h-full">
                                            <i class="fas fa-check-circle text-slate-300 text-2xl icon-check transition-transform"></i>
                                            <p class="text-[12px] font-bold text-slate-600 leading-tight">Terima &<br>Verifikasi</p>
                                        </div>
                                    </label>
                                    <label class="radio-card-reject cursor-pointer">
                                        <input type="radio" name="status_verifikasi" value="rejected" class="hidden" required>
                                        <div class="border-2 border-slate-200 bg-white rounded-xl p-4 text-center transition-all flex flex-col items-center justify-center gap-2 h-full">
                                            <i class="fas fa-times-circle text-slate-300 text-2xl icon-times transition-transform"></i>
                                            <p class="text-[12px] font-bold text-slate-600 leading-tight">Tolak Data<br>(Salah Input)</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2"><i class="fas fa-info-circle"></i> Catatan Penolakan (Jika Ditolak)</label>
                                <input type="text" name="catatan_bidan" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-[13px] rounded-xl px-4 py-3 outline-none focus:border-rose-500 focus:bg-rose-50 transition-colors" placeholder="Alasan mengapa data ini ditolak...">
                            </div>

                            <button type="submit" id="btnValidasi" class="w-full py-4 bg-cyan-600 text-white font-black text-[13px] rounded-xl hover:bg-cyan-700 shadow-[0_8px_20px_rgba(6,182,212,0.3)] hover:-translate-y-0.5 transition-all duration-300 uppercase tracking-wide flex items-center justify-center gap-2 mt-4">
                                <i class="fas fa-stethoscope"></i> Simpan Diagnosa
                            </button>
                        </form>
                    @else
                        <div class="space-y-6">
                            
                            @if(in_array(strtolower($pemeriksaan->kategori_pasien), ['ibu_hamil', 'bumil']) && $pemeriksaan->tfu)
                                <div class="p-4 bg-pink-50 border border-pink-100 rounded-2xl">
                                    <p class="text-[10px] font-black text-pink-600 uppercase tracking-widest mb-2"><i class="fas fa-female mr-1"></i> Kandungan (Bidan)</p>
                                    <div class="flex gap-4 text-pink-800 text-[12px] font-bold">
                                        <span>TFU: {{ $pemeriksaan->tfu }} cm</span>
                                        <span>DJJ: {{ $pemeriksaan->djj }}</span>
                                        <span>Pos: {{ $pemeriksaan->posisi_janin }}</span>
                                    </div>
                                </div>
                            @endif

                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5"><i class="fas fa-file-medical-alt mr-1"></i> Diagnosa Bidan</p>
                                <div class="p-4 bg-cyan-50 border border-cyan-100 rounded-2xl text-cyan-900 font-bold text-[14px] leading-relaxed shadow-sm">{{ $pemeriksaan->diagnosa ?? '-' }}</div>
                            </div>
                            
                            @if($pemeriksaan->tindakan) 
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5"><i class="fas fa-pills mr-1"></i> Tindakan / Resep</p>
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 font-semibold text-[13px] shadow-sm">{{ $pemeriksaan->tindakan }}</div>
                            </div> 
                            @endif
                            
                            @if($pemeriksaan->catatan_bidan) 
                            <div>
                                <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1.5"><i class="fas fa-exclamation-triangle mr-1"></i> Catatan Penolakan</p>
                                <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl text-rose-800 font-bold text-[13px] shadow-sm">{{ $pemeriksaan->catatan_bidan }}</div>
                            </div> 
                            @endif
                            
                            <form id="formReset" action="{{ route('bidan.pemeriksaan.verifikasi', $pemeriksaan->id) }}" method="POST" class="mt-6 pt-6 border-t border-slate-200 border-dashed">
                                @csrf @method('PUT')
                                <input type="hidden" name="status_verifikasi" value="pending"><input type="hidden" name="diagnosa" value="">
                                <button type="submit" id="btnReset" class="w-full py-3 bg-white border-2 border-slate-200 text-slate-500 font-bold text-[13px] rounded-xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-all flex items-center justify-center gap-2">
                                    <i class="fas fa-undo"></i> Ralat & Batalkan Validasi
                                </button>
                                <p class="text-[10px] font-medium text-slate-400 text-center mt-3 leading-tight">Tekan tombol ini jika Anda ingin mengubah diagnosa atau merasa salah memberikan keputusan ACC/Tolak.</p>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const showLoader = (text = 'MEMUAT SISTEM...') => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            document.getElementById('loaderText').innerText = text;
            loader.style.display = 'flex';
            loader.offsetHeight; 
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100');
        }
    };

    window.addEventListener('pageshow', () => {
        const loader = document.getElementById('smoothLoader');
        if(loader) {
            loader.classList.remove('opacity-100');
            loader.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => loader.style.display = 'none', 300);
        }
        
        const btnV = document.getElementById('btnValidasi');
        if(btnV) { btnV.innerHTML = '<i class="fas fa-stethoscope"></i> Simpan Diagnosa'; btnV.classList.remove('opacity-75', 'cursor-wait'); }
        
        const btnR = document.getElementById('btnReset');
        if(btnR) { btnR.innerHTML = '<i class="fas fa-undo"></i> Ralat & Batalkan Validasi'; btnR.classList.remove('opacity-75', 'cursor-wait'); }
    });

    document.querySelectorAll('.smooth-route').forEach(link => {
        link.addEventListener('click', function(e) {
            if(this.target !== '_blank' && !e.ctrlKey) showLoader('KEMBALI KE ANTRIAN...');
        });
    });

    const formV = document.getElementById('formValidasi');
    if(formV) {
        formV.addEventListener('submit', function() {
            const btn = document.getElementById('btnValidasi');
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Menyimpan...';
            btn.classList.add('opacity-75', 'cursor-wait');
            showLoader('MENYIMPAN DIAGNOSA MEDIS...');
        });
    }

    const formR = document.getElementById('formReset');
    if(formR) {
        formR.addEventListener('submit', function() {
            const btn = document.getElementById('btnReset');
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Mereset...';
            btn.classList.add('opacity-75', 'cursor-wait');
            showLoader('MEMBATALKAN VALIDASI...');
        });
    }
</script>
@endpush