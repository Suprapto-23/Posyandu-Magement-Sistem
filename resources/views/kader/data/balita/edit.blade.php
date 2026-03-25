@extends('layouts.kader')

@section('title', 'Edit Data Remaja')
@section('page-name', 'Edit Pasien')

@push('styles')
<style>
    /* Animasi masuk khas CRM */
    .fade-in { animation: fadeIn 0.4s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Styling Form Input Clean */
    .crm-label {
        display: block; font-size: 0.75rem; font-weight: 600; color: #4b5563; /* gray-600 */
        margin-bottom: 0.375rem;
    }
    .crm-input {
        width: 100%; background-color: #ffffff; border: 1px solid #d1d5db; /* gray-300 */
        color: #111827; /* gray-900 */ font-size: 0.875rem; border-radius: 0.5rem; 
        padding: 0.625rem 0.75rem; outline: none; transition: all 0.2s ease;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .crm-input:focus {
        border-color: #4f46e5; /* indigo-600 */
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }
    .crm-input::placeholder { color: #9ca3af; font-weight: 400; }
    
    /* Styling Error */
    .crm-error-input { border-color: #ef4444 !important; background-color: #fef2f2 !important; }
    .crm-error-text { color: #ef4444; font-size: 0.75rem; margin-top: 0.375rem; font-weight: 500; display: block; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto fade-in">
    
    {{-- Header Clean --}}
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('kader.data.remaja.index') }}" class="w-10 h-10 rounded-lg bg-white border border-gray-200 text-gray-500 flex items-center justify-center hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">Edit Data Remaja</h1>
            <p class="text-sm text-gray-500 mt-0.5">Memperbarui informasi profil milik <span class="font-semibold text-gray-900">{{ $remaja->nama_lengkap }}</span>.</p>
        </div>
    </div>

    {{-- Warning Alert Elegan --}}
    <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 shadow-sm">
        <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5"></i>
        <div>
            <h4 class="text-sm font-semibold text-amber-800">Perhatian Integrasi NIK</h4>
            <p class="text-xs text-amber-700 mt-0.5 leading-relaxed">
                Jika Anda mengubah <strong>NIK Remaja</strong>, sistem akan secara otomatis memutus koneksi dari akun Warga lama dan mencari akun Warga baru yang sesuai dengan NIK tersebut.
            </p>
        </div>
    </div>

    <form action="{{ route('kader.data.remaja.update', $remaja->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
            
            {{-- Section 1: Identitas Pribadi --}}
            <div class="p-6 md:p-8 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-user-circle text-gray-400"></i> Identitas Pribadi
                </h3>
                
                <div class="mb-5">
                    <label class="crm-label">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $remaja->nama_lengkap) }}" required class="crm-input @error('nama_lengkap') crm-error-input @enderror">
                    @error('nama_lengkap') <span class="crm-error-text">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="crm-label">NIK (Kunci Integrasi) <span class="text-rose-500">*</span></label>
                        <input type="number" name="nik" value="{{ old('nik', $remaja->nik) }}" required class="crm-input font-mono @error('nik') crm-error-input @enderror">
                        @error('nik') <span class="crm-error-text">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="crm-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select name="jenis_kelamin" required class="crm-input @error('jenis_kelamin') crm-error-input @enderror">
                            <option value="L" {{ old('jenis_kelamin', $remaja->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $remaja->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="crm-label">Tempat Lahir <span class="text-rose-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $remaja->tempat_lahir) }}" required class="crm-input @error('tempat_lahir') crm-error-input @enderror">
                    </div>
                    <div>
                        <label class="crm-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $remaja->tanggal_lahir->format('Y-m-d')) }}" required class="crm-input @error('tanggal_lahir') crm-error-input @enderror">
                    </div>
                </div>

                <div>
                    <label class="crm-label">Alamat Lengkap <span class="text-rose-500">*</span></label>
                    <textarea name="alamat" rows="2" required class="crm-input resize-none">{{ old('alamat', $remaja->alamat) }}</textarea>
                </div>
            </div>

            {{-- Section 2: Pendidikan & Orang Tua --}}
            <div class="p-6 md:p-8 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-gray-400"></i> Pendidikan & Data Wali
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="crm-label">Nama Sekolah <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="sekolah" value="{{ old('sekolah', $remaja->sekolah) }}" class="crm-input">
                    </div>
                    <div>
                        <label class="crm-label">Kelas <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="kelas" value="{{ old('kelas', $remaja->kelas) }}" class="crm-input">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="crm-label">Nama Orang Tua <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="nama_ortu" value="{{ old('nama_ortu', $remaja->nama_ortu) }}" class="crm-input">
                    </div>
                    <div>
                        <label class="crm-label">No. HP Orang Tua <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="number" name="telepon_ortu" value="{{ old('telepon_ortu', $remaja->telepon_ortu) }}" class="crm-input">
                    </div>
                </div>
            </div>
            
            {{-- Footer Actions --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-3">
                <a href="{{ route('kader.data.remaja.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition-colors text-center">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500/20 transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
            
        </div>
    </form>
</div>

<script>
    // Batasi input tanggal lahir maksimal hari ini
    document.getElementById('tanggal_lahir').max = new Date().toISOString().split('T')[0];
    
    // Batasi NIK tepat 16 digit angka
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            if (this.name === 'nik' && this.value.length > 16) {
                this.value = this.value.slice(0, 16);
            }
        });
    });
</script>
@endsection