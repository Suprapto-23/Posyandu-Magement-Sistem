<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Otentikasi'); ?> — PosyanduCare</title>
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><path fill=%22%230d9488%22 d=%22M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z%22/><path fill=%22white%22 d=%22M11 7h2v3h3v2h-3v3h-2v-3H8v-2h3V7z%22/></svg>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Poppins:wght@700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-poppins: 'Poppins', sans-serif;
        }
        body {
            font-family: var(--font-sans);
            margin: 0; padding: 0;
            overflow-x: hidden;
            background-color: #f8fafc;
        }
        h1, h2, h3, h4, h5, h6 { font-family: var(--font-poppins); }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="antialiased selection:bg-teal-200 selection:text-teal-900 text-slate-800">
    
    <?php echo $__env->yieldContent('content'); ?>

    <div id="loginSuccessOverlay" class="fixed inset-0 z-[10000] flex flex-col items-center justify-center opacity-0 pointer-events-none transition-opacity duration-500" style="background:linear-gradient(135deg, #0d9488 0%, #0369a1 100%);">
        <div id="successCircle" class="absolute w-0 h-0 bg-white/20 rounded-full transition-all duration-1000 ease-out"></div>
        <div class="relative z-10 flex flex-col items-center animate-success-pop" style="animation-delay: 0.2s; opacity: 0;">
            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-white shadow-2xl flex items-center justify-center mb-6 relative border-4 border-teal-100">
                <i class="fas fa-check text-5xl sm:text-6xl text-teal-500 animate-checkmark-draw"></i>
            </div>
            <h2 class="text-3xl sm:text-4xl font-black text-white font-poppins tracking-tight mb-2 text-center">Akses Diberikan!</h2>
            <p class="text-teal-100 text-xs sm:text-sm font-bold tracking-widest uppercase text-center">Memasuki Dashboard...</p>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <style type="text/tailwindcss">
        /* Animasi Khusus Overlay Sukses */
        .animate-success-pop { animation: successPop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        @keyframes successPop { from { opacity: 0; transform: scale(0.5); } to { opacity: 1; transform: scale(1); } }
        
        .animate-checkmark-draw { animation: checkmarkDraw 0.4s ease-in-out forwards; animation-delay: 0.5s; opacity: 0; transform: scale(0); }
        @keyframes checkmarkDraw { from { opacity: 0; transform: scale(0) rotate(-45deg); } to { opacity: 1; transform: scale(1) rotate(0deg); } }
    </style>
</body>
</html><?php /**PATH C:\xampp\htdocs\POSYANDU\posyandu-management-system\resources\views/layouts/auth.blade.php ENDPATH**/ ?>