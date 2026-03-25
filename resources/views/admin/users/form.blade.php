{{--
  PATH   : resources/views/admin/users/create.blade.php
            resources/views/admin/users/edit.blade.php   (isi file SAMA, pakai $isEdit)
  FUNGSI : Form tambah / edit warga
  NOTE   : Login warga menggunakan NIK (bukan email)
           Email dibuat otomatis: {nik}@posyandu.user
--}}
@extends('layouts.admin')
@php $isEdit = isset($user); @endphp
@section('title', $isEdit ? 'Edit Warga' : 'Tambah Warga Baru')

@push('styles')
<style>
.form-hero { background:linear-gradient(135deg,#0f172a,#0d9488); border-radius:16px; padding:1.5rem; color:#fff; margin-bottom:1.5rem; }
.form-hero h4 { font-size:1.1rem; font-weight:800; margin:0 0 .2rem; }
.form-hero p  { font-size:.78rem; opacity:.75; margin:0; }
.form-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; margin-bottom:1.25rem; }
.form-card-head { padding:.85rem 1.15rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:.5rem; }
.form-card-head h6 { font-size:.85rem; font-weight:800; color:#0f172a; margin:0; }
.form-card-head i { color:#0d9488; }
.form-card-body { padding:1.15rem; }
.form-row { display:grid; gap:1rem; grid-template-columns:1fr 1fr; }
.form-group { display:flex; flex-direction:column; gap:.35rem; }
.form-group.full { grid-column:1 / -1; }
.fl { font-size:.75rem; font-weight:700; color:#374151; }
.fl .req { color:#dc2626; }
.fi {
    height:40px; border:1.5px solid #e2e8f0; border-radius:10px;
    padding:0 .85rem; font-size:.84rem; font-family:inherit;
    outline:none; transition:border .15s; width:100%;
    background:#fff; color:#0f172a;
}
.fi:focus { border-color:#0d9488; box-shadow:0 0 0 3px rgba(13,148,136,.1); }
.fi.is-invalid { border-color:#dc2626; }
.fi-ta { height:auto; padding:.65rem .85rem; min-height:80px; resize:vertical; }
.err { font-size:.72rem; color:#dc2626; margin-top:.15rem; }
.info-box { background:#f0fdfa; border:1.5px solid #99f6e4; border-radius:10px; padding:.85rem 1rem; font-size:.78rem; color:#0f172a; }
.info-box strong { color:#0d9488; }
@media(max-width:576px){ .form-row { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

<div class="form-hero">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h4>{{ $isEdit ? '✏️ Edit Data Warga' : '➕ Tambah Warga Baru' }}</h4>
            <p>
                @if($isEdit)
                    Memperbarui akun: {{ $user->profile?->full_name ?? $user->name }}
                @else
                    Isi data lengkap warga. Password otomatis dibuat.
                @endif
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary-app">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<form method="POST" action="{{ $isEdit ? route('admin.users.update', $user->id) : route('admin.users.store') }}" id="mainForm">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- Info Box --}}
    <div class="info-box mb-4">
        <strong><i class="fas fa-info-circle me-1"></i>Cara Login Warga:</strong>
        Warga login menggunakan <strong>NIK 16 digit</strong> sebagai username dan password yang diberikan admin.
        @if($isEdit)
        <br><small style="color:#64748b">Jika NIK diubah, username login otomatis berubah mengikuti NIK baru.</small>
        @endif
    </div>

    {{-- Section: Data Pribadi --}}
    <div class="form-card">
        <div class="form-card-head">
            <i class="fas fa-user"></i>
            <h6>Data Pribadi</h6>
        </div>
        <div class="form-card-body">
            <div class="form-row">
                <div class="form-group full">
                    <label class="fl">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" name="full_name" class="fi @error('full_name') is-invalid @enderror"
                        value="{{ old('full_name', $user?->profile?->full_name ?? $user?->name) }}"
                        placeholder="Nama sesuai KTP">
                    @error('full_name')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="fl">NIK (16 digit) <span class="req">*</span></label>
                    <input type="text" name="nik" id="nikInput" class="fi @error('nik') is-invalid @enderror"
                        value="{{ old('nik', $user?->nik ?? $user?->profile?->nik) }}"
                        placeholder="1234567890123456" maxlength="16"
                        @if($isEdit) readonly title="NIK tidak dapat diubah setelah akun dibuat" @endif>
                    @error('nik')<span class="err">{{ $message }}</span>@enderror
                    @if($isEdit)
                    <span style="font-size:.7rem;color:#94a3b8">NIK terkunci setelah pendaftaran</span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="fl">Jenis Kelamin <span class="req">*</span></label>
                    <select name="jenis_kelamin" class="fi @error('jenis_kelamin') is-invalid @enderror">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin', $user?->profile?->jenis_kelamin) == 'L' ? 'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $user?->profile?->jenis_kelamin) == 'P' ? 'selected':'' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="fl">Nomor Telepon <span class="req">*</span></label>
                    <input type="text" name="telepon" id="telInput" class="fi @error('telepon') is-invalid @enderror"
                        value="{{ old('telepon', $user?->profile?->telepon) }}"
                        placeholder="08xxxxxxxxxx">
                    @error('telepon')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="fl">Tempat Lahir <span class="req">*</span></label>
                    <input type="text" name="tempat_lahir" class="fi @error('tempat_lahir') is-invalid @enderror"
                        value="{{ old('tempat_lahir', $user?->profile?->tempat_lahir) }}"
                        placeholder="Nama kota">
                    @error('tempat_lahir')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="fl">Tanggal Lahir <span class="req">*</span></label>
                    <input type="date" name="tanggal_lahir" class="fi @error('tanggal_lahir') is-invalid @enderror"
                        value="{{ old('tanggal_lahir', $user?->profile?->tanggal_lahir) }}">
                    @error('tanggal_lahir')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="form-group full">
                    <label class="fl">Alamat Lengkap <span class="req">*</span></label>
                    <textarea name="alamat" class="fi fi-ta @error('alamat') is-invalid @enderror"
                        placeholder="Jl. Contoh No. 1, RT/RW, Kelurahan, Kecamatan">{{ old('alamat', $user?->profile?->alamat) }}</textarea>
                    @error('alamat')<span class="err">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="fl">Status Akun <span class="req">*</span></label>
                    <select name="status" class="fi @error('status') is-invalid @enderror">
                        <option value="active"   {{ old('status', $user?->status ?? 'active') == 'active'   ? 'selected':'' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $user?->status) == 'inactive' ? 'selected':'' }}>Nonaktif</option>
                    </select>
                    @error('status')<span class="err">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol --}}
    <div class="d-flex gap-2 flex-wrap justify-content-end">
        <a href="{{ route('admin.users.index') }}" class="btn-secondary-app">
            <i class="fas fa-times"></i> Batal
        </a>
        <button type="submit" class="btn-primary-app" id="submitBtn">
            <i class="fas fa-save"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Buat Akun Warga' }}
        </button>
    </div>
</form>
@endsection
@push('scripts')
<script>
// NIK: angka saja
const nik = document.getElementById('nikInput');
if (nik && !nik.readOnly) {
    nik.addEventListener('input', e => {
        e.target.value = e.target.value.replace(/\D/g,'').slice(0,16);
    });
}
// Telepon
const tel = document.getElementById('telInput');
if (tel) tel.addEventListener('input', e => {
    e.target.value = e.target.value.replace(/[^\d+]/g,'');
});
// Submit loading
document.getElementById('mainForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
});
</script>
@endpush