@extends('layouts.kader')
@section('title', 'Input Pemeriksaan')
@section('page-name', 'Pengukuran Fisik')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .form-label { display: block; font-size: 0.70rem; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; text-align: left;}
    .form-input { width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a; font-size: 0.875rem; border-radius: 1rem; padding: 1rem 1.25rem; outline: none; transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02); text-align: left; }
    .form-input:focus { background-color: #ffffff; border-color: #4f46e5; box-shadow: 0 4px 20px -3px rgba(79, 70, 229, 0.15); transform: translateY(-2px); }
    .form-input:disabled { background-color: #f1f5f9; color: #94a3b8; cursor: not-allowed; border-style: dashed; }
    .dynamic-field { display: none; opacity: 0; transition: opacity 0.4s ease-in-out; }
    .dynamic-field.show { display: block; opacity: 1; }
</style>
@endpush

@section('content')
<div class="max-w-[1000px] mx-auto animate-slide-up relative pb-12 z-10">

    <div class="mb-6 flex items-center justify-center sm:justify-start gap-3">
        <a href="{{ route('kader.pemeriksaan.index') }}" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-50 hover:text-indigo-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <div class="bg-gradient-to-br from-indigo-500 via-violet-600 to-indigo-800 rounded-[32px] p-8 md:p-12 mb-8 shadow-[0_15px_40px_-10px_rgba(79,70,229,0.4)] flex flex-col md:flex-row items-center justify-center sm:justify-start gap-8 text-center sm:text-left">
        <div class="w-24 h-24 rounded-[24px] bg-white/20 backdrop-blur-md border border-white/30 text-white flex items-center justify-center text-4xl shrink-0 shadow-lg transform -rotate-6"><i class="fas fa-notes-medical"></i></div>
        <div>
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white text-[10px] font-black px-3 py-1.5 rounded-full mb-3 uppercase tracking-widest mx-auto sm:mx-0">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Form Cerdas Auto-Fill
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">Input Pemeriksaan</h1>
            <p class="text-indigo-100 font-medium text-[14px] max-w-xl mx-auto sm:mx-0">Pilih kategori dan nama pasien. Parameter akan menyesuaikan otomatis.</p>
        </div>
    </div>

    <form action="{{ route('kader.pemeriksaan.store') }}" method="POST" id="formPemeriksaan">
        @csrf
        
        <div class="bg-white/95 backdrop-blur-2xl border border-white/80 rounded-[32px] shadow-sm overflow-hidden flex flex-col mb-8 text-center sm:text-left">
            
            <div class="p-8 md:p-10 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-4 mb-8">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-lg border border-indigo-200">1</div>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Identitas Target</h3>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-6 text-left">
                    <div>
                        <label class="form-label">Kategori Pasien <span class="text-rose-500">*</span></label>
                        <select name="kategori_pasien" id="kategoriSelect" required class="form-input cursor-pointer" onchange="updateFormRealtime()">
                            <option value="">-- Tentukan Kategori --</option>
                            <option value="balita">👶 Anak & Balita</option>
                            <option value="remaja">🎓 Usia Remaja</option>
                            <option value="ibu_hamil">🤰 Ibu Hamil</option>
                            <option value="lansia">👴 Lanjut Usia (Lansia)</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Pengukuran <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_periksa" value="{{ date('Y-m-d') }}" required max="{{ date('Y-m-d') }}" class="form-input cursor-pointer bg-white text-center sm:text-left">
                    </div>
                </div>

                <div class="text-left">
                    <label class="form-label">Cari & Pilih Nama Pasien <span class="text-rose-500">*</span></label>
                    <select name="pasien_id" id="pasienSelect" required class="form-input transition-all" disabled>
                        <option value="">-- Pilih kategori di atas terlebih dahulu --</option>
                    </select>
                </div>
            </div>

            <div class="p-8 md:p-10 bg-white">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8 text-center sm:text-left">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-lg border border-emerald-200">2</div>
                        <h3 class="text-xl font-black text-slate-800 font-poppins">Parameter Pengukuran</h3>
                    </div>
                    <span id="labelKategori" class="px-4 py-2 bg-slate-100 text-slate-500 text-[11px] font-black uppercase rounded-xl border border-slate-200 tracking-widest">Menunggu Pilihan</span>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 text-left">
                    <div class="col-span-1">
                        <label class="form-label">Berat B. (kg) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="berat_badan" required placeholder="0.0" class="form-input text-center text-lg bg-white">
                    </div>
                    <div class="col-span-1">
                        <label class="form-label">Tinggi B. (cm) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="tinggi_badan" required placeholder="0.0" class="form-input text-center text-lg bg-white">
                    </div>
                    
                    <div class="col-span-1 dynamic-field" data-kategori="balita">
                        <label class="form-label text-rose-600">L. Kepala (cm)</label>
                        <input type="number" step="0.1" name="lingkar_kepala" placeholder="0.0" class="form-input text-center border-rose-200 focus:border-rose-500 bg-rose-50/30">
                    </div>

                    <div class="col-span-1 dynamic-field" data-kategori="balita,remaja,ibu_hamil">
                        <label class="form-label text-indigo-600">L. Lengan (cm)</label>
                        <input type="number" step="0.1" name="lingkar_lengan" placeholder="0.0" class="form-input text-center border-indigo-200 focus:border-indigo-500 bg-indigo-50/30">
                        <p class="text-[9px] font-bold text-rose-500 mt-1 hidden" id="kek-warning">*Wajib untuk deteksi KEK Ibu Hamil</p>
                    </div>

                    <div class="col-span-1 dynamic-field" data-kategori="remaja,lansia">
                        <label class="form-label">L. Perut (cm)</label>
                        <input type="number" step="0.1" name="lingkar_perut" placeholder="0.0" class="form-input text-center bg-white">
                    </div>
                    
                    <div class="col-span-1 dynamic-field" data-kategori="remaja,ibu_hamil,lansia">
                        <label class="form-label text-rose-600">Tensi Darah</label>
                        <input type="text" name="tekanan_darah" placeholder="120/80" class="form-input text-center border-rose-200 focus:border-rose-500 bg-rose-50/30">
                    </div>
                    
                    <div class="col-span-2 sm:col-span-4 dynamic-field" data-kategori="lansia">
                        <div class="p-6 rounded-[24px] bg-emerald-50/50 border border-emerald-100">
                            <h4 class="text-[11px] font-black text-emerald-600 uppercase tracking-widest mb-4 text-center sm:text-left"><i class="fas fa-vial mr-1"></i> Cek Lab Lansia</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                                <div><label class="form-label text-emerald-700">Gula Darah</label><input type="text" name="gula_darah" placeholder="mg/dL" class="form-input text-center border-emerald-200 bg-white"></div>
                                <div><label class="form-label text-emerald-700">Asam Urat</label><input type="number" step="0.1" name="asam_urat" placeholder="mg/dL" class="form-input text-center border-emerald-200 bg-white"></div>
                                <div><label class="form-label text-emerald-700">Kolesterol</label><input type="number" name="kolesterol" placeholder="mg/dL" class="form-input text-center border-emerald-200 bg-white"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-100 pt-6 text-left">
                    <label class="form-label flex items-center gap-2"><i class="fas fa-comment-medical text-amber-500"></i> Keluhan Umum Pasien (Opsional)</label>
                    <textarea name="keluhan" rows="2" placeholder="Catat keluhan pasien di sini..." class="form-input resize-none bg-white"></textarea>
                </div>
            </div>
            
            <div class="p-6 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('kader.pemeriksaan.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-white border border-slate-200 text-slate-600 font-black text-[13px] rounded-xl hover:bg-slate-100 transition-colors text-center uppercase tracking-widest shadow-sm">Batalkan</a>
                <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-3.5 bg-indigo-600 text-white font-black text-[13px] rounded-xl hover:bg-indigo-700 shadow-md transition-all flex items-center justify-center gap-2 uppercase tracking-widest">
                    <i class="fas fa-paper-plane"></i> Kirim ke Bidan
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const masterDataPasien = @json($semuaPasien ?? []); 

    function updateFormRealtime() {
        const kategori = document.getElementById('kategoriSelect').value;
        const pasienSelect = document.getElementById('pasienSelect');
        const labelKategori = document.getElementById('labelKategori');
        const dynamicFields = document.querySelectorAll('.dynamic-field');

        if (kategori === 'balita') {
            labelKategori.textContent = 'Parameter Bayi/Balita';
            labelKategori.className = 'px-4 py-2 bg-rose-100 text-rose-600 text-[11px] font-black uppercase rounded-xl border border-rose-200 tracking-widest transition-all';
        } else if (kategori === 'remaja') {
            labelKategori.textContent = 'Parameter Remaja';
            labelKategori.className = 'px-4 py-2 bg-sky-100 text-sky-600 text-[11px] font-black uppercase rounded-xl border border-sky-200 tracking-widest transition-all';
        } else if (kategori === 'ibu_hamil') {
            labelKategori.textContent = 'Parameter Ibu Hamil';
            labelKategori.className = 'px-4 py-2 bg-pink-100 text-pink-600 text-[11px] font-black uppercase rounded-xl border border-pink-200 tracking-widest transition-all';
            document.getElementById('kek-warning').classList.remove('hidden');
        } else if (kategori === 'lansia') {
            labelKategori.textContent = 'Parameter Lansia';
            labelKategori.className = 'px-4 py-2 bg-emerald-100 text-emerald-600 text-[11px] font-black uppercase rounded-xl border border-emerald-200 tracking-widest transition-all';
            document.getElementById('kek-warning').classList.add('hidden');
        } else {
            labelKategori.textContent = 'Pilih Kategori Dulu';
            labelKategori.className = 'px-4 py-2 bg-slate-100 text-slate-500 text-[11px] font-black uppercase rounded-xl border border-slate-200 tracking-widest transition-all';
            document.getElementById('kek-warning').classList.add('hidden');
        }

        pasienSelect.innerHTML = ''; 
        if (kategori === '') {
            pasienSelect.innerHTML = '<option value="">-- Pilih kategori di atas terlebih dahulu --</option>';
            pasienSelect.disabled = true;
            pasienSelect.classList.remove('bg-white');
        } else {
            pasienSelect.disabled = false;
            pasienSelect.classList.add('bg-white');
            let optionsHtml = '<option value="">-- Silakan Pilih Nama Pasien --</option>';
            const pasienDifilter = masterDataPasien.filter(p => p.kategori === kategori);
            if (pasienDifilter.length > 0) {
                pasienDifilter.forEach(p => { optionsHtml += `<option value="${p.id}">${p.nama} (NIK/ID: ${p.nik || '-'})</option>`; });
            } else {
                optionsHtml = '<option value="">(Tidak ada pasien terdaftar di sistem untuk kategori ini)</option>';
            }
            pasienSelect.innerHTML = optionsHtml;
        }

        dynamicFields.forEach(field => {
            const targetCategories = field.getAttribute('data-kategori').split(',');
            if (kategori && targetCategories.includes(kategori)) field.classList.add('show');
            else { field.classList.remove('show'); field.querySelectorAll('input').forEach(input => input.value = ''); }
        });
    }

    document.addEventListener('DOMContentLoaded', () => { updateFormRealtime(); });

    document.getElementById('formPemeriksaan').addEventListener('submit', async function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Menyimpan Data...', html: 'Mengirim ukuran fisik ke sistem Bidan.',
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
                Swal.fire({ icon: 'error', title: 'Gagal', text: result.message || 'Periksa kembali isian Anda.' });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error Koneksi', text: 'Gagal menghubungi server.' });
        }
    });
</script>
@endpush
@endsection