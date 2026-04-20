<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kader Workspace') — PosyanduCare</title>
    
    {{-- MODERN SVG FAVICON --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='%236366f1'/%3E%3Cstop offset='100%25' stop-color='%23312e81'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='32' height='32' rx='10' fill='url(%23g)'/%3E%3Cpath d='M16 8C11.6 4.4 5 7.7 5 13.8c0 5.3 6.1 9.4 11 14.2 4.9-4.8 11-8.9 11-14.2C27 7.7 20.4 4.4 16 8z' fill='%23fff' opacity='0.95'/%3E%3C/svg%3E">
    
    {{-- DUAL FONT ENGINE: Poppins (UI) & Inter (Readability) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- ICONS & ALERTS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- TAILWIND CSS CONFIG --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter"', 'sans-serif'],
                        poppins: ['"Poppins"', 'sans-serif'],
                    },
                    colors: {
                        indigo: { 50: '#eef2ff', 100: '#e0e7ff', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca' }
                    }
                }
            }
        }
    </script>

    <style>
        /* ILUSI SINGLE PAGE APPLICATION (Tanpa Reload Kaku) */
        .page-content { animation: fadeInScale 0.4s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }
        @keyframes fadeInScale {
            0% { opacity: 0; transform: translateY(15px) scale(0.99); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* CUSTOM SCROLLBAR (Mulus & Elegan) */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* GLASSMORPHISM NAVBAR */
        .glass-nav {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* OVERRIDE CLASS UNTUK LOGIKA HAMBURGER DESKTOP */
        .force-hide-sidebar { transform: translateX(-100%) !important; }
        .force-expand-main { margin-left: 0 !important; }

        /* Kustomisasi Font SweetAlert agar pakai Poppins */
        div#swal2-html-container, div#swal2-title, button.swal2-confirm, button.swal2-cancel {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-[#f4f7fb] font-sans text-slate-700 overflow-hidden selection:bg-indigo-100 selection:text-indigo-900">

    {{-- OVERLAY GELAP UNTUK VERSI MOBILE (HP) --}}
    <div id="mobileBackdrop" class="fixed inset-0 bg-slate-900/50 z-40 hidden xl:hidden transition-opacity duration-300 opacity-0 backdrop-blur-sm"></div>

    <div class="flex h-screen w-full">
        
        {{-- MEMANGGIL SIDEBAR (PATH YANG BENAR) --}}
        @include('partials.sidebar.kader') 

        {{-- MAIN WRAPPER (Transisi margin untuk fitur Expand/Collapse Layar Penuh) --}}
        <div id="mainWrapper" class="flex-1 flex flex-col min-w-0 h-screen transition-all duration-400 ease-[cubic-bezier(0.2,0.8,0.2,1)] xl:ml-[280px]">
            
            {{-- NAVBAR ATAS --}}
            <header class="h-[80px] glass-nav border-b border-white/50 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-8 shadow-[0_4px_30px_rgba(0,0,0,0.02)]">
                
                {{-- Kiri: Tombol Hamburger Saja (Search dihapus agar tidak menjadi boomerang) --}}
                <div class="flex items-center gap-5">
                    <button id="btnToggleSidebar" class="w-11 h-11 rounded-[14px] bg-white border border-slate-200/60 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 flex items-center justify-center transition-all duration-300 shadow-sm focus:outline-none">
                        <i class="fas fa-bars-staggered text-[17px]"></i>
                    </button>
                    
                    {{-- Judul Halaman Dinamis (Opsional, agar area kiri tidak terlalu kosong) --}}
                    <div class="hidden md:block font-poppins">
                        <h2 class="text-[15px] font-bold text-slate-800 tracking-tight">@yield('page-name', 'Dashboard')</h2>
                        <p class="text-[11px] font-medium text-slate-400">@yield('title', 'Sistem Informasi Posyandu')</p>
                    </div>
                </div>

                {{-- Kanan: Lonceng Notifikasi & Profil User --}}
                <div class="flex items-center gap-3 sm:gap-6">
                    
                    {{-- DROPDOWN NOTIFIKASI (MENGGUNAKAN AJAX) --}}
                    <div class="relative" id="notifContainer">
                        <button id="btnNotif" class="relative w-11 h-11 rounded-[14px] bg-white border border-slate-200/60 hover:bg-indigo-50 flex items-center justify-center text-slate-500 hover:text-indigo-600 transition-colors shadow-sm focus:outline-none">
                            <i class="far fa-bell text-[19px]"></i>
                            {{-- Dot Merah (Aktif jika ada notif dari server) --}}
                            <span id="notifBadge" class="absolute top-2.5 right-3 w-2.5 h-2.5 rounded-full bg-rose-500 border-2 border-white hidden"></span>
                        </button>

                        {{-- Panel Dropdown Notifikasi --}}
                        <div id="notifMenu" class="absolute right-0 mt-3 w-[340px] bg-white rounded-[20px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] border border-slate-100 opacity-0 invisible translate-y-3 transition-all duration-300 z-50 overflow-hidden font-poppins">
                            <div class="px-5 py-4 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                                <h3 class="text-[14px] font-bold text-slate-800">Notifikasi Masuk</h3>
                                <a href="{{ route('kader.notifikasi.index') }}" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2.5 py-1 rounded-md">Lihat Semua</a>
                            </div>
                            <div id="notifContent" class="max-h-[320px] overflow-y-auto custom-scrollbar">
                                <div class="p-8 text-center text-[13px] font-medium text-slate-400">
                                    <i class="fas fa-circle-notch fa-spin text-indigo-500 text-xl mb-3 block"></i> Sedang memuat...
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-px h-8 bg-slate-200/80 hidden sm:block"></div>

                    {{-- DROPDOWN PROFIL USER --}}
                    <div class="relative" id="userContainer">
                        <button id="btnUser" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none group">
                            <div class="text-right hidden sm:block font-poppins">
                                <p class="text-[13.5px] font-bold text-slate-800 leading-tight group-hover:text-indigo-600 transition-colors">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Kader Posyandu</p>
                            </div>
                            <div class="w-11 h-11 rounded-[14px] bg-gradient-to-br from-slate-800 to-slate-700 text-white flex items-center justify-center font-bold text-[14px] shadow-md ring-2 ring-transparent group-hover:ring-indigo-100 transition-all">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                        </button>

                        {{-- Panel Dropdown Profil --}}
                        <div id="userMenu" class="absolute right-0 mt-3 w-[220px] bg-white rounded-[20px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.15)] border border-slate-100 opacity-0 invisible translate-y-3 transition-all duration-300 z-50 p-2 font-poppins">
                            <a href="{{ route('kader.profile.index') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] font-semibold text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-[14px] transition-colors">
                                <i class="far fa-user-circle text-[16px] w-5 text-center"></i> Profil & Akun
                            </a>
                            <div class="h-px bg-slate-100 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-[13px] font-semibold text-rose-500 hover:bg-rose-50 rounded-[14px] transition-colors text-left">
                                    <i class="fas fa-power-off text-[15px] w-5 text-center"></i> Keluar Sistem
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- RUANG KONTEN DINAMIS (Rata Tengah untuk Layar Ultra-Wide) --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto page-content p-4 sm:p-6 lg:p-8 relative scroll-smooth flex justify-center">
                <div class="w-full max-w-[1400px]"> 
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Core Scripts (SweetAlert) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- MESIN JAVASCRIPT LAYOUT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. LOGIKA HAMBURGER
            const sidebar = document.getElementById('sidebar');
            const mainWrapper = document.getElementById('mainWrapper');
            const btnToggle = document.getElementById('btnToggleSidebar');
            const backdrop = document.getElementById('mobileBackdrop');

            function toggleSidebar() {
                const isDesktop = window.innerWidth >= 1280; 

                if (isDesktop) {
                    sidebar.classList.toggle('force-hide-sidebar');
                    mainWrapper.classList.toggle('force-expand-main');
                } else {
                    const isClosed = sidebar.classList.contains('-translate-x-full');
                    if (isClosed) {
                        sidebar.classList.remove('-translate-x-full');
                        backdrop.classList.remove('hidden');
                        setTimeout(() => backdrop.classList.add('opacity-100'), 10);
                    } else {
                        sidebar.classList.add('-translate-x-full');
                        backdrop.classList.remove('opacity-100');
                        setTimeout(() => backdrop.classList.add('hidden'), 300);
                    }
                }
            }

            if (btnToggle) btnToggle.addEventListener('click', toggleSidebar);
            if (backdrop) backdrop.addEventListener('click', toggleSidebar);

            // 2. ENGINE DROPDOWN (Click Outside to Close)
            function setupDropdown(btnId, menuId, containerId) {
                const btn = document.getElementById(btnId);
                const menu = document.getElementById(menuId);
                const container = document.getElementById(containerId);

                if(btn && menu) {
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        document.querySelectorAll('.absolute.z-50').forEach(el => {
                            if(el.id !== menuId) el.classList.add('invisible', 'opacity-0', 'translate-y-3');
                        });
                        menu.classList.toggle('invisible');
                        menu.classList.toggle('opacity-0');
                        menu.classList.toggle('translate-y-3');
                    });

                    document.addEventListener('click', (e) => {
                        if (!container.contains(e.target)) {
                            menu.classList.add('invisible', 'opacity-0', 'translate-y-3');
                        }
                    });
                }
            }

            setupDropdown('btnUser', 'userMenu', 'userContainer');
            setupDropdown('btnNotif', 'notifMenu', 'notifContainer');

            // 3. ENGINE NOTIFIKASI AJAX
            const notifBtn = document.getElementById('btnNotif');
            let isNotifFetched = false;

            if (notifBtn) {
                fetch("{{ route('kader.notifikasi.fetch') }}", {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.unreadCount > 0) document.getElementById('notifBadge').classList.remove('hidden');
                }).catch(() => {});

                notifBtn.addEventListener('click', () => {
                    if (!isNotifFetched) {
                        const contentBox = document.getElementById('notifContent');
                        fetch("{{ route('kader.notifikasi.fetch') }}", {
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        })
                        .then(res => res.json())
                        .then(data => {
                            contentBox.innerHTML = data.html || '<div class="p-8 text-center text-[13px] text-slate-400 font-poppins">Tidak ada notifikasi terbaru.</div>';
                            isNotifFetched = true;
                        }).catch(() => {
                            contentBox.innerHTML = '<div class="p-8 text-center text-[13px] text-rose-500 font-poppins"><i class="fas fa-exclamation-triangle mb-2 text-xl"></i><br>Gagal memuat info server.</div>';
                        });
                    }
                });
            }

            // 4. TOAST ALERTS
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: { popup: 'rounded-[16px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-slate-100 font-poppins' },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
            @endif

            @if(session('error'))
                Toast.fire({ icon: 'error', title: "{{ session('error') }}" });
            @endif

            // GLOBAL DELETE CONFIRMATION
            window.confirmDelete = function(formId, itemName = 'data ini') {
                Swal.fire({
                    title: 'Hapus Permanen?',
                    html: `Yakin ingin menghapus <b>${itemName}</b>?<br><span class="text-sm text-slate-500">Tindakan ini tidak bisa dibatalkan.</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: { 
                        popup: 'rounded-[24px] shadow-2xl border-0',
                        title: 'font-bold text-slate-800',
                        confirmButton: 'rounded-xl font-semibold tracking-wide',
                        cancelButton: 'rounded-xl font-semibold tracking-wide'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading() }
                        });
                        document.getElementById(formId).submit();
                    }
                })
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>