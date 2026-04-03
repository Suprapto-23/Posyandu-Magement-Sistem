@extends('layouts.kader')

@section('title', 'Edit Data Lansia')
@section('page-name', 'Edit Pasien')

@push('styles')
<style>
    .fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .crm-label { display: block; font-size: 0.75rem; font-weight: 600; color: #4b5563; margin-bottom: 0.375rem; }
    .crm-input {
        width: 100%; background-color: #ffffff; border: 1px solid #d1d5db;
        color: #111827; font-size: 0.875rem; border-radius: 0.5rem;
        padding: 0.625rem 0.75rem; outline: none; transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .crm-input:focus { border-color: #059669; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }
    .crm-error-input { border-color: #ef4444 !important; background-color: #fef2f2 !important; }
    .crm-error-text { color: #ef4444; font-size: 0.75rem; margin-top: 0.375rem; font-weight: 500; display: block; }

    .imt-kurus    { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
    .imt-normal   { background:#d1fae5; color:#065f46; border-color:#a7f3d0; }
    .imt-gemuk    { background:#fef3c7; color:#92400e; border-color:#fde68a; }
    .imt-obesitas { background:#fee2e2; color:#991b1b; border-color:#fca5a5; }

    .kem-card { cursor:pointer; border:2px solid #e5e7eb; border-radius:0.75rem; padding:1rem; transition:all 0.2s; }
    .kem-card:hover { border-color:#10b981; background:#f0fdf4; }
    .kem-card.selected-a { border-color:#10b981; background:#d1fae5; }
    .kem-card.selected-b { border-color:#f59e0b; background:#fef3c7; }
    .kem-card.selected-c { border-color:#ef4444; background:#fee2e2; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto fade-in">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('kader.data.lansia.index') }}" class="w-10 h-10 rounded-lg bg-white border border-gray-200 text-gray-500 flex items-center justify-center hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">Edit Data Lansia</h1>
            <p class="text-sm text-gray-500 mt-0.5">Memperbarui informasi profil milik <span class="font-semibold text-gray-900">{{ $lansia->nama_lengkap }}</span>.</p>
        </div>
    </div>

    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl p-4 mb-5 shadow-sm">
        <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5"></i>
        <div>
            <h4 class="text-sm font-semibold text-amber-800">Perhatian Integrasi NIK</h4>
            <p class="text-xs text-amber-700 mt-0.5 leading-relaxed">Jika Anda mengubah <strong>NIK Lansia</strong>, sistem akan otomatis memutus koneksi lama dan mencari akun Warga baru yang cocok.</p>
        </div>
    </div>

    <form action="{{ route('kader.data.lansia.update', $lansia->id) }}" method="POST" id="formLansia">
        @csrf @method('PUT')

        {{-- ===== SEKSI 1: IDENTITAS ===== --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-5">
            <div class="p-6 md:p-8">
                <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-user-circle text-gray-400"></i> Identitas Pribadi
                </h3>

                <div class="mb-5">
                    <label class="crm-label">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $lansia->nama_lengkap) }}" required class="crm-input @error('nama_lengkap') crm-error-input @enderror">
                    @error('nama_lengkap') <span class="crm-error-text">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="crm-label">NIK <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik" value="{{ old('nik', $lansia->nik) }}" required class="crm-input font-mono @error('nik') crm-error-input @enderror">
                        @error('nik') <span class="crm-error-text">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="crm-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" required class="crm-input">
                            <option value="L" {{ old('jenis_kelamin', $lansia->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $lansia->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="crm-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $lansia->tempat_lahir) }}" required class="crm-input">
                    </div>
                    <div>
                        <label class="crm-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $lansia->tanggal_lahir->format('Y-m-d')) }}" required class="crm-input">
                    </div>
                </div>

                <div>
                    <label class="crm-label">Alamat Lengkap <span class="text-rose-500">*</span></label>
                    <textarea name="alamat" rows="2" required class="crm-input resize-none">{{ old('alamat', $lansia->alamat) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ===== SEKSI 2: KEMANDIRIAN ===== --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-5">
            <div class="p-6 md:p-8">
                <h3 class="text-base font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <i class="fas fa-hand-holding-heart text-gray-400"></i> Tingkat Kemandirian
                </h3>
                <p class="text-xs text-gray-500 mb-5">Pilih kategori kemandirian yang sesuai kondisi lansia saat ini.</p>

                @php $kemVal = old('kemandirian', $lansia->kemandirian); @endphp
                <input type="hidden" name="kemandirian" id="kemandirian_val" value="{{ $kemVal }}">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="kem-card {{ $kemVal == 'A' ? 'selected-a' : '' }}" onclick="pilihKemandirian('A', this)">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg font-black">A</div>
                            <div>
                                <p class="font-extrabold text-gray-800 text-sm">Mandiri</p>
                                <p class="text-[11px] text-gray-400 font-medium">Kategori A</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">Dapat melakukan aktivitas sehari-hari secara mandiri tanpa bantuan.</p>
                        <div class="mt-3 flex items-center gap-1.5 text-emerald-600">
                            <i class="fas fa-walking text-sm"></i>
                            <span class="text-[11px] font-bold">Aktif & Independen</span>
                        </div>
                    </div>

                    <div class="kem-card {{ $kemVal == 'B' ? 'selected-b' : '' }}" onclick="pilihKemandirian('B', this)">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-lg font-black">B</div>
                            <div>
                                <p class="font-extrabold text-gray-800 text-sm">Sebagian Bantuan</p>
                                <p class="text-[11px] text-gray-400 font-medium">Kategori B</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">Membutuhkan bantuan untuk sebagian aktivitas tertentu.</p>
                        <div class="mt-3 flex items-center gap-1.5 text-amber-600">
                            <i class="fas fa-hands-helping text-sm"></i>
                            <span class="text-[11px] font-bold">Perlu Pendampingan</span>
                        </div>
                    </div>

                    <div class="kem-card {{ $kemVal == 'C' ? 'selected-c' : '' }}" onclick="pilihKemandirian('C', this)">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-9 h-9 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-lg font-black">C</div>
                            <div>
                                <p class="font-extrabold text-gray-800 text-sm">Total</p>
                                <p class="text-[11px] text-gray-400 font-medium">Kategori C</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 leading-relaxed">Bergantung sepenuhnya pada orang lain. Contoh: pasca-stroke.</p>
                        <div class="mt-3 flex items-center gap-1.5 text-rose-600">
                            <i class="fas fa-wheelchair text-sm"></i>
                            <span class="text-[11px] font-bold">Ketergantungan Penuh</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== SEKSI 3: IMT ===== --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-5">
            <div class="p-6 md:p-8">
                <h3 class="text-base font-semibold text-gray-900 mb-1 flex items-center gap-2">
                    <i class="fas fa-weight text-gray-400"></i> Indeks Massa Tubuh (IMT)
                </h3>
                <p class="text-xs text-gray-500 mb-5">IMT dihitung otomatis dari berat dan tinggi badan. Opsional.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-4">
                    <div>
                        <label class="crm-label">Berat Badan (kg)</label>
                        <div class="relative">
                            <input type="number" step="0.1" id="berat_badan" name="berat_badan" value="{{ old('berat_badan', $lansia->berat_badan) }}" placeholder="Contoh: 60" class="crm-input pr-12">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">kg</span>
                        </div>
                    </div>
                    <div>
                        <label class="crm-label">Tinggi Badan (cm)</label>
                        <div class="relative">
                            <input type="number" step="0.1" id="tinggi_badan" name="tinggi_badan" value="{{ old('tinggi_badan', $lansia->tinggi_badan) }}" placeholder="Contoh: 160" class="crm-input pr-12">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">cm</span>
                        </div>
                    </div>
                </div>

                <div id="imt-preview" class="hidden border rounded-xl p-4 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center shrink-0 bg-white border-2 border-current">
                        <span id="imt-angka" class="text-lg font-black"></span>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-widest opacity-60 mb-0.5">Hasil IMT</p>
                        <p id="imt-label" class="text-base font-black"></p>
                        <p id="imt-range" class="text-xs font-medium opacity-70 mt-0.5"></p>
                    </div>
                </div>
                <div id="imt-empty" class="text-center py-4 text-xs text-gray-400 font-medium italic">
                    IMT akan dihitung otomatis setelah berat & tinggi badan diisi.
                </div>
            </div>
        </div>

        {{-- ===== SEKSI 4: RIWAYAT KESEHATAN ===== --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-5">
            <div class="p-6 md:p-8 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-stethoscope text-gray-400"></i> Riwayat Kesehatan (Opsional)
                </h3>
                <div>
                    <label class="crm-label">Penyakit Bawaan / Keluhan Rutin</label>
                    <textarea name="penyakit_bawaan" rows="3" class="crm-input">{{ old('penyakit_bawaan', $lansia->penyakit_bawaan) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ===== FOOTER ===== --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm px-6 py-4 flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-3">
            <a href="{{ route('kader.data.lansia.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-center">
                Batal
            </a>
            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 border border-transparent rounded-lg hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>

    </form>
</div>

<script>
    document.getElementById('tanggal_lahir').max = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="nik"]').addEventListener('input', function() {
        if (this.value.length > 16) this.value = this.value.slice(0, 16);
    });

    function pilihKemandirian(val, el) {
        document.querySelectorAll('.kem-card').forEach(c => c.classList.remove('selected-a','selected-b','selected-c'));
        const current = document.getElementById('kemandirian_val').value;
        if (current === val) { document.getElementById('kemandirian_val').value = ''; return; }
        document.getElementById('kemandirian_val').value = val;
        if (val === 'A') el.classList.add('selected-a');
        if (val === 'B') el.classList.add('selected-b');
        if (val === 'C') el.classList.add('selected-c');
    }

    function hitungIMT() {
        const bb = parseFloat(document.getElementById('berat_badan').value);
        const tb = parseFloat(document.getElementById('tinggi_badan').value);
        if (!bb || !tb || tb < 50) {
            document.getElementById('imt-preview').classList.add('hidden');
            document.getElementById('imt-empty').classList.remove('hidden');
            return;
        }
        const imt = (bb / Math.pow(tb / 100, 2)).toFixed(2);
        let label, rangeText, cssClass;
        if (imt < 18.5)      { label='Kurus';        rangeText='IMT < 18,5';         cssClass='imt-kurus'; }
        else if (imt < 25.0) { label='Normal';       rangeText='18,5 ≤ IMT < 25,0';  cssClass='imt-normal'; }
        else if (imt < 27.0) { label='Gemuk Ringan'; rangeText='25,0 ≤ IMT < 27,0';  cssClass='imt-gemuk'; }
        else                  { label='Obesitas';     rangeText='IMT ≥ 27,0';         cssClass='imt-obesitas'; }
        const preview = document.getElementById('imt-preview');
        preview.className = 'border rounded-xl p-4 flex items-center gap-4 ' + cssClass;
        preview.classList.remove('hidden');
        document.getElementById('imt-empty').classList.add('hidden');
        document.getElementById('imt-angka').textContent = imt;
        document.getElementById('imt-label').textContent = label;
        document.getElementById('imt-range').textContent = rangeText;
    }

    document.getElementById('berat_badan').addEventListener('input', hitungIMT);
    document.getElementById('tinggi_badan').addEventListener('input', hitungIMT);
    window.addEventListener('DOMContentLoaded', hitungIMT);
</script>
@endsection