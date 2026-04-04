@extends('layouts.kader')
@section('title', 'Edit Data Ibu Hamil')
@section('page-name', 'Edit Ibu Hamil')

@push('styles')
<style>
    .fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
    .crm-label { display:block; font-size:.75rem; font-weight:600; color:#4b5563; margin-bottom:.375rem; }
    .crm-input { width:100%; background:#fff; border:1px solid #d1d5db; color:#111827; font-size:.875rem;
        border-radius:.5rem; padding:.625rem .75rem; outline:none; transition:all .2s;
        box-shadow:0 1px 2px 0 rgba(0,0,0,.05); }
    .crm-input:focus { border-color:#ec4899; box-shadow:0 0 0 4px rgba(236,72,153,.1); }
    .crm-error { border-color:#ef4444!important; background:#fef2f2!important; }
    .crm-error-text { color:#ef4444; font-size:.75rem; margin-top:.375rem; font-weight:500; display:block; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto fade-in">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('kader.data.ibu-hamil.show', $ibuHamil->id) }}"
           class="w-10 h-10 rounded-lg bg-white border border-gray-200 text-gray-500 flex items-center justify-center hover:bg-gray-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Edit Data Ibu Hamil</h1>
            <p class="text-sm text-gray-500 mt-0.5">Memperbarui data identitas & fisik dasar
                <strong class="text-gray-800">{{ $ibuHamil->nama_lengkap }}</strong>.
                Pemeriksaan mendalam hanya oleh bidan.</p>
        </div>
    </div>

    {{-- NOTIF: Kader tidak bisa edit hasil pemeriksaan bidan --}}
    <div class="mb-5 p-4 bg-indigo-50 border border-indigo-200 rounded-xl flex items-start gap-3">
        <i class="fas fa-info-circle text-indigo-500 text-lg mt-0.5"></i>
        <div>
            <p class="font-bold text-indigo-800 text-sm">Perhatian — Batasan Akses Kader</p>
            <p class="text-xs text-indigo-600 mt-0.5 leading-relaxed">
                Anda hanya dapat mengubah <strong>data identitas dan fisik dasar</strong> (BB, TB, HPHT, HPL).
                Hasil pemeriksaan klinis seperti tekanan darah, DJJ, diagnosa, dan resep hanya dapat diisi oleh <strong>Bidan</strong>.
            </p>
        </div>
    </div>

    <form action="{{ route('kader.data.ibu-hamil.update', $ibuHamil->id) }}" method="POST">
        @csrf @method('PUT')

        {{-- SEKSI 1: IDENTITAS --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 md:p-8 mb-5">
            <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <i class="fas fa-user-circle text-gray-400"></i> Identitas Ibu
            </h3>

            <div class="mb-5">
                <label class="crm-label">Nama Lengkap Ibu <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_lengkap"
                       value="{{ old('nama_lengkap', $ibuHamil->nama_lengkap) }}" required
                       class="crm-input @error('nama_lengkap') crm-error @enderror">
                @error('nama_lengkap') <span class="crm-error-text">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="crm-label">NIK Ibu</label>
                    <input type="number" name="nik"
                           value="{{ old('nik', $ibuHamil->nik) }}" placeholder="16 digit NIK"
                           class="crm-input @error('nik') crm-error @enderror">
                    @error('nik') <span class="crm-error-text">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="crm-label">No. Telepon</label>
                    <input type="text" name="telepon_ortu"
                           value="{{ old('telepon_ortu', $ibuHamil->telepon_ortu) }}"
                           class="crm-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="crm-label">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir"
                           value="{{ old('tempat_lahir', $ibuHamil->tempat_lahir) }}"
                           class="crm-input">
                </div>
                <div>
                    <label class="crm-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tgl_lahir"
                           value="{{ old('tanggal_lahir', $ibuHamil->tanggal_lahir?->format('Y-m-d')) }}"
                           class="crm-input">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="crm-label">Nama Suami</label>
                    <input type="text" name="nama_suami"
                           value="{{ old('nama_suami', $ibuHamil->nama_suami) }}"
                           class="crm-input">
                </div>
                <div>
                    <label class="crm-label">Golongan Darah</label>
                    <select name="golongan_darah" class="crm-input">
                        <option value="">-- Pilih --</option>
                        @foreach(['A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-'] as $gd)
                        <option value="{{ $gd }}"
                            {{ old('golongan_darah', $ibuHamil->golongan_darah) == $gd ? 'selected' : '' }}>
                            {{ $gd }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="crm-label">Alamat Domisili <span class="text-rose-500">*</span></label>
                <textarea name="alamat" rows="2" required class="crm-input resize-none">{{ old('alamat', $ibuHamil->alamat) }}</textarea>
            </div>
        </div>

        {{-- SEKSI 2: DATA KEHAMILAN --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 md:p-8 mb-5">
            <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                <i class="fas fa-baby-carriage text-gray-400"></i> Data Kehamilan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="crm-label">HPHT</label>
                    <input type="date" name="hpht" id="hpht"
                           value="{{ old('hpht', $ibuHamil->hpht?->format('Y-m-d')) }}"
                           class="crm-input">
                </div>
                <div>
                    <label class="crm-label">HPL (Perkiraan Lahir)</label>
                    <input type="date" name="hpl" id="hpl"
                           value="{{ old('hpl', $ibuHamil->hpl?->format('Y-m-d')) }}"
                           class="crm-input">
                </div>
            </div>

            <div>
                <label class="crm-label">Riwayat Penyakit / Komplikasi</label>
                <input type="text" name="riwayat_penyakit"
                       value="{{ old('riwayat_penyakit', $ibuHamil->riwayat_penyakit) }}"
                       placeholder="Mis: Hipertensi, Diabetes Gestasional..."
                       class="crm-input">
            </div>
        </div>

        {{-- SEKSI 3: DATA FISIK --}}
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl shadow-sm p-6 md:p-8 mb-5">
            <h3 class="text-base font-semibold text-emerald-900 mb-1 flex items-center gap-2">
                <i class="fas fa-weight text-emerald-500"></i> Data Fisik
                <span class="ml-auto text-[10px] font-black bg-emerald-100 text-emerald-700 px-2 py-1 rounded-lg border border-emerald-200 uppercase">Diisi Kader</span>
            </h3>
            <p class="text-xs text-emerald-700 mb-5">BB dan TB saat ini. IMT dihitung otomatis.</p>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="crm-label">Berat Badan (kg)</label>
                    <div class="relative">
                        <input type="number" step="0.1" name="berat_badan" id="berat_badan"
                               value="{{ old('berat_badan', $ibuHamil->berat_badan) }}"
                               placeholder="Contoh: 65" class="crm-input pr-10">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">kg</span>
                    </div>
                </div>
                <div>
                    <label class="crm-label">Tinggi Badan (cm)</label>
                    <div class="relative">
                        <input type="number" step="0.1" name="tinggi_badan" id="tinggi_badan"
                               value="{{ old('tinggi_badan', $ibuHamil->tinggi_badan) }}"
                               placeholder="Contoh: 158" class="crm-input pr-10">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">cm</span>
                    </div>
                </div>
            </div>

            <div id="imt-result" class="hidden mt-4 p-3 bg-white border border-emerald-200 rounded-xl text-center">
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-0.5">Hasil IMT</p>
                <p id="imt-val" class="text-2xl font-black text-emerald-700"></p>
                <p id="imt-kat" class="text-xs font-bold text-emerald-600 mt-0.5"></p>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm px-6 py-4 flex flex-col-reverse sm:flex-row items-center justify-between gap-3">
            <a href="{{ route('kader.data.ibu-hamil.show', $ibuHamil->id) }}"
               class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-center">
                Batal
            </a>
            <button type="submit"
                    class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-white bg-pink-600 rounded-lg hover:bg-pink-700 transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('tgl_lahir').max = new Date().toISOString().split('T')[0];
    document.getElementById('hpht').max      = new Date().toISOString().split('T')[0];

    document.querySelector('input[name="nik"]')?.addEventListener('input', function() {
        if (this.value.length > 16) this.value = this.value.slice(0, 16);
    });

    document.getElementById('hpht').addEventListener('change', function() {
        const hpht = new Date(this.value);
        if (isNaN(hpht)) return;
        const hpl = new Date(hpht);
        hpl.setDate(hpl.getDate() + 280);
        const hplInput = document.getElementById('hpl');
        if (!hplInput.value) hplInput.value = hpl.toISOString().split('T')[0];
    });

    function hitungIMT() {
        const bb = parseFloat(document.getElementById('berat_badan').value);
        const tb = parseFloat(document.getElementById('tinggi_badan').value);
        if (!bb || !tb || tb < 50) { document.getElementById('imt-result').classList.add('hidden'); return; }
        const imt = (bb / Math.pow(tb / 100, 2)).toFixed(2);
        let kat = imt < 18.5 ? 'Kurus' : imt < 25 ? 'Normal' : imt < 27 ? 'Gemuk Ringan' : 'Obesitas';
        document.getElementById('imt-val').textContent = imt;
        document.getElementById('imt-kat').textContent = kat;
        document.getElementById('imt-result').classList.remove('hidden');
    }
    document.getElementById('berat_badan').addEventListener('input', hitungIMT);
    document.getElementById('tinggi_badan').addEventListener('input', hitungIMT);
    window.addEventListener('DOMContentLoaded', hitungIMT);
</script>
@endsection