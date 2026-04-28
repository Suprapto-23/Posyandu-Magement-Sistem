

<?php $__env->startSection('title', 'Dashboard Operasional'); ?>
<?php $__env->startSection('page-name', 'Beranda Utama'); ?>

<?php $__env->startPush('styles'); ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

<style>
/* ============================================================
   CSS VARIABLES — NEXUS DESIGN SYSTEM
   ============================================================ */
:root {
    --nexus-bg: #f4f6fb;
    --nexus-card: #ffffff;
    --nexus-border: rgba(215, 222, 240, 0.7);
    --nexus-indigo: #4f46e5;
    --nexus-indigo-light: #eef2ff;
    --nexus-text-primary: #0f172a;
    --nexus-text-muted: #64748b;
    --nexus-radius-xl: 24px;
    --nexus-radius-lg: 18px;
    --nexus-shadow-card: 0 2px 20px -4px rgba(15,23,42,0.06), 0 1px 4px -1px rgba(15,23,42,0.04);
    --nexus-shadow-hover: 0 16px 40px -8px rgba(79,70,229,0.14), 0 4px 12px -2px rgba(15,23,42,0.06);
    --font-display: 'Outfit', sans-serif;
    --font-body: 'DM Sans', sans-serif;
}

/* ============================================================
   BASE RESETS
   ============================================================ */
body { font-family: var(--font-body); background: var(--nexus-bg); }

/* ============================================================
   ENTRANCE ANIMATIONS
   ============================================================ */
