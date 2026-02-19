<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Bidan') - E-Posyandu</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Palet Warna Professional untuk Bidan */
            --primary-50: #f0f9ff;
            --primary-100: #e0f2fe;
            --primary-200: #bae6fd;
            --primary-300: #7dd3fc;
            --primary-400: #38bdf8;
            --primary-500: #0ea5e9;
            --primary-600: #0284c7;
            --primary-700: #0369a1;
            --primary-800: #075985;
            --primary-900: #0c4a6e;
            
            /* Secondary Colors - Soft Coral */
            --secondary-50: #fff1f2;
            --secondary-100: #ffe4e6;
            --secondary-200: #fecdd3;
            --secondary-300: #fda4af;
            --secondary-400: #fb7185;
            --secondary-500: #f43f5e;
            --secondary-600: #e11d48;
            --secondary-700: #be123c;
            --secondary-800: #9f1239;
            --secondary-900: #881337;
            
            /* Neutral Colors for Professional Look */
            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-300: #cbd5e1;
            --neutral-400: #94a3b8;
            --neutral-500: #64748b;
            --neutral-600: #475569;
            --neutral-700: #334155;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;
            
            /* Semantic Colors */
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            
            /* Layout Variables */
            --sidebar-width: 280px;
            --header-height: 72px;
            --border-radius: 12px;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--neutral-50);
            color: var(--neutral-800);
            line-height: 1.6;
            font-weight: 400;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--neutral-900);
            line-height: 1.3;
        }

        /* --- LAYOUT WRAPPER --- */
        .layout-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: relative;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            background: linear-gradient(180deg, #ffffff 0%, var(--neutral-50) 100%);
            border-right: 1px solid var(--neutral-200);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1100;
            overflow-y: auto;
            overflow-x: hidden;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--neutral-300);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--neutral-400);
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }

        /* --- TOP NAVBAR --- */
        .top-navbar {
            height: var(--header-height);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--neutral-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .page-header h4 {
            margin: 0;
            font-weight: 700;
            color: var(--neutral-900);
            font-size: 1.5rem;
            letter-spacing: -0.025em;
        }

        .page-header p {
            margin: 0;
            font-size: 0.875rem;
            color: var(--neutral-600);
            font-weight: 400;
            margin-top: 0.25rem;
        }

        /* --- USER MENU --- */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--neutral-100);
            border: 1px solid var(--neutral-200);
            color: var(--neutral-700);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            position: relative;
        }

        .notification-btn:hover {
            background: var(--neutral-200);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 18px;
            height: 18px;
            background: var(--secondary-500);
            color: white;
            border-radius: 50%;
            font-size: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            border: 2px solid white;
        }

        /* User Profile Dropdown */
        .user-profile-dropdown {
            border: 0;
            background: transparent;
            padding: 0.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .user-profile-dropdown:hover {
            background: var(--neutral-100);
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .user-info {
            text-align: left;
            margin-left: 0.75rem;
        }

        .user-name {
            font-weight: 600;
            color: var(--neutral-900);
            font-size: 0.875rem;
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--primary-600);
            font-weight: 500;
            background: var(--primary-50);
            padding: 0.15rem 0.5rem;
            border-radius: 20px;
            display: inline-block;
        }

        .dropdown-menu {
            border: 1px solid var(--neutral-200);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.625rem 1rem;
            border-radius: 8px;
            color: var(--neutral-700);
            font-weight: 500;
            transition: var(--transition);
            margin-bottom: 0.125rem;
        }

        .dropdown-item:hover {
            background: var(--neutral-100);
            color: var(--primary-600);
        }

        .dropdown-item:active {
            background: var(--primary-50);
            color: var(--primary-600);
        }

        .dropdown-item.logout {
            color: var(--danger);
        }

        .dropdown-item.logout:hover {
            background: var(--secondary-50);
            color: var(--danger);
        }

        /* --- CONTENT AREA --- */
        .content-wrapper {
            padding: 2rem;
            flex: 1;
            background: var(--neutral-50);
        }

        /* Card Styling */
        .card {
            border: 1px solid var(--neutral-200);
            border-radius: var(--border-radius);
            background: white;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--neutral-200);
            padding: 1.25rem 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Button Styling */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.625rem 1.25rem;
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            border-color: transparent;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-light {
            background: var(--neutral-100);
            border-color: var(--neutral-200);
            color: var(--neutral-700);
        }

        .btn-light:hover {
            background: var(--neutral-200);
            border-color: var(--neutral-300);
        }

        /* Badge Styling */
        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 600;
            border-radius: 20px;
            font-size: 0.75rem;
            line-height: 1;
        }

        /* Table Styling */
        .table {
            color: var(--neutral-700);
        }

        .table thead th {
            background: var(--neutral-50);
            color: var(--neutral-800);
            font-weight: 600;
            border-bottom: 2px solid var(--neutral-200);
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--neutral-200);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: var(--neutral-50);
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 1199.98px) {
            :root {
                --sidebar-width: 240px;
            }
            
            .content-wrapper {
                padding: 1.5rem;
            }
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: var(--shadow-lg);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .top-navbar {
                padding: 0 1.5rem;
            }
            
            .content-wrapper {
                padding: 1.25rem;
            }
        }

        @media (max-width: 767.98px) {
            .top-navbar {
                padding: 0 1rem;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
            
            .page-header h4 {
                font-size: 1.25rem;
            }
            
            .user-info {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            :root {
                --header-height: 64px;
            }
            
            .content-wrapper {
                padding: 0.75rem;
            }
        }

        /* --- UTILITIES --- */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .border-gradient {
            border-image: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%) 1;
        }

        /* --- SIDEBAR TOGGLE FOR MOBILE --- */
        .sidebar-toggle {
            display: none;
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            color: white;
            border: none;
            border-radius: 50%;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .sidebar-toggle:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
        }

        @media (max-width: 991.98px) {
            .sidebar-toggle {
                display: flex;
            }
        }

        /* --- ANIMATIONS --- */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        /* --- LOADING STATE --- */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid var(--neutral-300);
            border-top-color: var(--primary-500);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* --- ACCESSIBILITY --- */
        .focus-visible:focus-visible {
            outline: 2px solid var(--primary-500);
            outline-offset: 2px;
        }

        /* Print styles */
        @media print {
            .sidebar, 
            .top-navbar, 
            .btn, 
            .notification-btn,
            .sidebar-toggle {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            body {
                background: white !important;
                color: black !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>

    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle" id="sidebarToggleBtn">
        <i class="fas fa-bars"></i>
    </button>

    <div class="layout-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            @include('partials.sidebar.bidan')
        </nav>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Top Navigation -->
            <header class="top-navbar">
                <div class="page-header">
                    <h4>@yield('page-title', 'Dashboard Bidan')</h4>
                    <p>@yield('page-subtitle', 'Portal Tenaga Medis Posyandu')</p>
                </div>
                
                <div class="user-menu">
                    <!-- Notification Button -->
                    <button class="notification-btn focus-visible" type="button">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    
                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <button class="user-profile-dropdown dropdown-toggle d-flex align-items-center focus-visible" 
                                type="button" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false"
                                id="userDropdown">
                            <div class="avatar">
                                {{ substr(Auth::user()->name ?? 'B', 0, 1) }}
                            </div>
                            <div class="user-info d-none d-md-block">
                                <div class="user-name">{{ Auth::user()->name ?? 'Bidan' }}</div>
                                <span class="user-role">Tenaga Medis</span>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                            
                    
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-question-circle me-2"></i>Bantuan
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="mb-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout w-100">
                                        <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="content-wrapper animate-fade-in">
                <!-- Breadcrumb (Optional) -->
                @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            const mainContent = document.getElementById('mainContent');
            
            // Mobile sidebar toggle
            if (sidebarToggleBtn) {
                sidebarToggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    
                    // Update icon
                    const icon = this.querySelector('i');
                    if (sidebar.classList.contains('active')) {
                        icon.classList.remove('fa-bars');
                        icon.classList.add('fa-times');
                    } else {
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 991.98) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggleBtn = sidebarToggleBtn.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnToggleBtn && sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        const icon = sidebarToggleBtn.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                }
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991.98) {
                    sidebar.classList.remove('active');
                    if (sidebarToggleBtn) {
                        const icon = sidebarToggleBtn.querySelector('i');
                        icon.classList.remove('fa-times');
                        icon.classList.add('fa-bars');
                    }
                }
            });
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Add focus-visible class for keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-navigation');
                }
            });
            
            document.addEventListener('mousedown', function() {
                document.body.classList.remove('keyboard-navigation');
            });
        });
        
        // Add keyboard navigation support
        document.addEventListener('keydown', function(e) {
            const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
            const modal = document.querySelector('.modal.show');
            const container = modal ? modal : document;
            
            if (e.key === 'Tab') {
                const focusable = Array.from(container.querySelectorAll(focusableElements));
                const firstFocusable = focusable[0];
                const lastFocusable = focusable[focusable.length - 1];
                
                if (e.shiftKey && document.activeElement === firstFocusable) {
                    lastFocusable.focus();
                    e.preventDefault();
                } else if (!e.shiftKey && document.activeElement === lastFocusable) {
                    firstFocusable.focus();
                    e.preventDefault();
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>