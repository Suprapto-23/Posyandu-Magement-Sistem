
<?php $__env->startSection('title', 'Dashboard Bidan'); ?>
<?php $__env->startSection('page-name', 'Dashboard Monitoring'); ?>

<?php $__env->startSection('content'); ?>
<style>
/* ── DASHBOARD BIDAN PREMIUM STYLES ── */
@keyframes pulseRing { 0%, 100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4) } 50% { box-shadow: 0 0 0 6px rgba(255, 255, 255, 0) } }
.animate-slide-up { opacity: 0; animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
@keyframes slideUpFade { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

/* HERO - Medical Cyan/Blue Premium */
.hero { background: linear-gradient(135deg, #0891b2 0%, #0369a1 100%); border-radius: 32px; padding: 48px; margin-bottom: 32px; display: flex; align-items: center; justify-content: space-between; gap: 24px; position: relative; overflow: hidden; box-shadow: 0 20px 40px -10px rgba(8, 145, 178, 0.3); }
.hero::before { content: ''; position: absolute; inset: 0; background-image: radial-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px); background-size: 30px 30px; opacity: 0.5; pointer-events: none; }
.hero-glow-1 { position: absolute; top: -100px; right: 50px; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%); pointer-events: none; }
.hero-glow-2 { position: absolute; bottom: -80px; left: -50px; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(14, 165, 233, 0.2) 0%, transparent 70%); pointer-events: none; }

.hero-txt { position: relative; z-index: 1; }
.hero-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.3); color: #fff; font-size: 11px; font-weight: 800; padding: 6px 16px; border-radius: 50px; margin-bottom: 20px; letter-spacing: 1px; text-transform: uppercase; shadow-sm}
.hero-badge .pulse { width: 8px; height: 8px; border-radius: 50%; background: #fff; animation: pulseRing 2s infinite; }
.hero-title { font-size: 36px; font-weight: 900; color: #fff; line-height: 1.2; margin-bottom: 12px; letter-spacing: -0.5px; }
.hero-desc { font-size: 15px; color: rgba(255, 255, 255, 0.9); margin-bottom: 0; max-width: 500px; font-weight: 500; line-height: 1.6; }

/* STATS CARDS */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px; }
.stat-card { background: #fff; border: 1px solid rgba(226, 232, 240, 0.6); border-radius: 28px; padding: 26px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); transition: all 0.3s ease; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between;}
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06); border-color: #cbd5e1; }

.stat-card.alert-card { background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); color: white; border: none; box-shadow: 0 12px 30px rgba(225, 29, 72, 0.25); }
.stat-card.alert-card .stat-label { color: rgba(255,255,255,0.85); }
.stat-card.alert-card .stat-value { color: white; }

