@extends('layouts.kader')

@section('title', 'Edit Data Remaja')
@section('page-name', 'Edit Remaja')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .form-label { display: block; font-size: 0.70rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
    .form-input { width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a; font-size: 0.875rem; border-radius: 0.75rem; padding: 0.75rem 1rem; outline: none; transition: all 0.3s ease; font-weight: 600; }
    .form-input:focus { background-color: #ffffff; border-color: #f59e0b; box-shadow: 0 4px 12px -3px rgba(245, 158, 11, 0.15); }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto animate-slide-up">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('kader.data.remaja.index') }}" class="w-10 h-10 bg-white border border-slate-200 text-slate-500 rounded-xl flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Koreksi Profil Remaja</h1>
            <p class="text-sm font-bold text-slate-500">Edit data milik <span class="text-amber-500">{{ $remaja->nama_lengkap }}</span></p>
        </div>
    </div>

    <form action="{{ route('kader.data.remaja.update', $remaja->id) }}" method="POST" id="formEditRemaja">
        @csrf @method('PUT')
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="space-y-5">
                        <h3 class="text-xs font-black text-amber-500 uppercase tracking-widest border-b border-slate-100 pb-2 mb-4"><i class="fas fa-id-card mr-2"></i> Identitas Diri</h3>
                        
                        <div>
                            <label class="form-label">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ $remaja->nama_lengkap }}" required class="form-input focus:border-amber-400">
                        </div>
                        
                        <div>
                            <label class="form-label">NIK (Nomor Induk Kependudukan)</label>
                            <input type="number" name="nik" value="{{ $remaja->nik }}" class="form-input focus:border-amber-400" oninput="if(this.value.length > 16) this.value = this.value.slice(0, 16);">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="{{ $remaja->tempat_lahir }}" class="form-input focus:border-amber-400">
                            </div>
                            <div>
                                <label class="form-label">Tanggal Lahir <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ $remaja->tanggal_lahir }}" required class="form-input focus:border-amber-400">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <select name="jenis_kelamin" required class="form-input focus:border-amber-400">
                                <option value="L" {{ $remaja->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $remaja->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <h3 class="text-xs font-black text-amber-500 uppercase tracking-widest border-b border-slate-100 pb-2 mb-4"><i class="fas fa-graduation-cap mr-2"></i> Pendidikan & Keluarga</h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="form-label">Nama Sekolah</label>
                                <input type="text" name="sekolah" value="{{ $remaja->sekolah }}" class="form-input focus:border-amber-400">
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" value="{{ $remaja->kelas }}" class="form-input focus:border-amber-400">
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Nama Orang Tua (Wali) <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_ortu" value="{{ $remaja->nama_ortu }}" required class="form-input focus:border-amber-400">
                        </div>

                        <div>
                            <label class="form-label">No. Telepon/WhatsApp Ortu</label>
                            <input type="number" name="telepon_ortu" value="{{ $remaja->telepon_ortu }}" class="form-input focus:border-amber-400">
                        </div>

                        <div>
                            <label class="form-label">Alamat Lengkap</label>
                            <input type="text" name="alamat" value="{{ $remaja->alamat }}" class="form-input focus:border-amber-400">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-6 sm:px-10 sm:py-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('kader.data.remaja.index') }}" class="w-full sm:w-auto px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-100 transition-colors text-center">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-amber-500 text-white font-extrabold text-sm rounded-xl hover:bg-amber-600 shadow-[0_4px_12px_rgba(245,158,11,0.3)] hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 10, today.getMonth(), today.getDate()).toISOString().split('T')[0];
    const minDate = new Date(today.getFullYear() - 20, today.getMonth(), today.getDate()).toISOString().split('T')[0];
    document.getElementById('tanggal_lahir').max = maxDate;
    document.getElementById('tanggal_lahir').min = minDate;

    document.getElementById('formEditRemaja').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Update Data?',
            text: "Perubahan akan disimpan ke database rekam medis.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Update!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, showConfirmButton: false, didOpen: () => { Swal.showLoading() } });
                this.submit();
            }
        });
    });
</script>
@endsection