

<?php $__env->startSection('title', 'Detail Riwayat Imunisasi'); ?>
<?php $__env->startSection('page-name', 'Sertifikat Vaksinasi'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .animate-slide-up { opacity: 0; animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .receipt-edge { background-image: radial-gradient(#f8fafc 4px, transparent 4px); background-size: 16px 16px; background-position: -8px -8px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $pasien = $imunisasi->kunjungan->pasien ?? null;
    $nama = $pasien->nama_lengkap ?? 'Anonim';
    $nik = $pasien->nik ?? '-';
    $kategori = class_basename($imunisasi->kunjungan->pasien_type ?? '');
?>

<div class="max-w-3xl mx-auto animate-slide-up pb-10">
    
    <div class="mb-6 flex items-center justify-between">
        <a href="<?php echo e(route('bidan.imunisasi.index')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold text-[12px] uppercase tracking-widest rounded-xl hover:bg-slate-50 hover:text-cyan-600 transition-colors shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Register
        </a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 border border-slate-800 text-white font-bold text-[12px] uppercase tracking-widest rounded-xl hover:bg-black transition-colors shadow-sm">
            <i class="fas fa-print"></i> Cetak Dokumen
        </button>
    </div>

    
    <div class="bg-white rounded-[32px] shadow-[0_15px_50px_rgba(0,0,0,0.06)] border border-slate-100 overflow-hidden relative print:shadow-none print:border-none print:w-full">
        
        <div class="p-8 md:p-10 bg-gradient-to-r from-cyan-600 to-blue-700 flex items-center justify-between gap-4 relative overflow-hidden">
            <i class="fas fa-certificate absolute -right-10 -bottom-10 text-9xl text-white/10 rotate-12"></i>
            <div class="flex items-center gap-5 relative z-10">
                <div class="w-16 h-16 rounded-[20px] bg-white border border-slate-200 text-cyan-600 flex items-center justify-center text-3xl shadow-sm">
                    <i class="fas fa-shield-virus"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight font-poppins">Bukti Imunisasi Medis</h2>
                    <p class="text-[12px] font-bold text-cyan-100 mt-1 uppercase tracking-widest">ID Rekam: IMU-<?php echo e(str_pad($imunisasi->id, 5, '0', STR_PAD_LEFT)); ?></p>
                </div>
            </div>
            <div class="hidden sm:flex relative z-10 bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl border border-white/30 text-white items-center gap-2">
                <i class="fas fa-check-circle"></i> <span class="text-[11px] font-black uppercase tracking-widest">Tervalidasi</span>
            </div>
        </div>

        <div class="p-8 md:p-10">
            
            <div class="mb-8">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                    <i class="fas fa-user-check text-cyan-400"></i> Identitas Penerima Vaksin
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-slate-50 rounded-2xl p-6 border border-slate-100">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap</p>
                        <p class="text-[16px] font-black text-slate-800 font-poppins"><?php echo e($nama); ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Kategori / NIK</p>
                        <p class="text-[14px] font-bold text-slate-700"><span class="bg-white px-2 py-0.5 rounded border border-slate-200 shadow-sm mr-2"><?php echo e($kategori); ?></span> <?php echo e($nik); ?></p>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                    <i class="fas fa-syringe text-cyan-400"></i> Rincian Tindakan Medis
                </h3>
                <div class="bg-white border-2 border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                    <table class="w-full text-left text-[13px]">
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6 font-black text-slate-500 w-2/5 bg-slate-50/50 uppercase tracking-wide text-[10px]">Program Layanan</td>
                                <td class="py-4 px-6 font-bold text-slate-800"><?php echo e($imunisasi->jenis_imunisasi); ?></td>
                            </tr>
                            <tr class="hover:bg-cyan-50 transition-colors">
                                <td class="py-4 px-6 font-black text-slate-500 bg-slate-50/50 uppercase tracking-wide text-[10px]">Vaksin Diberikan</td>
                                <td class="py-4 px-6 font-black text-cyan-600 text-[16px] font-poppins"><?php echo e($imunisasi->vaksin); ?></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6 font-black text-slate-500 bg-slate-50/50 uppercase tracking-wide text-[10px]">Tanggal Pelaksanaan</td>
                                <td class="py-4 px-6 font-bold text-slate-800"><i class="far fa-calendar-alt text-slate-400 mr-2"></i><?php echo e(\Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->translatedFormat('l, d F Y')); ?></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6 font-black text-slate-500 bg-slate-50/50 uppercase tracking-wide text-[10px]">Bidan Penanggung Jawab</td>
                                <td class="py-4 px-6 font-bold text-slate-800"><i class="fas fa-user-md text-slate-400 mr-2"></i><?php echo e($imunisasi->kunjungan->petugas->name ?? 'Bidan Desa'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-100 pb-2">
                    <i class="fas fa-file-medical-alt text-amber-400"></i> Observasi Klinis (KIPI)
                </h3>
                <?php $hasKipi = !empty($imunisasi->keterangan) && $imunisasi->keterangan != '-'; ?>
                <div class="p-6 rounded-2xl border-2 <?php echo e($hasKipi ? 'border-amber-200 bg-amber-50/50 text-slate-800' : 'border-emerald-100 bg-emerald-50/50 text-emerald-700'); ?>">
                    <div class="flex items-start gap-4">
                        <i class="fas <?php echo e($hasKipi ? 'fa-exclamation-triangle text-amber-500' : 'fa-check-circle text-emerald-500'); ?> text-2xl mt-0.5"></i>
                        <div>
                            <p class="text-[12px] font-black uppercase tracking-widest <?php echo e($hasKipi ? 'text-amber-600' : 'text-emerald-600'); ?> mb-1">
                                <?php echo e($hasKipi ? 'Catatan Keluhan Pasca Imunisasi' : 'Aman Terkendali'); ?>

                            </p>
                            <p class="text-[14px] font-medium leading-relaxed">
                                <?php echo e($hasKipi ? $imunisasi->keterangan : 'Tidak ditemukan keluhan klinis (KIPI) pasca pemberian vaksin pada pasien ini.'); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="h-4 w-full receipt-edge opacity-50"></div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/imunisasi/show.blade.php ENDPATH**/ ?>