.stat-icon { width: 52px; height: 52px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 16px; }
.stat-label { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; font-family: 'Poppins', sans-serif;}
.stat-value { font-size: 36px; font-weight: 900; color: #0f172a; line-height: 1; letter-spacing: -1px; }

@media(max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 900px) { .hero { padding: 36px 28px; border-radius: 28px; } .hero-title { font-size: 28px; } }
@media(max-width: 580px) { .stats-grid { grid-template-columns: 1fr; gap: 16px; } .hero { border-radius: 24px; padding: 28px 20px; } .hero-title { font-size: 24px; } .stat-value { font-size: 32px; } }
</style>

<div class="animate-slide-up">
    
    <div class="hero">
        <div class="hero-glow-1"></div><div class="hero-glow-2"></div>
        <div class="hero-txt">
            <div class="hero-badge"><span class="pulse"></span> Monitor Medis Aktif</div>
            <h1 class="hero-title">Halo Bidan <span class="text-cyan-200"><?php echo e(Str::words(Auth::user()->name, 1, '')); ?></span> 👋</h1>
            <p class="hero-desc">Pantau status kesehatan warga, validasi hasil pemeriksaan dari kader, dan pastikan tidak ada indikasi gizi buruk yang terlewat.</p>
        </div>
        <div class="hidden md:flex items-center justify-center w-44 h-44 bg-white/10 backdrop-blur-2xl border border-white/30 rounded-[2.5rem] shadow-2xl relative z-10 rotate-6 hover:rotate-0 transition-transform duration-500">
            <i class="fas fa-stethoscope text-7xl text-white drop-shadow-[0_10px_20px_rgba(0,0,0,0.2)]"></i>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card border-b-[6px] border-b-rose-500">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="stat-label">Risiko Stunting</div>
                    <div class="stat-value"><?php echo e($balitaStunting ?? 0); ?></div>
                </div>
                <div class="stat-icon bg-rose-100 text-rose-600 mb-0"><i class="fas fa-baby"></i></div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 mt-2">
                <div class="bg-rose-500 h-2 rounded-full" style="width: <?php echo e($totalBalita > 0 ? ($balitaStunting/$totalBalita)*100 : 0); ?>%"></div>
            </div>
            <p class="text-[11px] text-slate-500 mt-2.5 font-bold">Dari total <?php echo e($totalBalita ?? 0); ?> balita terdaftar</p>
        </div>

        <div class="stat-card border-b-[6px] border-b-amber-500">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <div class="stat-label">Lansia Hipertensi</div>
                    <div class="stat-value"><?php echo e($lansiaHipertensi ?? 0); ?></div>
                </div>
                <div class="stat-icon bg-amber-100 text-amber-600 mb-0"><i class="fas fa-heartbeat"></i></div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 mt-2">
                <div class="bg-amber-500 h-2 rounded-full" style="width: <?php echo e($totalLansia > 0 ? ($lansiaHipertensi/$totalLansia)*100 : 0); ?>%"></div>
            </div>
            <p class="text-[11px] text-slate-500 mt-2.5 font-bold">Dari total <?php echo e($totalLansia ?? 0); ?> lansia terdaftar</p>
        </div>

        <div class="stat-card <?php echo e(($jumlahBelumValidasi ?? 0) > 0 ? 'alert-card' : 'border-b-[6px] border-b-cyan-500'); ?>">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <div class="stat-label">Antrian Validasi</div>
                    <div class="stat-value"><?php echo e($jumlahBelumValidasi ?? 0); ?></div>
                </div>
                <div class="stat-icon <?php echo e(($jumlahBelumValidasi ?? 0) > 0 ? 'bg-white/20 text-white' : 'bg-cyan-100 text-cyan-600'); ?> mb-0">
                    <i class="fas fa-file-signature"></i>
                </div>
            </div>
            <a href="#antrian-section" class="mt-4 w-full py-2.5 flex items-center justify-center gap-2 text-[13px] font-bold rounded-xl transition-colors <?php echo e(($jumlahBelumValidasi ?? 0) > 0 ? 'bg-white/20 hover:bg-white/30 text-white shadow-sm' : 'bg-cyan-50 hover:bg-cyan-100 text-cyan-700'); ?>">
                Periksa Sekarang <i class="fas fa-arrow-down"></i>
            </a>
        </div>

        <div class="stat-card border-b-[6px] border-b-indigo-500">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <div class="stat-label">Agenda Berikutnya</div>
                    <?php if($jadwalBerikutnya): ?>
                        <div class="text-2xl font-black text-slate-800 tracking-tight"><?php echo e(\Carbon\Carbon::parse($jadwalBerikutnya->tanggal)->format('d M Y')); ?></div>
                    <?php else: ?>
                        <div class="text-xl font-black text-slate-400">Belum Ada</div>
                    <?php endif; ?>
                </div>
                <div class="stat-icon bg-indigo-100 text-indigo-600 mb-0"><i class="fas fa-calendar-day"></i></div>
            </div>
            <p class="text-[13px] text-slate-600 font-bold mt-2 truncate"><i class="fas fa-map-marker-alt text-slate-400 mr-1"></i> <?php echo e($jadwalBerikutnya->judul ?? 'Buat jadwal baru'); ?></p>
        </div>
    </div>

    <div id="antrian-section" class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_10px_40px_rgb(0,0,0,0.03)] mb-8 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
            <div>
                <h3 class="text-lg font-black text-slate-800 flex items-center gap-3 font-poppins">
                    <div class="w-10 h-10 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center"><i class="fas fa-user-md"></i></div>
                    Antrian Validasi Medis
                </h3>
                <p class="text-[13px] font-semibold text-slate-500 mt-1">Data pengukuran dari kader yang membutuhkan verifikasi dan diagnosa klinis dari Anda.</p>
            </div>
            <?php if(count($antrianPemeriksaan ?? []) > 0): ?>
                <a href="<?php echo e(route('bidan.pemeriksaan.index')); ?>?status=pending" class="px-5 py-2.5 bg-white border border-slate-200 text-cyan-700 text-sm font-bold rounded-xl hover:bg-cyan-50 transition-colors shadow-sm">
                    Lihat Semua Antrian
                </a>
            <?php else: ?>
                <span class="px-4 py-2 bg-emerald-50 text-emerald-700 text-sm font-bold rounded-xl border border-emerald-200 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Antrian Bersih
                </span>
            <?php endif; ?>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100">
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Waktu Input</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Pasien & Kategori</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest">Hasil Ukur Kader</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-5 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php $__empty_1 = true; $__currentLoopData = $antrianPemeriksaan ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-8 py-5">
                            <p class="font-bold text-slate-800 text-[15px]"><?php echo e($item->created_at->format('H:i')); ?> <span class="text-xs text-slate-500">WIB</span></p>
                            <p class="text-[11px] font-bold text-cyan-600 mt-0.5"><?php echo e($item->created_at->diffForHumans()); ?></p>
                        </td>
                        <td class="px-8 py-5">
                            <p class="font-black text-slate-800 text-[15px]"><?php echo e($item->nama_pasien); ?></p>
                            <span class="inline-block mt-1 px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-lg uppercase tracking-widest border border-slate-200"><?php echo e($item->kategori_pasien); ?></span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-wrap gap-2 text-[12px] font-bold text-slate-600">
                                <span class="bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">BB: <span class="font-black text-slate-800"><?php echo e($item->berat_badan); ?> kg</span></span>
                                <span class="bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">TB: <span class="font-black text-slate-800"><?php echo e($item->tinggi_badan); ?> cm</span></span>
                                <?php if($item->tekanan_darah): ?>
                                    <span class="bg-rose-50 border border-rose-100 text-rose-700 px-3 py-1.5 rounded-lg shadow-sm">TD: <span class="font-black"><?php echo e($item->tekanan_darah); ?></span></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 text-[11px] font-black border border-amber-200 tracking-wide uppercase"><i class="fas fa-hourglass-half animate-pulse"></i> Menunggu</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="<?php echo e(route('bidan.pemeriksaan.show', $item->id)); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-[13px] font-black rounded-xl hover:from-cyan-600 hover:to-blue-700 shadow-[0_8px_16px_rgba(8,145,178,0.25)] transition-all transform hover:-translate-y-1">
                                <i class="fas fa-stethoscope"></i> Diagnosa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-16 text-center">
                            <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 mx-auto mb-4 text-4xl border-2 border-emerald-100 shadow-sm"><i class="fas fa-shield-check"></i></div>
                            <h4 class="font-black text-slate-800 text-lg font-poppins">Tidak ada antrian data</h4>
                            <p class="text-[13px] font-medium text-slate-500 mt-1">Kerja bagus! Semua data pemeriksaan telah Anda validasi hari ini.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-[32px] border border-slate-200/80 shadow-[0_10px_40px_rgb(0,0,0,0.03)] p-8">
            <h3 class="text-base font-black text-slate-800 flex items-center gap-3 mb-8 font-poppins">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center"><i class="fas fa-chart-pie text-sm"></i></div>
                Status Gizi Balita
            </h3>
            <div class="relative h-56 mb-4 flex justify-center">
                <canvas id="chartGizi"></canvas>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-[32px] border border-slate-200/80 shadow-[0_10px_40px_rgb(0,0,0,0.03)] p-8">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-base font-black text-slate-800 flex items-center gap-3 font-poppins">
                    <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center"><i class="fas fa-chart-line text-sm"></i></div>
                    Tren Kunjungan Posyandu
                </h3>
            </div>
            <div class="relative h-56">
                <canvas id="chartKunjungan"></canvas>
            </div>
        </div>
        
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Konfigurasi Font Default Chart.js
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    // CHART 1: GIZI BALITA (Doughnut)
    const ctxGizi = document.getElementById('chartGizi');
    if (ctxGizi) {
        new Chart(ctxGizi, {
            type: 'doughnut',
            data: {
                labels: ['Normal', 'Kurang', 'Stunting', 'Obesitas'],
                datasets: [{
                    data: [
                        <?php echo e($chartGizi['normal'] ?? 0); ?>,
                        <?php echo e($chartGizi['kurang'] ?? 0); ?>,
                        <?php echo e($chartGizi['stunting'] ?? 0); ?>,
                        <?php echo e($chartGizi['lebih'] ?? 0); ?>

                    ],
                    backgroundColor: ['#10b981','#f59e0b','#f43f5e','#0ea5e9'],
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 8,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '75%',
                plugins: { 
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: {size: 12, weight: 'bold'} } },
                    tooltip: { backgroundColor: '#0f172a', padding: 14, borderRadius: 12, titleFont: {size: 13}, bodyFont: {size: 14, weight: 'bold'} }
                }
            }
        });
    }

    // CHART 2: TREN KUNJUNGAN (Line Area)
    const ctxKunjungan = document.getElementById('chartKunjungan');
    if (ctxKunjungan) {
        const kCtx = ctxKunjungan.getContext('2d');
        const gradient = kCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(8, 145, 178, 0.3)'); 
        gradient.addColorStop(1, 'rgba(8, 145, 178, 0)');

        new Chart(ctxKunjungan, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labelBulan ?? []); ?>,
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: <?php echo json_encode($dataKunjungan ?? []); ?>,
                    borderColor: '#0891b2',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4, // Membuat garis melengkung mulus (smooth curve)
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#0891b2',
                    pointBorderWidth: 3
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: { backgroundColor: '#0f172a', padding: 14, borderRadius: 12, displayColors: false, titleFont: {size: 13}, bodyFont: {size: 15, weight: 'bold'} }
                },
                scales: {
                    y: { beginAtZero: true, border: {display: false}, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { precision: 0, font: {weight: 'bold'} } },
                    x: { border: {display: false}, grid: { display: false }, ticks: { font: {weight: 'bold'} } }
                },
                interaction: { mode: 'index', intersect: false }
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.bidan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/bidan/dashboard.blade.php ENDPATH**/ ?>