@extends('layouts.bidan')

@section('title', 'Buat Jadwal Baru')
@section('page-name', 'Tambah Agenda Medis')

@push('styles')
<style>
    .fade-in-up { animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    .med-input { 
        width: 100%; background: #ffffff; border: 2px solid #f1f5f9; border-radius: 16px; 
        padding: 16px 20px 16px 52px; color: #0f172a; font-weight: 600; font-size: 14px; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); outline: none; appearance: none;
        box-shadow: 0 2px 6px rgba(15,23,42,0.02);
    }
    .med-input:focus { border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15), 0 2px 6px rgba(15,23,42,0.02); }
    .med-input::placeholder { color: #94a3b8; font-weight: 500; }
    
    .med-label { display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 10px; margin-left: 4px; font-family: 'Poppins', sans-serif;}
    .input-wrapper { position: relative; width: 100%; }
    .input-icon { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 16px; transition: all 0.3s ease; z-index: 10; }
    .med-input:focus + .input-icon { color: #0ea5e9; }

    .broadcast-panel { background: linear-gradient(160deg, #0ea5e9 0%, #0284c7 100%); border-radius: 28px; position: relative; overflow: hidden; }
    .broadcast-panel::before { content: ''; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; }

    .select-custom { padding-right: 40px !important; cursor: pointer; }
    .select-arrow { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }
</style>
@endpush

@section('content')
{{-- Loader Sistem --}}
<div id="smoothLoader" class="fixed inset-0 bg-slate-50/90 backdrop-blur-md z-[9999] flex flex-col items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
    <div class="relative w-20 h-20 flex items-center justify-center mb-5">
        <div class="absolute inset-0 border-4 border-cyan-100 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-cyan-600 rounded-full border-t-transparent animate-spin"></div>
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
            <i class="fas fa-paper-plane text-cyan-600 text-xl animate-pulse"></i>
        </div>
    </div>
    <p class="text-[10px] font-black text-cyan-700 uppercase tracking-[0.2em] font-poppins" id="loaderText">BROADCAST NOTIFIKASI...</p>
</div>

<div class="max-w-[1100px] mx-auto fade-in-up pb-20">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 px-2">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-[20px] bg-white border border-slate-200 text-cyan-600 flex items-center justify-center text-2xl shadow-sm"><i class="fas fa-calendar-plus"></i></div>
            <div>
                <h1 class="text-[26px] font-black text-slate-800 tracking-tight font-poppins leading-none">Terbitkan Jadwal</h1>
                <p class="text-[13px] font-semibold text-slate-500 mt-1.5">Publikasikan agenda Posyandu dan kirim notifikasi otomatis.</p>
            </div>
        </div>
        <a href="{{ route('bidan.jadwal.index') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-white border border-slate-200 text-slate-600 font-bold text-[11.5px] uppercase tracking-widest rounded-[16px] hover:bg-slate-50 hover:text-cyan-600 transition-all shadow-sm">
            <i class="fas fa-arrow-left text-slate-400"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-[36px] border border-slate-100 shadow-[0_25px_70px_-15px_rgba(0,0,0,0.06)] overflow-hidden flex flex-col md:flex-row">
        
        <div class="md:w-[350px] broadcast-panel p-10 flex flex-col text-white">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-[20px] border border-white/30 flex items-center justify-center text-3xl mb-8 shadow-xl"><i class="fas fa-broadcast-tower"></i></div>
            <h2 class="text-2xl font-black font-poppins tracking-tight mb-4 leading-tight">Sistem Notifikasi Pintar</h2>
            <p class="text-cyan-50 text-[14px] leading-relaxed font-medium opacity-90">Jadwal yang Anda buat akan otomatis didistribusikan sebagai Push Notification ke aplikasi warga berdasarkan Target Sasaran.</p>
        </div>

        <div class="flex-1 p-8 md:p-12">
            <form id="formJadwal" action="{{ route('bidan.jadwal.store') }}" method="POST">
                @csrf
                <div class="space-y-8">
                    
                    <div>
                        <label class="med-label">Judul Agenda Kegiatan <span class="text-rose-500">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-heading input-icon"></i>
                            <input type="text" name="judul" value="{{ old('judul') }}" required class="med-input" placeholder="Contoh: Imunisasi Polio Akbar Desa">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="med-label">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                            <div class="input-wrapper">
                                <i class="fas fa-calendar-day input-icon"></i>
                                <input type="date" name="tanggal" value="{{ old('tanggal') }}" required class="med-input">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="med-label">Jam Mulai <span class="text-rose-500">*</span></label>
                                <div class="input-wrapper">
                                    <i class="far fa-clock input-icon"></i>
                                    <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required class="med-input" style="padding-left: 48px;">
                                </div>
                            </div>
                            <div>
                                <label class="med-label">Jam Selesai <span class="text-rose-500">*</span></label>
                                <div class="input-wrapper">
                                    <i class="far fa-clock input-icon"></i>
                                    <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required class="med-input" style="padding-left: 48px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="med-label">Lokasi Kegiatan / Gedung <span class="text-rose-500">*</span></label>
                        <div class="input-wrapper">
                            <i class="fas fa-map-marked-alt input-icon"></i>
                            <input type="text" name="lokasi" value="{{ old('lokasi', 'Posyandu Induk Desa') }}" required class="med-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="med-label">Kategori Layanan <span class="text-rose-500">*</span></label>
                            <div class="input-wrapper">
                                <i class="fas fa-tags input-icon"></i>
                                <select name="kategori" required class="med-input select-custom">
                                    <option value="posyandu">Posyandu Rutin Bulanan</option>
                                    <option value="imunisasi">Suntik Vaksin / Imunisasi</option>
                                    <option value="pemeriksaan">Pemeriksaan Kandungan</option>
                                    <option value="konseling">Penyuluhan & Edukasi</option>
                                    <option value="lainnya">Lain-Lain</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow"></i>
                            </div>
                        </div>
                        <div>
                            <label class="med-label text-cyan-600">Target Sasaran Penerima Notif <span class="text-rose-500">*</span></label>
                            <div class="input-wrapper">
                                <i class="fas fa-bullseye input-icon text-cyan-500"></i>
                                <select name="target_peserta" required class="med-input select-custom border-cyan-100 bg-cyan-50/30">
                                    <option value="semua">Semua Elemen Warga</option>
                                    <option value="balita">Khusus Ibu & Balita</option>
                                    <option value="ibu_hamil">Khusus Ibu Hamil (KIA)</option>
                                </select>
                                <i class="fas fa-chevron-down select-arrow text-cyan-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="med-label">Pesan Tambahan (Opsional)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-comment-medical input-icon" style="top: 24px;"></i>
                            <textarea name="deskripsi" rows="3" class="med-input resize-none" style="padding-top: 16px;" placeholder="Misal: Wajib membawa Buku KIA/KMS..."></textarea>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" id="btnSubmit" class="w-full sm:w-auto px-10 py-5 bg-gradient-to-r from-cyan-600 to-blue-700 text-white font-black text-[13px] uppercase tracking-widest rounded-[18px] hover:shadow-[0_20px_40px_rgba(6,182,212,0.4)] hover:-translate-y-1 transition-all shadow-xl">
                            <i class="fas fa-paper-plane text-lg mr-2"></i> Terbitkan & Broadcast
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('formJadwal').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        const loader = document.getElementById('smoothLoader');
        btn.innerHTML = '<i class="fas fa-circle-notch fa-spin text-lg mr-2"></i> Mengirim Notifikasi...';
        btn.classList.add('opacity-75', 'cursor-wait');
        if(loader) {
            loader.style.display = 'flex';
            loader.offsetHeight; 
            loader.classList.remove('opacity-0', 'pointer-events-none');
            loader.classList.add('opacity-100');
        }
    });
</script>
@endpush