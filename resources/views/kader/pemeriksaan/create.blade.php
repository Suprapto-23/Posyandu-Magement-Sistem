@extends('layouts.kader')
@section('title', 'Input Pemeriksaan Fisik')
@section('page-name', 'Pengukuran Antropometri')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .form-label { display: block; font-size: 0.70rem; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; text-align: left;}
    .form-input { width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a; font-size: 0.875rem; border-radius: 1rem; padding: 1rem 1.25rem; outline: none; transition: all 0.3s ease; font-weight: 700; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02); }
    .form-input:focus { background-color: #ffffff; border-color: #4f46e5; box-shadow: 0 4px 20px -3px rgba(79, 70, 229, 0.15); transform: translateY(-2px); }
    
    .input-group { position: relative; display: flex; align-items: center; }
    .input-group input { padding-right: 3.5rem; }
    .input-group .unit { position: absolute; right: 1rem; font-size: 0.75rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; pointer-events: none; }
    
    .dynamic-field { display: none; opacity: 0; transition: opacity 0.5s; }
    .dynamic-field.show { display: block; opacity: 1; animation: slideUpFade 0.4s ease forwards; }

    .kat-radio:checked + label { background-color: #4f46e5; color: white; border-color: #4f46e5; box-shadow: 0 4px 15px -3px rgba(79,70,229,0.4); transform: scale(1.02); }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto animate-slide-up relative z-10 pb-10">
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="mb-6 flex items-center gap-3 relative z-10">
        <a href="{{ route('kader.pemeriksaan.index') }}" class="w-12 h-12 rounded-[16px] bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <div class="text-center mb-10 relative z-10">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-[24px] bg-gradient-to-br from-indigo-100 to-violet-100 text-indigo-600 mb-5 shadow-sm border border-indigo-200">
            <i class="fas fa-balance-scale text-4xl"></i>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight font-poppins">Pemeriksaan Fisik (Kader)</h1>
        <p class="text-slate-500 mt-2 font-medium text-[13px] max-w-lg mx-auto">Isi data ukuran fisik peserta dengan akurat. Setelah disimpan, data akan diteruskan ke Meja 5 (Bidan) untuk validasi medis akhir.</p>
    </div>

    <form action="{{ route('kader.pemeriksaan.store') }}" method="POST" id="formPemeriksaan" class="relative z-10">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
            
            {{-- KOLOM 1: PEMILIHAN PASIEN --}}
            <div class="lg:col-span-5 flex flex-col gap-6">
                <div class="bg-white/95 backdrop-blur-2xl border border-white/80 rounded-[32px] shadow-lg p-8 relative overflow-hidden h-full">
                    <div class="flex items-center gap-4 mb-6 border-b border-slate-100 pb-5">
                        <span class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-black shadow-md">1</span>
                        <h3 class="text-xl font-black text-slate-800 font-poppins">Tentukan Peserta</h3>
                    </div>
                    
                    <div>
                        <label class="form-label mb-3">Kategori Warga</label>
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            @php $reqKat = request('kategori', 'balita'); @endphp
                            
                            <input type="radio" name="kategori_pasien" id="kat-balita" value="balita" class="kat-radio hidden" {{ $reqKat == 'balita' ? 'checked' : '' }}>
                            <label for="kat-balita" class="border-2 border-slate-200 text-slate-500 font-black text-xs rounded-xl p-3 text-center cursor-pointer transition-all"><i class="fas fa-baby block text-xl mb-1 text-sky-500"></i> Balita</label>

                            <input type="radio" name="kategori_pasien" id="kat-bumil" value="ibu_hamil" class="kat-radio hidden" {{ $reqKat == 'ibu_hamil' ? 'checked' : '' }}>
                            <label for="kat-bumil" class="border-2 border-slate-200 text-slate-500 font-black text-xs rounded-xl p-3 text-center cursor-pointer transition-all"><i class="fas fa-female block text-xl mb-1 text-pink-500"></i> Ibu Hamil</label>

                            <input type="radio" name="kategori_pasien" id="kat-remaja" value="remaja" class="kat-radio hidden" {{ $reqKat == 'remaja' ? 'checked' : '' }}>
                            <label for="kat-remaja" class="border-2 border-slate-200 text-slate-500 font-black text-xs rounded-xl p-3 text-center cursor-pointer transition-all"><i class="fas fa-user-graduate block text-xl mb-1 text-indigo-500"></i> Remaja</label>

                            <input type="radio" name="kategori_pasien" id="kat-lansia" value="lansia" class="kat-radio hidden" {{ $reqKat == 'lansia' ? 'checked' : '' }}>
                            <label for="kat-lansia" class="border-2 border-slate-200 text-slate-500 font-black text-xs rounded-xl p-3 text-center cursor-pointer transition-all"><i class="fas fa-user-clock block text-xl mb-1 text-emerald-500"></i> Lansia</label>
                        </div>
                    </div>

                    <div class="relative">
                        <label class="form-label">Pilih Nama Pasien <span class="text-rose-500">*</span></label>
                        <select name="pasien_id" id="pasien_id" required class="form-input focus:ring-4 focus:ring-indigo-50 cursor-pointer bg-slate-50">
                            <option value="">-- Loading Data... --</option>
                        </select>
                        <input type="hidden" id="old_pasien_id" value="{{ request('pasien_id') }}">
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-5">
                        <label class="form-label">Tanggal Diukur <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_periksa" value="{{ date('Y-m-d') }}" required class="form-input focus:ring-4 focus:ring-indigo-50 cursor-pointer">
                    </div>
                </div>
            </div>

            {{-- KOLOM 2: HASIL PENGUKURAN --}}
            <div class="lg:col-span-7 bg-white rounded-[32px] border border-slate-200 shadow-lg p-8 md:p-10 relative overflow-hidden flex flex-col">
                <div class="flex items-center gap-4 mb-8 border-b border-slate-200 pb-5 relative z-10">
                    <span class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-black shadow-md">2</span>
                    <h3 class="text-xl font-black text-slate-800 font-poppins">Pengukuran Fisik & Lab Cepat</h3>
                </div>

                <div class="space-y-6 relative z-10">
                    
                    {{-- FIELD UMUM (BB & TB) --}}
                    <div class="grid grid-cols-2 gap-5 bg-emerald-50/50 p-5 rounded-2xl border border-emerald-100">
                        <div>
                            <label class="form-label text-emerald-700">Berat Badan <span class="text-rose-500">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="berat_badan" id="berat_badan" required placeholder="0.0" class="form-input bg-white focus:border-emerald-400">
                                <span class="unit">kg</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-emerald-700">Tinggi/Panjang Badan <span class="text-rose-500">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan" required placeholder="0.0" class="form-input bg-white focus:border-emerald-400">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    {{-- Widget IMT Auto Calculate (Untuk Dewasa) --}}
                    <div id="imt-widget" class="hidden animate-slide-up">
                        <div class="flex items-center justify-between bg-slate-800 text-white p-4 rounded-2xl shadow-sm">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Indeks Massa Tubuh</p>
                                <p class="text-2xl font-black font-poppins" id="imt-val">0.0</p>
                            </div>
                            <div id="imt-kat" class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-slate-600">-</div>
                        </div>
                    </div>

                    <hr class="border-slate-100 my-4">

                    {{-- DYNAMIC FIELD: BALITA --}}
                    <div class="dynamic-field field-balita">
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="form-label text-sky-600"><i class="fas fa-circle-notch mr-1"></i> Lingkar Kepala</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="lingkar_kepala" placeholder="0.0" class="form-input focus:ring-4 focus:ring-sky-50">
                                    <span class="unit">cm</span>
                                </div>
                            </div>
                            <div>
                                <label class="form-label text-rose-500"><i class="fas fa-thermometer-half mr-1"></i> Suhu Tubuh</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="suhu_tubuh" placeholder="36.5" max="45" class="form-input focus:ring-4 focus:ring-rose-50">
                                    <span class="unit">°C</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DYNAMIC FIELD: IBU HAMIL --}}
                    <div class="dynamic-field field-ibu_hamil">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 bg-pink-50/50 p-5 rounded-2xl border border-pink-100 mb-5">
                            <div>
                                <label class="form-label text-pink-600"><i class="fas fa-tape mr-1"></i> Lingkar Lengan (LILA)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="lingkar_lengan" id="lila_bumil" placeholder="0.0" class="form-input bg-white focus:border-pink-400">
                                    <span class="unit">cm</span>
                                </div>
                                <div id="warning-kek" class="hidden mt-2 px-3 py-2 bg-rose-500 text-white text-[10px] font-bold rounded-lg shadow-sm animate-pulse uppercase tracking-widest">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Indikasi Risiko KEK
                                </div>
                            </div>
                            <div>
                                <label class="form-label text-rose-600"><i class="fas fa-heartbeat mr-1"></i> Tensi Darah</label>
                                <input type="text" name="tekanan_darah" placeholder="120/80" class="form-input font-mono text-center bg-white focus:border-rose-400">
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label text-rose-500"><i class="fas fa-vial mr-1"></i> Cek Hemoglobin (HB) <span class="text-[9px] text-slate-400 lowercase normal-case">- Opsional Laborat</span></label>
                            <div class="input-group">
                                <input type="number" step="0.1" name="hemoglobin" placeholder="0.0" class="form-input focus:ring-4 focus:ring-rose-50">
                                <span class="unit">g/dL</span>
                            </div>
                        </div>
                    </div>

                    {{-- DYNAMIC FIELD: REMAJA & LANSIA --}}
                    <div class="dynamic-field field-dewasa">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="form-label"><i class="fas fa-heartbeat text-rose-500 mr-1"></i> Tekanan Darah (Tensi)</label>
                                <input type="text" name="tekanan_darah" placeholder="120/80" class="form-input focus:ring-4 focus:ring-indigo-50 text-center font-mono">
                            </div>
                            
                            {{-- Field Spesifik Remaja (LILA, LP, HB) --}}
                            <div class="dynamic-field field-remaja">
                                <label class="form-label"><i class="fas fa-tape text-pink-500 mr-1"></i> Lingkar Lengan (Putri)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="lingkar_lengan" placeholder="0.0" class="form-input focus:ring-4 focus:ring-pink-50">
                                    <span class="unit">cm</span>
                                </div>
                            </div>
                            
                            {{-- Lingkar Perut (Remaja & Lansia) --}}
                            <div class="dynamic-field field-lp">
                                <label class="form-label"><i class="fas fa-ruler-horizontal text-sky-500 mr-1"></i> Lingkar Perut (LP)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="lingkar_perut" placeholder="0.0" class="form-input focus:ring-4 focus:ring-sky-50">
                                    <span class="unit">cm</span>
                                </div>
                            </div>
                        </div>

                        {{-- Laboratorium Cepat (Gula, Asam Urat, Kolesterol, HB) --}}
                        <div class="bg-slate-50 border border-slate-200 p-5 rounded-2xl">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 border-b border-slate-200 pb-2"><i class="fas fa-microscope text-indigo-400 mr-1"></i> Tes Lab Sederhana (Opsional)</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="text-[9px] font-bold text-sky-600 uppercase">Gula Darah</label>
                                    <input type="number" step="1" name="gula_darah" placeholder="mg/dL" class="form-input px-2 py-2 text-center text-sm border-sky-200 focus:border-sky-500">
                                </div>
                                <div class="dynamic-field field-hb">
                                    <label class="text-[9px] font-bold text-rose-600 uppercase">Hemoglobin (HB)</label>
                                    <input type="number" step="0.1" name="hemoglobin" placeholder="g/dL" class="form-input px-2 py-2 text-center text-sm border-rose-200 focus:border-rose-500">
                                </div>
                                <div class="dynamic-field field-lansia">
                                    <label class="text-[9px] font-bold text-amber-600 uppercase">Asam Urat</label>
                                    <input type="number" step="0.1" name="asam_urat" placeholder="mg/dL" class="form-input px-2 py-2 text-center text-sm border-amber-200 focus:border-amber-500">
                                </div>
                                <div class="dynamic-field field-lansia">
                                    <label class="text-[9px] font-bold text-purple-600 uppercase">Kolesterol</label>
                                    <input type="number" step="1" name="kolesterol" placeholder="mg/dL" class="form-input px-2 py-2 text-center text-sm border-purple-200 focus:border-purple-500">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>
        
        <div class="p-8 border-t border-slate-100 bg-white/90 backdrop-blur-xl rounded-[32px] shadow-lg flex flex-col sm:flex-row items-center justify-end gap-4">
            <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-black text-[13px] rounded-xl hover:from-indigo-500 hover:to-blue-500 shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 uppercase tracking-wide">
                <i class="fas fa-save text-lg"></i> Simpan Data Pengukuran
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // URL ke API yang baru kita buat di PemeriksaanController
    const API_PASIEN_URL = '{{ route('kader.pemeriksaan.api.pasien') }}'; 

    function updateFormRealtime() {
        const kategori = document.querySelector('input[name="kategori_pasien"]:checked')?.value;
        if(!kategori) return;

        // 1. Sembunyikan & Matikan semua field dinamis
        document.querySelectorAll('.dynamic-field').forEach(el => {
            el.classList.remove('show');
            el.querySelectorAll('input').forEach(inp => inp.disabled = true);
        });

        // 2. Tampilkan sesuai kategori
        if (kategori === 'balita') {
            const el = document.querySelector('.field-balita');
            el.classList.add('show'); el.querySelectorAll('input').forEach(inp => inp.disabled = false);
            document.getElementById('imt-widget').classList.add('hidden');
        } else {
            document.getElementById('imt-widget').classList.remove('hidden');
            
            if (kategori === 'ibu_hamil') {
                const el = document.querySelector('.field-ibu_hamil');
                el.classList.add('show'); el.querySelectorAll('input').forEach(inp => inp.disabled = false);
            } else {
                const el = document.querySelector('.field-dewasa');
                el.classList.add('show'); el.querySelectorAll('input').forEach(inp => inp.disabled = false);
                
                // Nyalakan field Lingkar Perut (LP) untuk Remaja & Lansia
                document.querySelector('.field-lp').classList.add('show');
                document.querySelector('.field-lp input').disabled = false;

                if(kategori === 'remaja') {
                    // Nyalakan field khusus remaja (LILA, HB)
                    document.querySelectorAll('.field-remaja, .field-hb').forEach(e => {
                        e.classList.add('show'); e.querySelectorAll('input').forEach(inp => inp.disabled = false);
                    });
                } else if(kategori === 'lansia') {
                    // Nyalakan field khusus lansia (Asam Urat, Kolesterol)
                    document.querySelectorAll('.field-lansia').forEach(e => {
                        e.classList.add('show'); e.querySelectorAll('input').forEach(inp => inp.disabled = false);
                    });
                }
            }
        }

        // 3. Tarik Data Pasien
        fetchPasien(kategori);
    }

    async function fetchPasien(kategori) {
        const select = document.getElementById('pasien_id');
        const oldId = document.getElementById('old_pasien_id').value;
        select.innerHTML = '<option value="">⏳ Memuat data nama...</option>';
        
        try {
            const response = await fetch(`${API_PASIEN_URL}?kategori=${kategori}`);
            if(!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            
            select.innerHTML = '<option value="">-- Pilih Nama Pasien --</option>';
            data.forEach(p => {
                const isSelected = (oldId == p.id) ? 'selected' : '';
                select.innerHTML += `<option value="${p.id}" ${isSelected}>${p.nama_lengkap} ${p.nik ? ' ('+p.nik+')' : ''}</option>`;
            });
        } catch(e) {
            select.innerHTML = '<option value="">⚠️ Gagal memuat data (Refresh halaman)</option>';
        }
    }

    document.querySelectorAll('.kat-radio').forEach(radio => {
        radio.addEventListener('change', updateFormRealtime);
    });
    updateFormRealtime();

    // AUTO CALCULATE IMT (Dewasa)
    function hitungIMT() {
        const kat = document.querySelector('input[name="kategori_pasien"]:checked')?.value;
        if(kat === 'balita') return;

        const bb = parseFloat(document.getElementById('berat_badan').value);
        const tb = parseFloat(document.getElementById('tinggi_badan').value);
        if (!bb || !tb || tb < 50) return;
        
        const imt = (bb / Math.pow(tb/100, 2)).toFixed(2);
        let label = imt < 18.5 ? 'Kurus' : (imt < 25 ? 'Normal' : (imt < 27 ? 'Gemuk' : 'Obesitas'));
        let color = imt < 18.5 ? 'bg-amber-500' : (imt < 25 ? 'bg-emerald-500' : 'bg-rose-500');
        
        document.getElementById('imt-val').textContent = imt;
        const imtKatEl = document.getElementById('imt-kat');
        imtKatEl.textContent = label;
        imtKatEl.className = `px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-wider text-white ${color}`;
    }
    document.getElementById('berat_badan').addEventListener('input', hitungIMT);
    document.getElementById('tinggi_badan').addEventListener('input', hitungIMT);

    // KEK WARNING LILA
    document.getElementById('lila_bumil')?.addEventListener('input', function() {
        const warn = document.getElementById('warning-kek');
        if(this.value && parseFloat(this.value) < 23.5) warn.classList.remove('hidden');
        else warn.classList.add('hidden');
    });

    document.getElementById('formPemeriksaan').addEventListener('submit', async function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Menyimpan...', html: 'Mengirim data ke database.',
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
                Swal.fire({ icon: 'error', title: 'Gagal', text: result.message || 'Periksa kembali form.' });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error Server', text: 'Koneksi terputus.' });
        }
    });
</script>
@endpush
@endsection