@extends('layouts.kader')

@section('title', 'Tambah Data Lansia')
@section('page-name', 'Registrasi Lansia')

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
    .crm-input::placeholder { color: #9ca3af; font-weight: 400; }
    
    .crm-error-input { border-color: #ef4444 !important; background-color: #fef2f2 !important; }
    .crm-error-text { color: #ef4444; font-size: 0.75rem; margin-top: 0.375rem; font-weight: 500; display: block; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto fade-in">
    
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('kader.data.lansia.index') }}" class="w-10 h-10 rounded-lg bg-white border border-gray-200 text-gray-500 flex items-center justify-center hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">Registrasi Lansia</h1>
            <p class="text-sm text-gray-500 mt-0.5">Daftarkan data diri lansia untuk keperluan rekam medis.</p>
        </div>
    </div>

    <form action="{{ route('kader.data.lansia.store') }}" method="POST">
        @csrf
        
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
            
            <div class="p-6 md:p-8 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-user-circle text-gray-400"></i> Identitas Pribadi
                </h3>
                
                <div class="mb-5">
                    <label class="crm-label">Nama Lengkap Sesuai KTP <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Budi Santoso" class="crm-input @error('nama_lengkap') crm-error-input @enderror">
                    @error('nama_lengkap') <span class="crm-error-text">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="crm-label">NIK (Kunci Integrasi) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik" value="{{ old('nik') }}" required placeholder="Masukkan 16 digit NIK" class="crm-input font-mono @error('nik') crm-error-input @enderror">
                        @error('nik') <span class="crm-error-text">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="crm-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" required class="crm-input @error('jenis_kelamin') crm-error-input @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="crm-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Kota Kelahiran" class="crm-input @error('tempat_lahir') crm-error-input @enderror">
                    </div>
                    <div>
                        <label class="crm-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="crm-input @error('tanggal_lahir') crm-error-input @enderror">
                    </div>
                </div>

                <div>
                    <label class="crm-label">Alamat Lengkap <span class="text-rose-500">*</span></label>
                    <textarea name="alamat" rows="2" required placeholder="Alamat domisili saat ini..." class="crm-input resize-none">{{ old('alamat') }}</textarea>
                </div>
            </div>

            <div class="p-6 md:p-8 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-stethoscope text-gray-400"></i> Riwayat Kesehatan (Opsional)
                </h3>

                <div>
                    <label class="crm-label">Penyakit Bawaan / Keluhan Rutin</label>
                    <textarea name="penyakit_bawaan" rows="3" placeholder="Contoh: Hipertensi, Diabetes... (Pisahkan dengan koma)" class="crm-input">{{ old('penyakit_bawaan') }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Kosongkan jika lansia terpantau sehat tanpa riwayat medis khusus.</p>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-3">
                <a href="{{ route('kader.data.lansia.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition-colors text-center">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 border border-transparent rounded-lg hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-500/20 transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Data Lansia
                </button>
            </div>
            
        </div>
    </form>
</div>

<script>
    document.getElementById('tanggal_lahir').max = new Date().toISOString().split('T')[0];
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            if (this.name === 'nik' && this.value.length > 16) {
                this.value = this.value.slice(0, 16);
            }
        });
    });
</script>
@endsection