@extends('layouts.kader')
@section('title', 'Buat Jadwal')
@section('page-name', 'Buat Agenda Posyandu')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .form-label { display: block; font-size: 0.70rem; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; text-align: left;}
    .form-input { width: 100%; background-color: #f8fafc; border: 2px solid #e2e8f0; color: #0f172a; font-size: 0.875rem; border-radius: 0.75rem; padding: 0.75rem 1rem; outline: none; transition: all 0.3s ease; font-weight: 600; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.02); text-align: left; }
    .form-input:focus { background-color: #ffffff; border-color: #8b5cf6; box-shadow: 0 4px 15px -3px rgba(139, 92, 246, 0.15); transform: translateY(-2px); }
</style>
@endpush

@section('content')
<div class="max-w-[800px] mx-auto animate-slide-up text-center sm:text-left">
    
    <div class="mb-6 flex items-center justify-center sm:justify-start gap-3">
        <a href="{{ route('kader.jadwal.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rancang Agenda Baru</h1>
    </div>

    <form action="{{ route('kader.jadwal.store') }}" method="POST" id="formJadwal">
        @csrf
        <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm overflow-hidden mb-6">
            
            <div class="p-6 md:p-8 border-b border-slate-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="form-label">Nama Kegiatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" required placeholder="Contoh: Posyandu Balita RW 01" class="form-input bg-white">
                    </div>
                    <div>
                        <label class="form-label">Kategori Layanan <span class="text-rose-500">*</span></label>
                        <select name="kategori" required class="form-input bg-white cursor-pointer">
                            <option value="kesehatan_ibu_anak">Kesehatan Ibu & Anak (KIA)</option>
                            <option value="imunisasi">Imunisasi / Vaksinasi</option>
                            <option value="pemeriksaan_lansia">Pemeriksaan Lansia</option>
                            <option value="penyuluhan">Penyuluhan / Edukasi</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="form-label">Target Peserta (Warga) <span class="text-rose-500">*</span></label>
                    <select name="target_peserta" required class="form-input bg-indigo-50 border-indigo-100 text-indigo-800 cursor-pointer">
                        <option value="semua">Semua Warga / Umum</option>
                        <option value="balita">Bayi & Balita (0-59 Bulan)</option>
                        <option value="ibu_hamil">Ibu Hamil</option>
                        <option value="remaja">Remaja (10-19 Tahun)</option>
                        <option value="lansia">Lansia (> 60 Tahun)</option>
                    </select>
                    <p class="text-[10px] font-bold text-slate-400 mt-1.5 text-left">*Peserta yang dipilih akan mendapat notifikasi di aplikasi warga.</p>
                </div>
            </div>

            <div class="p-6 md:p-8 bg-slate-50/50">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="form-label">Tanggal <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal" required class="form-input bg-white cursor-pointer" min="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="form-label">Mulai (WIB) <span class="text-rose-500">*</span></label>
                        <input type="time" name="waktu_mulai" required class="form-input bg-white cursor-pointer" value="08:00">
                    </div>
                    <div>
                        <label class="form-label">Selesai (WIB) <span class="text-rose-500">*</span></label>
                        <input type="time" name="waktu_selesai" required class="form-input bg-white cursor-pointer" value="12:00">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="form-label">Lokasi / Tempat <span class="text-rose-500">*</span></label>
                    <input type="text" name="lokasi" required placeholder="Contoh: Balai Desa / Posyandu RW 02" value="Balai Desa" class="form-input bg-white">
                </div>

                <div>
                    <label class="form-label flex items-center gap-1.5"><i class="fas fa-align-left text-slate-400"></i> Catatan Persyaratan (Opsional)</label>
                    <textarea name="deskripsi" rows="2" placeholder="Contoh: Harap membawa buku KIA dan fotokopi KK..." class="form-input bg-white resize-none"></textarea>
                </div>
            </div>
            
            <div class="p-6 bg-white border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('kader.jadwal.index') }}" class="px-8 py-3 bg-slate-100 text-slate-600 font-black text-sm rounded-xl hover:bg-slate-200 transition-colors uppercase tracking-widest text-center w-full sm:w-auto">Batal</a>
                <button type="submit" class="px-10 py-3 bg-violet-600 text-white font-black text-sm rounded-xl hover:bg-violet-700 shadow-md transition-all uppercase tracking-widest flex justify-center items-center gap-2 w-full sm:w-auto">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('formJadwal').addEventListener('submit', async function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Memproses...', html: 'Menyimpan jadwal ke database.',
            allowOutsideClick: false, showConfirmButton: false,
            willOpen: () => { Swal.showLoading(); }
        });

        try {
            const res = await fetch(this.action, {
                method: 'POST', body: new FormData(this),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const result = await res.json();
            if (res.ok && result.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: result.message, timer: 1500, showConfirmButton: false })
                .then(() => { window.location.href = result.redirect; });
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: result.message || 'Cek kembali isian Anda.' });
            }
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Error Koneksi', text: 'Gagal terhubung ke server.' });
        }
    });
</script>
@endpush
@endsection