@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(22px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.94); }
    to   { opacity: 1; transform: scale(1); }
}
@keyframes numberCount {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

.anim-1 { animation: fadeSlideUp 0.65s cubic-bezier(0.22, 1, 0.36, 1) 0.05s both; }
.anim-2 { animation: fadeSlideUp 0.65s cubic-bezier(0.22, 1, 0.36, 1) 0.15s both; }
.anim-3 { animation: fadeSlideUp 0.65s cubic-bezier(0.22, 1, 0.36, 1) 0.25s both; }
.anim-4 { animation: fadeSlideUp 0.65s cubic-bezier(0.22, 1, 0.36, 1) 0.35s both; }

/* ============================================================
   NEXUS CARD
   ============================================================ */
.nx-card {
    background: var(--nexus-card);
    border: 1px solid var(--nexus-border);
    border-radius: var(--nexus-radius-xl);
    box-shadow: var(--nexus-shadow-card);
    transition: box-shadow 0.3s ease, transform 0.3s ease, border-color 0.3s ease;
    font-family: var(--font-body);
}
.nx-card:hover {
    box-shadow: var(--nexus-shadow-hover);
    border-color: rgba(79, 70, 229, 0.2);
    transform: translateY(-3px);
}

/* ============================================================
   HERO SECTION
   ============================================================ */
.hero-gradient {
    background: linear-gradient(135deg, #3730a3 0%, #4f46e5 45%, #6366f1 100%);
    border-radius: 28px;
    position: relative;
    overflow: hidden;
}
.hero-gradient::before {
    content: '';
    position: absolute; inset: 0;
    background: 
        radial-gradient(ellipse 80% 80% at 80% -20%, rgba(99,102,241,0.5) 0%, transparent 60%),
        radial-gradient(ellipse 50% 50% at 10% 110%, rgba(55,48,163,0.6) 0%, transparent 60%);
    pointer-events: none;
}

/* Noise texture overlay */
.hero-gradient::after {
    content: '';
    position: absolute; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
    opacity: 0.3;
    pointer-events: none;
}

/* Decorative grid lines on hero */
.hero-grid {
    position: absolute; inset: 0;
    background-image: 
        linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 40px 40px;
    pointer-events: none;
}

/* ============================================================
   CUTE CHARACTER ANIMATION (Pure CSS — no Lottie needed!)
   ============================================================ */
.mascot-wrapper {
    position: relative;
    width: 180px;
    height: 180px;
    flex-shrink: 0;
}

/* Glowing orbit ring */
.mascot-orbit {
    position: absolute; inset: -10px;
    border-radius: 50%;
    border: 2px dashed rgba(255,255,255,0.2);
    animation: orbitSpin 12s linear infinite;
}
.mascot-orbit::before {
    content: '✦';
    position: absolute; top: 6px; left: 50%;
    transform: translateX(-50%);
    font-size: 14px; color: rgba(255,255,255,0.7);
}
@keyframes orbitSpin { to { transform: rotate(360deg); } }

/* The character itself */
.mascot-face {
    width: 140px; height: 140px;
    background: linear-gradient(145deg, #ffffff 0%, #e8edff 100%);
    border-radius: 50%;
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    box-shadow: 0 20px 60px rgba(0,0,0,0.25), inset 0 -4px 20px rgba(79,70,229,0.15);
    animation: mascotFloat 3.5s ease-in-out infinite;
    display: flex; align-items: center; justify-content: center;
    font-size: 64px;
    user-select: none;
}
@keyframes mascotFloat {
    0%, 100% { transform: translate(-50%, -50%) translateY(0px) rotate(-2deg); }
    50%       { transform: translate(-50%, -50%) translateY(-12px) rotate(2deg); }
}

/* Sparkles around mascot */
.spark {
    position: absolute;
    width: 8px; height: 8px;
    background: #fcd34d;
    border-radius: 50%;
    animation: sparkle 2.5s ease-in-out infinite;
}
.spark-1 { top: 10%; right: 15%; animation-delay: 0s; }
.spark-2 { top: 60%; right: 2%;  animation-delay: 0.7s; width: 6px; height: 6px; background: #a5f3fc; }
.spark-3 { top: 20%; left: 8%;   animation-delay: 1.4s; width: 5px; height: 5px; background: #c084fc; }
.spark-4 { bottom: 10%; left: 20%; animation-delay: 0.3s; width: 7px; height: 7px; background: #fb7185; }

@keyframes sparkle {
    0%, 100% { opacity: 0; transform: scale(0); }
    50%       { opacity: 1; transform: scale(1); }
}

/* ============================================================
   STAT CARDS
   ============================================================ */
.stat-icon-wrap {
    width: 52px; height: 52px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
    flex-shrink: 0;
}
.nx-card:hover .stat-icon-wrap {
    transform: scale(1.12) rotate(6deg);
}

.stat-number {
    font-family: var(--font-display);
    font-size: 40px;
    font-weight: 800;
    line-height: 1;
    color: var(--nexus-text-primary);
    transition: color 0.3s ease;
    animation: numberCount 0.8s cubic-bezier(0.22, 1, 0.36, 1) 0.4s both;
}
.nx-card:hover .stat-number { color: var(--nexus-indigo); }

.stat-badge {
    font-family: var(--font-display);
    font-size: 11px; font-weight: 700;
    padding: 4px 10px;
    border-radius: 30px;
    letter-spacing: 0.02em;
}
.badge-up   { background: #dcfce7; color: #16a34a; }
.badge-zero { background: #f1f5f9; color: #94a3b8; }

/* Dark stat card (5th card) */
.stat-dark {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #3730a3 100%) !important;
    border: 1px solid rgba(99,102,241,0.3) !important;
}
.stat-dark::before {
    content: '';
    position: absolute; inset: 0; border-radius: var(--nexus-radius-xl);
    background: radial-gradient(ellipse at 80% 120%, rgba(99,102,241,0.4) 0%, transparent 60%);
    pointer-events: none;
}

/* ============================================================
   CHART SECTION
   ============================================================ */
.chart-legend-dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ============================================================
   ACTIVITY LOG
   ============================================================ */
.log-item {
    display: flex; align-items: flex-start; gap: 14px;
    padding: 14px 16px;
    background: #fff;
    border: 1px solid rgba(226,232,240,0.8);
    border-radius: var(--nexus-radius-lg);
    transition: all 0.25s ease;
}
.log-item:hover {
    background: #fafbff;
    border-color: rgba(79,70,229,0.2);
    box-shadow: 0 4px 16px -4px rgba(79,70,229,0.1);
    transform: translateX(3px);
}
.log-avatar {
    width: 44px; height: 44px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}
.log-item:hover .log-avatar { transform: scale(1.08); }

/* ============================================================
   JADWAL CARDS
   ============================================================ */
.jadwal-item {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 16px;
    border-radius: var(--nexus-radius-lg);
    border: 1px solid var(--nexus-border);
    background: #fff;
    transition: all 0.25s ease;
}
.jadwal-item:hover {
    border-color: rgba(79,70,229,0.25);
    box-shadow: 0 6px 20px -4px rgba(79,70,229,0.12);
    transform: translateY(-2px);
}

/* ============================================================
   SCROLLBAR
   ============================================================ */
.nx-scroll::-webkit-scrollbar { width: 5px; }
.nx-scroll::-webkit-scrollbar-track { background: transparent; }
.nx-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
.nx-scroll::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

/* ============================================================
   CLOCK PILL
   ============================================================ */
.clock-pill {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 10px 18px;
    background: rgba(255,255,255,0.12);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

/* ============================================================
   EMPTY STATE
   ============================================================ */
.empty-state-icon {
    font-size: 52px;
    animation: mascotFloat 3s ease-in-out infinite;
    display: inline-block;
}

/* ============================================================
   SECTION TITLE
   ============================================================ */
.nx-section-title {
    font-family: var(--font-display);
    font-size: 17px;
    font-weight: 700;
    color: var(--nexus-text-primary);
}
.nx-section-sub {
    font-size: 13px;
    color: var(--nexus-text-muted);
    margin-top: 2px;
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 768px) {
    .hero-gradient { padding: 28px 24px; border-radius: 22px; }
    .stat-number { font-size: 32px; }
    .mascot-wrapper { width: 130px; height: 130px; }
    .mascot-face { width: 100px; height: 100px; font-size: 48px; }
}

/* Progress bar for visit indicator */
.progress-bar-track {
    height: 6px;
    background: #f1f5f9;
    border-radius: 10px;
    overflow: hidden;
    margin-top: 8px;
}
.progress-bar-fill {
    height: 100%;
    border-radius: 10px;
    background: linear-gradient(90deg, #4f46e5, #818cf8);
    transition: width 1.2s cubic-bezier(0.22, 1, 0.36, 1);
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $jam = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
    $sapaan = $jam < 11 ? 'Selamat Pagi' : ($jam < 15 ? 'Selamat Siang' : ($jam < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $emoji  = $jam < 11 ? '🌤️' : ($jam < 15 ? '☀️' : ($jam < 18 ? '🌅' : '🌙'));
    $namaDepan = explode(' ', Auth::user()->name)[0] ?? 'Kader Hebat';
?>

<div class="space-y-6 pb-14 max-w-[1600px] mx-auto" style="font-family: var(--font-body);">

    
    <div class="hero-gradient p-8 md:p-10 anim-1" style="box-shadow: 0 20px 60px -10px rgba(79,70,229,0.35);">
        <div class="hero-grid"></div>

        <div class="relative z-10 flex flex-col xl:flex-row items-center justify-between gap-8">

            
            <div class="flex-1 text-center xl:text-left">
                
                
                <div class="clock-pill mb-6 mx-auto xl:mx-0" style="width: fit-content;">
                    <span class="relative flex h-2.5 w-2.5 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-400"></span>
                    </span>
                    <span id="realtime-clock" class="text-white text-[13px] font-semibold tracking-wide" style="font-family: var(--font-display);">
                        Memuat...
                    </span>
                </div>

                <h1 style="font-family: var(--font-display); font-size: clamp(30px, 4vw, 48px); font-weight: 900; color: #fff; line-height: 1.1; letter-spacing: -0.02em; margin-bottom: 14px;">
                    <?php echo e($sapaan); ?>, <?php echo e($namaDepan); ?>! <?php echo e($emoji); ?>

                </h1>
                
                <p style="color: rgba(199,210,254,0.9); font-size: 15px; line-height: 1.7; max-width: 520px; font-weight: 500;">
                    Selamat datang kembali di pusat komando Posyandu. Bersama-sama kita wujudkan generasi yang sehat, cerdas, dan kuat. 💪
                </p>

                
                <div class="flex flex-wrap gap-3 mt-6 justify-center xl:justify-start">
                    <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); border-radius: 30px; padding: 7px 16px; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-users" style="color: #a5f3fc; font-size: 13px;"></i>
                        <span style="color: #fff; font-size: 13px; font-weight: 700; font-family: var(--font-display);">
                            <?php echo e(($stats['total_balita'] ?? 0) + ($stats['total_remaja'] ?? 0) + ($stats['total_lansia'] ?? 0) + ($stats['total_ibu_hamil'] ?? 0)); ?> Total Warga
                        </span>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); border-radius: 30px; padding: 7px 16px; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-calendar-day" style="color: #fcd34d; font-size: 13px;"></i>
                        <span style="color: #fff; font-size: 13px; font-weight: 700; font-family: var(--font-display);">
                            <?php echo e($stats['imunisasi_hari_ini'] ?? 0); ?> Imunisasi Hari Ini
                        </span>
                    </div>
                    <?php if(($stats['jadwal_hari_ini'] ?? 0) > 0): ?>
                    <div style="background: rgba(251,191,36,0.25); backdrop-filter: blur(10px); border: 1px solid rgba(251,191,36,0.4); border-radius: 30px; padding: 7px 16px; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-bolt" style="color: #fcd34d; font-size: 13px;"></i>
                        <span style="color: #fef9c3; font-size: 13px; font-weight: 700; font-family: var(--font-display);">
                            <?php echo e($stats['jadwal_hari_ini']); ?> Agenda Aktif
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="shrink-0 flex flex-col items-center gap-5">
                
                
                <div class="mascot-wrapper">
                    <div class="mascot-orbit"></div>
                    <div class="mascot-face">🩺</div>
                    <div class="spark spark-1"></div>
                    <div class="spark spark-2"></div>
                    <div class="spark spark-3"></div>
                    <div class="spark spark-4"></div>
                </div>

                <a href="<?php echo e(route('kader.pemeriksaan.index')); ?>"
                   style="display: inline-flex; align-items: center; gap: 10px; padding: 14px 26px; background: #fff; border-radius: 18px; color: #4f46e5; font-family: var(--font-display); font-size: 15px; font-weight: 800; text-decoration: none; box-shadow: 0 12px 30px rgba(0,0,0,0.18); transition: all 0.3s cubic-bezier(0.22,1,0.36,1); border: 1px solid rgba(255,255,255,0.5);"
                   onmouseover="this.style.transform='translateY(-4px) scale(1.03)'; this.style.boxShadow='0 20px 40px rgba(0,0,0,0.22)'"
                   onmouseout="this.style.transform=''; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.18)'">
                    <i class="fas fa-stethoscope"></i>
                    Mulai Pelayanan
                    <span style="width: 30px; height: 30px; background: #eef2ff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-right" style="font-size: 11px;"></i>
                    </span>
                </a>
            </div>

        </div>
    </div>

    
    <?php
        $statCards = [
            [
                'label' => 'Total Balita',
                'value' => $stats['total_balita'] ?? 0,
                'bulan' => $pendaftaran_bulan_ini['balita'] ?? 0,
                'icon'  => 'fa-baby',
                'color' => '#ef4444',
                'bg'    => '#fff1f2',
            ],
            [
                'label' => 'Ibu Hamil',
                'value' => $stats['total_ibu_hamil'] ?? 0,
                'bulan' => $pendaftaran_bulan_ini['ibu_hamil'] ?? 0,
                'icon'  => 'fa-female',
                'color' => '#ec4899',
                'bg'    => '#fdf2f8',
            ],
            [
                'label' => 'Remaja',
                'value' => $stats['total_remaja'] ?? 0,
                'bulan' => $pendaftaran_bulan_ini['remaja'] ?? 0,
                'icon'  => 'fa-user-graduate',
                'color' => '#0ea5e9',
                'bg'    => '#f0f9ff',
            ],
            [
                'label' => 'Lansia',
                'value' => $stats['total_lansia'] ?? 0,
                'bulan' => $pendaftaran_bulan_ini['lansia'] ?? 0,
                'icon'  => 'fa-wheelchair',
                'color' => '#10b981',
                'bg'    => '#f0fdf4',
            ],
        ];
    ?>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-5 anim-2">

        <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="nx-card p-6 flex flex-col justify-between relative overflow-hidden" style="min-height: 170px;">
            
            <div style="position: absolute; bottom: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; background: <?php echo e($card['bg']); ?>; opacity: 0.7; pointer-events: none;"></div>
            
            <div class="flex justify-between items-start mb-4">
                <div class="stat-icon-wrap" style="background: <?php echo e($card['bg']); ?>; color: <?php echo e($card['color']); ?>;">
                    <i class="fas <?php echo e($card['icon']); ?>"></i>
                </div>
                <span class="stat-badge <?php echo e($card['bulan'] > 0 ? 'badge-up' : 'badge-zero'); ?>">
                    <?php echo e($card['bulan'] > 0 ? '+' : ''); ?><?php echo e($card['bulan']); ?> <span style="font-size: 9px; opacity: 0.7;">BLN</span>
                </span>
            </div>
            <div>
                <p class="stat-number"><?php echo e(number_format($card['value'])); ?></p>
                <p style="font-size: 11px; font-weight: 600; color: var(--nexus-text-muted); text-transform: uppercase; letter-spacing: 0.08em; margin-top: 5px;"><?php echo e($card['label']); ?></p>
                
                
                <div class="progress-bar-track">
                    <?php $maxVal = max(1, $stats['total_balita'] ?? 1, $stats['total_ibu_hamil'] ?? 1, $stats['total_remaja'] ?? 1, $stats['total_lansia'] ?? 1); ?>
                    <div class="progress-bar-fill" style="width: <?php echo e(min(100, ($card['value'] / $maxVal) * 100)); ?>%; background: linear-gradient(90deg, <?php echo e($card['color']); ?>, <?php echo e($card['color']); ?>aa);"></div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <div class="nx-card stat-dark p-6 flex flex-col justify-between relative overflow-hidden col-span-2 lg:col-span-1" style="min-height: 170px;">
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.18); display: flex; align-items: center; justify-content: center; font-size: 22px; color: #a5b4fc; transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);" class="stat-icon-wrap-dark">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <span style="background: rgba(251,191,36,0.2); border: 1px solid rgba(251,191,36,0.35); border-radius: 30px; padding: 4px 10px; font-size: 11px; font-weight: 700; color: #fcd34d; display: flex; align-items: center; gap: 5px;">
                    <i class="fas fa-bolt" style="font-size: 9px;"></i> Aktif
                </span>
            </div>
            <div class="relative z-10">
                <p style="font-family: var(--font-display); font-size: 40px; font-weight: 800; color: #fff; line-height: 1;"><?php echo e($stats['jadwal_hari_ini'] ?? 0); ?></p>
                <p style="font-size: 11px; font-weight: 600; color: rgba(165,180,252,0.8); text-transform: uppercase; letter-spacing: 0.08em; margin-top: 5px;">Agenda Hari Ini</p>
                <div class="progress-bar-track" style="background: rgba(255,255,255,0.08);">
                    <div style="height: 100%; border-radius: 10px; width: <?php echo e(($stats['jadwal_hari_ini'] ?? 0) > 0 ? '100%' : '0%'); ?>; background: linear-gradient(90deg, #818cf8, #c084fc); transition: width 1.2s cubic-bezier(0.22, 1, 0.36, 1);"></div>
                </div>
            </div>
        </div>

    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 anim-3">

        
        <div class="nx-card p-6 flex items-center gap-5">
            <div style="width: 64px; height: 64px; border-radius: 20px; background: linear-gradient(135deg, #4f46e5, #818cf8); display: flex; align-items: center; justify-content: center; font-size: 28px; color: #fff; flex-shrink: 0; box-shadow: 0 8px 24px -4px rgba(79,70,229,0.4);">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="flex-1">
                <p style="font-size: 12px; font-weight: 600; color: var(--nexus-text-muted); text-transform: uppercase; letter-spacing: 0.08em;">Absensi Hari Ini</p>
                <p style="font-family: var(--font-display); font-size: 36px; font-weight: 800; color: var(--nexus-text-primary); line-height: 1; margin-top: 4px;"><?php echo e($stats['jadwal_hari_ini'] ?? 0); ?></p>
                <p style="font-size: 13px; color: var(--nexus-text-muted); margin-top: 6px;">
                    <i class="fas fa-check-circle" style="color: #4f46e5;"></i>
                    Kehadiran tercatat hari ini
                </p>
            </div>
            <a href="<?php echo e(route('kader.absensi.index')); ?>"
               style="display: flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 13px; background: #eef2ff; color: #4f46e5; text-decoration: none; transition: all 0.2s ease; flex-shrink: 0;"
               onmouseover="this.style.background='#4f46e5'; this.style.color='#fff'"
               onmouseout="this.style.background='#eef2ff'; this.style.color='#4f46e5'">
                <i class="fas fa-arrow-right" style="font-size: 14px;"></i>
            </a>
        </div>

        
        <div class="nx-card p-6 flex items-center gap-5">
            <div style="width: 64px; height: 64px; border-radius: 20px; background: linear-gradient(135deg, #10b981, #34d399); display: flex; align-items: center; justify-content: center; font-size: 28px; color: #fff; flex-shrink: 0; box-shadow: 0 8px 24px -4px rgba(16,185,129,0.4);">
                <i class="fas fa-syringe"></i>
            </div>
            <div class="flex-1">
                <p style="font-size: 12px; font-weight: 600; color: var(--nexus-text-muted); text-transform: uppercase; letter-spacing: 0.08em;">Imunisasi Hari Ini</p>
                <p style="font-family: var(--font-display); font-size: 36px; font-weight: 800; color: var(--nexus-text-primary); line-height: 1; margin-top: 4px;"><?php echo e($stats['imunisasi_hari_ini'] ?? 0); ?></p>
                <p style="font-size: 13px; color: var(--nexus-text-muted); margin-top: 6px;">
                    <i class="fas fa-check-circle" style="color: #10b981;"></i>
                    Data imunisasi tercatat
                </p>
            </div>
            <div style="width: 64px; height: 64px; position: relative; flex-shrink: 0;">
                <svg viewBox="0 0 42 42" style="transform: rotate(-90deg); width: 100%; height: 100%;">
                    <circle cx="21" cy="21" r="15.91549431" fill="transparent" stroke="#f1f5f9" stroke-width="4"></circle>
                    <?php $pct = min(100, (($stats['imunisasi_hari_ini'] ?? 0) / max(1, 10)) * 100); ?>
                    <circle cx="21" cy="21" r="15.91549431" fill="transparent" stroke="#10b981" stroke-width="4"
                        stroke-dasharray="<?php echo e($pct); ?> <?php echo e(100 - $pct); ?>" stroke-dashoffset="0" stroke-linecap="round"></circle>
                </svg>
                <span style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-size: 13px; font-weight: 800; color: #10b981;"><?php echo e($stats['imunisasi_hari_ini'] ?? 0); ?></span>
            </div>
        </div>

    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 anim-3">

        
        <div class="nx-card xl:col-span-2 flex flex-col" style="padding: 0; overflow: hidden;">
            
            
            <div style="padding: 24px 28px 20px; border-bottom: 1px solid var(--nexus-border); display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
                <div>
                    <p class="nx-section-title">Trafik Absensi Warga</p>
                    <p class="nx-section-sub">Pergerakan kehadiran selama 7 hari terakhir</p>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <div class="chart-legend-dot" style="background: #4f46e5;"></div>
                        <span style="font-size: 12px; color: var(--nexus-text-muted); font-weight: 500;">Absensi</span>
                    </div>
                    <span style="background: var(--nexus-indigo-light); color: var(--nexus-indigo); font-size: 11px; font-weight: 700; padding: 5px 12px; border-radius: 30px; font-family: var(--font-display); letter-spacing: 0.04em;">7 HARI TERAKHIR</span>
                </div>
            </div>

            
            <div style="padding: 24px 24px 20px; flex: 1;">
                <?php if(empty($chartData) || array_sum($chartData) == 0): ?>
                    
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 280px; background: #fafbff; border-radius: 16px; border: 2px dashed #e0e7ff;">
                        <div class="empty-state-icon" style="font-size: 56px;">📊</div>
                        <p style="font-family: var(--font-display); font-size: 16px; font-weight: 700; color: var(--nexus-text-primary); margin-top: 16px;">Belum Ada Data Absensi</p>
                        <p style="font-size: 13px; color: var(--nexus-text-muted); margin-top: 6px;">Data akan muncul saat ada warga yang melakukan absensi.</p>
                    </div>
                <?php else: ?>
                    <div style="position: relative; width: 100%; height: 300px;">
                        <canvas id="trafficChart"></canvas>
                    </div>
                <?php endif; ?>
            </div>

            
            <div style="padding: 16px 28px; border-top: 1px solid var(--nexus-border); display: flex; gap: 20px; background: #fafbff; border-radius: 0 0 24px 24px; flex-wrap: wrap;">
                <?php
                    $totalChart = array_sum($chartData ?? [0]);
                    $avgChart = count($chartData ?? []) > 0 ? round($totalChart / count($chartData)) : 0;
                    $maxChart = !empty($chartData) ? max($chartData) : 0;
                ?>
                <div>
                    <p style="font-size: 11px; color: var(--nexus-text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">Total 7 Hari</p>
                    <p style="font-family: var(--font-display); font-size: 22px; font-weight: 800; color: var(--nexus-text-primary);"><?php echo e($totalChart); ?></p>
                </div>
                <div style="width: 1px; background: var(--nexus-border);"></div>
                <div>
                    <p style="font-size: 11px; color: var(--nexus-text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">Rata-rata/Hari</p>
                    <p style="font-family: var(--font-display); font-size: 22px; font-weight: 800; color: #4f46e5;"><?php echo e($avgChart); ?></p>
                </div>
                <div style="width: 1px; background: var(--nexus-border);"></div>
                <div>
                    <p style="font-size: 11px; color: var(--nexus-text-muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;">Tertinggi</p>
                    <p style="font-family: var(--font-display); font-size: 22px; font-weight: 800; color: #10b981;"><?php echo e($maxChart); ?></p>
                </div>
            </div>
        </div>

        
        <div class="nx-card flex flex-col" style="padding: 0; overflow: hidden; min-height: 460px;">
            <div style="padding: 22px 24px 18px; border-bottom: 1px solid var(--nexus-border); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p class="nx-section-title">Warga Baru</p>
                    <p class="nx-section-sub">Balita terdaftar terbaru</p>
                </div>
                <span style="display: flex; align-items: center; gap: 7px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 20px; padding: 5px 12px; font-size: 11px; font-weight: 700; color: #16a34a; font-family: var(--font-display); letter-spacing: 0.05em;">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: #22c55e; animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></span>
                    TERBARU
                </span>
            </div>

            <div class="nx-scroll flex-1 overflow-y-auto" style="padding: 16px; background: #f8fafc; max-height: 380px;">
                <?php if(isset($balita_baru) && count($balita_baru) > 0): ?>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php $__currentLoopData = $balita_baru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $balita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="log-item">
                                <div class="log-avatar" style="background: #fff1f2; color: #ef4444;">
                                    <i class="fas fa-baby"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                                        <p style="font-size: 14px; font-weight: 700; color: var(--nexus-text-primary); font-family: var(--font-display); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <?php echo e($balita->nama_lengkap ?? 'Balita'); ?>

                                        </p>
                                        <span style="font-size: 10px; font-weight: 600; color: var(--nexus-text-muted); background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px; padding: 2px 8px; flex-shrink: 0;">
                                            <?php echo e($balita->created_at->translatedFormat('d M')); ?>

                                        </span>
                                    </div>
                                    <p style="font-size: 12px; color: var(--nexus-text-muted); margin-top: 3px;">
                                        <span style="color: #ef4444; font-weight: 600;">Balita</span> · Terdaftar di sistem
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; min-height: 240px; text-align: center; padding: 20px;">
                        <div class="empty-state-icon">👶</div>
                        <p style="font-family: var(--font-display); font-size: 15px; font-weight: 700; color: var(--nexus-text-primary); margin-top: 14px;">Belum Ada Data</p>
                        <p style="font-size: 12px; color: var(--nexus-text-muted); margin-top: 4px;">Data balita baru akan muncul otomatis.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div style="padding: 14px 16px; border-top: 1px solid var(--nexus-border); background: #fff; border-radius: 0 0 24px 24px;">
                <a href="<?php echo e(route('kader.data.balita.index')); ?>"
                   style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 12px; border-radius: 14px; border: 1.5px solid #e0e7ff; background: #fafbff; color: #4f46e5; font-size: 12px; font-weight: 700; font-family: var(--font-display); text-decoration: none; letter-spacing: 0.04em; transition: all 0.2s ease; text-transform: uppercase;"
                   onmouseover="this.style.background='#4f46e5'; this.style.color='#fff'; this.style.borderColor='#4f46e5'"
                   onmouseout="this.style.background='#fafbff'; this.style.color='#4f46e5'; this.style.borderColor='#e0e7ff'">
                    Database Balita <i class="fas fa-arrow-right" style="font-size: 10px;"></i>
                </a>
            </div>
        </div>

    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 anim-4">

        
        <div class="nx-card" style="padding: 0; overflow: hidden;">
            <div style="padding: 22px 24px 18px; border-bottom: 1px solid var(--nexus-border); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p class="nx-section-title">Jadwal Mendatang</p>
                    <p class="nx-section-sub">Agenda Posyandu yang akan datang</p>
                </div>
                <div style="width: 40px; height: 40px; border-radius: 12px; background: #fff7ed; border: 1px solid #fed7aa; display: flex; align-items: center; justify-content: center; color: #f97316; font-size: 18px;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            <div style="padding: 16px; display: flex; flex-direction: column; gap: 10px;">
                <?php $__empty_1 = true; $__currentLoopData = $jadwal_mendatang ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="jadwal-item">
                    <div style="width: 50px; height: 50px; border-radius: 14px; background: #eef2ff; color: #4f46e5; display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid #e0e7ff;">
                        <span style="font-family: var(--font-display); font-size: 18px; font-weight: 800; line-height: 1;"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->format('d')); ?></span>
                        <span style="font-size: 9px; font-weight: 600; text-transform: uppercase; color: #818cf8; letter-spacing: 0.05em;"><?php echo e(\Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('M')); ?></span>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <p style="font-size: 14px; font-weight: 700; color: var(--nexus-text-primary); font-family: var(--font-display); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <?php echo e($jadwal->nama_kegiatan ?? 'Kegiatan Posyandu'); ?>

                        </p>
                        <p style="font-size: 12px; color: var(--nexus-text-muted); margin-top: 3px;">
                            <i class="fas fa-map-marker-alt" style="font-size: 10px;"></i> <?php echo e($jadwal->lokasi ?? 'Posyandu'); ?>

                        </p>
                    </div>
                    <span style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 20px; padding: 4px 10px; font-size: 10px; font-weight: 700; color: #16a34a; flex-shrink: 0; font-family: var(--font-display);">Aktif</span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; text-align: center;">
                    <div class="empty-state-icon">📅</div>
                    <p style="font-family: var(--font-display); font-size: 15px; font-weight: 700; color: var(--nexus-text-primary); margin-top: 14px;">Tidak Ada Jadwal</p>
                    <p style="font-size: 12px; color: var(--nexus-text-muted); margin-top: 4px;">Belum ada agenda mendatang yang dijadwalkan.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="nx-card" style="padding: 0; overflow: hidden;">
            <div style="padding: 22px 24px 18px; border-bottom: 1px solid var(--nexus-border); display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <p class="nx-section-title">Pendaftaran Bulan Ini</p>
                    <p class="nx-section-sub">Distribusi warga baru <?php echo e(now()->translatedFormat('F Y')); ?></p>
                </div>
                <div style="width: 40px; height: 40px; border-radius: 12px; background: #eef2ff; border: 1px solid #e0e7ff; display: flex; align-items: center; justify-content: center; color: #4f46e5; font-size: 18px;">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
            <div style="padding: 20px 24px; display: flex; align-items: center; gap: 28px; flex-wrap: wrap;">
                
                
                <div style="position: relative; width: 140px; height: 140px; flex-shrink: 0;">
                    <canvas id="donutChart" style="width: 140px; height: 140px;"></canvas>
                    <?php $totalBulan = array_sum(array_values($pendaftaran_bulan_ini ?? ['balita'=>0,'remaja'=>0,'lansia'=>0,'ibu_hamil'=>0])); ?>
                    <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                        <p style="font-family: var(--font-display); font-size: 26px; font-weight: 800; color: var(--nexus-text-primary); line-height: 1;"><?php echo e($totalBulan); ?></p>
                        <p style="font-size: 10px; color: var(--nexus-text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em;">Warga</p>
                    </div>
                </div>

                
                <div style="flex: 1; display: flex; flex-direction: column; gap: 12px; min-width: 160px;">
                    <?php
                        $donutItems = [
                            ['label' => 'Balita',    'val' => $pendaftaran_bulan_ini['balita'] ?? 0,    'color' => '#ef4444'],
                            ['label' => 'Ibu Hamil', 'val' => $pendaftaran_bulan_ini['ibu_hamil'] ?? 0, 'color' => '#ec4899'],
                            ['label' => 'Remaja',    'val' => $pendaftaran_bulan_ini['remaja'] ?? 0,    'color' => '#0ea5e9'],
                            ['label' => 'Lansia',    'val' => $pendaftaran_bulan_ini['lansia'] ?? 0,    'color' => '#10b981'],
                        ];
                    ?>
                    <?php $__currentLoopData = $donutItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $di): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: <?php echo e($di['color']); ?>; flex-shrink: 0;"></div>
                        <span style="font-size: 13px; color: var(--nexus-text-muted); font-weight: 500; flex: 1;"><?php echo e($di['label']); ?></span>
                        <span style="font-family: var(--font-display); font-size: 15px; font-weight: 800; color: var(--nexus-text-primary);"><?php echo e($di['val']); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// =============================================================================
// 1. REALTIME CLOCK
// =============================================================================
(function initClock() {
    const el = document.getElementById('realtime-clock');
    if (!el) return;

    const hari  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    function tick() {
        const d  = new Date();
        const hh = String(d.getHours()).padStart(2,'0');
        const mm = String(d.getMinutes()).padStart(2,'0');
        const ss = String(d.getSeconds()).padStart(2,'0');
        el.innerHTML = `${hari[d.getDay()]}, ${String(d.getDate()).padStart(2,'0')} ${bulan[d.getMonth()]} ${d.getFullYear()} &nbsp;|&nbsp; ${hh}:${mm}:${ss} WIB`;
    }
    tick();
    setInterval(tick, 1000);
})();

// =============================================================================
// 2. CHART.JS — LINE CHART (Traffic)
// =============================================================================
function renderLineChart() {
    if (typeof Chart === 'undefined') {
        return setTimeout(renderLineChart, 300);
    }

    const canvas = document.getElementById('trafficChart');
    if (!canvas) return;

    const labels = <?php echo json_encode($chartLabels ?? []); ?>;
    const data   = <?php echo json_encode($chartData ?? []); ?>;

    if (!labels.length || Math.max(...data) === 0) return;

    // Destroy existing
    if (window._trafficChart instanceof Chart) {
        window._trafficChart.destroy();
    }

    const ctx = canvas.getContext('2d');

    // Gradient fill
    const grad = ctx.createLinearGradient(0, 0, 0, 280);
    grad.addColorStop(0,   'rgba(79,70,229,0.22)');
    grad.addColorStop(0.6, 'rgba(79,70,229,0.06)');
    grad.addColorStop(1,   'rgba(79,70,229,0)');

    Chart.defaults.font.family = "'DM Sans', sans-serif";

    window._trafficChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Kunjungan',
                data,
                borderColor: '#4f46e5',
                backgroundColor: grad,
                borderWidth: 3,
                fill: true,
                tension: 0.45,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2.5,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#4f46e5',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#94a3b8',
                    bodyColor: '#f8fafc',
                    padding: { top: 12, bottom: 12, left: 16, right: 16 },
                    borderRadius: 14,
                    titleFont: { size: 11, weight: '600', family: "'DM Sans', sans-serif" },
                    bodyFont: { size: 18, weight: '800', family: "'Outfit', sans-serif" },
                    displayColors: false,
                        callbacks: {
                            title: (items) => items[0].label,
                            label: (item) => `${item.parsed.y} Warga Hadir`
                        }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(226,232,240,0.6)', drawTicks: false },
                    border: { display: false },
                    ticks: {
                        padding: 12,
                        stepSize: 1,
                        font: { size: 12, weight: '600' },
                        color: '#94a3b8'
                    }
                },
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        padding: 12,
                        font: { size: 12, weight: '600' },
                        color: '#94a3b8'
                    }
                }
            },
            interaction: { mode: 'index', intersect: false }
        }
    });
}

// =============================================================================
// 3. CHART.JS — DONUT CHART (Pendaftaran)
// =============================================================================
function renderDonutChart() {
    if (typeof Chart === 'undefined') {
        return setTimeout(renderDonutChart, 300);
    }

    const canvas = document.getElementById('donutChart');
    if (!canvas) return;

    if (window._donutChart instanceof Chart) {
        window._donutChart.destroy();
    }

    const donutData = [
        <?php echo $pendaftaran_bulan_ini['balita'] ?? 0; ?>,
        <?php echo $pendaftaran_bulan_ini['ibu_hamil'] ?? 0; ?>,
        <?php echo $pendaftaran_bulan_ini['remaja'] ?? 0; ?>,
        <?php echo $pendaftaran_bulan_ini['lansia'] ?? 0; ?>

    ];

    const total = donutData.reduce((a,b) => a+b, 0);
    // If all zero, show placeholder
    const finalData = total === 0 ? [1,1,1,1] : donutData;

    window._donutChart = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: ['Balita','Ibu Hamil','Remaja','Lansia'],
            datasets: [{
                data: finalData,
                backgroundColor: ['#ef4444','#ec4899','#0ea5e9','#10b981'],
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: false,
            cutout: '72%',
            animation: { duration: 1000, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: total > 0,
                    backgroundColor: '#1e293b',
                    titleColor: '#94a3b8',
                    bodyColor: '#f8fafc',
                    padding: { top: 10, bottom: 10, left: 14, right: 14 },
                    borderRadius: 12,
                    displayColors: false,
                    callbacks: {
                        label: (item) => `${item.label}: ${total > 0 ? item.parsed : 0} orang`
                    }
                }
            }
        }
    });
}

// =============================================================================
// 4. INTERSECTION OBSERVER — Animate on scroll
// =============================================================================
function initScrollAnimations() {
    const items = document.querySelectorAll('.anim-1, .anim-2, .anim-3, .anim-4');
    if (!('IntersectionObserver' in window)) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, { threshold: 0.1 });

    items.forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
}

// =============================================================================
// 5. INIT ALL
// =============================================================================
window.addEventListener('load', function() {
    renderLineChart();
    renderDonutChart();
    initScrollAnimations();
});

// Also run on DOMContentLoaded as fallback
document.addEventListener('DOMContentLoaded', function() {
    // Delay slightly to ensure Chart.js is ready
    setTimeout(function() {
        renderLineChart();
        renderDonutChart();
    }, 200);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.kader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/kader/dashboard.blade.php ENDPATH**/ ?>