<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Sistem Posyandu - Kader')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #06D6A0;
            --primary-dark: #048B69;
            --primary-light: #E8FFF8;
            --secondary-color: #118AB2;
            --accent-color: #FFD166;
            --danger-color: #EF476F;
            --dark-color: #073B4C;
            --gray-color: #8B9DA9;
            --gray-light: #F7F9FB;
            --white: #FFFFFF;
            --shadow-sm: 0 2px 8px rgba(7, 59, 76, 0.04);
            --shadow-md: 0 4px 16px rgba(7, 59, 76, 0.08);
            --shadow-lg: 0 8px 32px rgba(7, 59, 76, 0.12);
            --sidebar-width: 280px;
            --header-height: 72px;
            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 24px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #F7F9FB 0%, #E8FFF8 100%);
            color: var(--dark-color);
            overflow-x: hidden;
            font-size: 15px;
            line-height: 1.6;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--white);
            border-right: 1px solid rgba(7, 59, 76, 0.08);
            z-index: 1000;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gray-color);
            border-radius: 10px;
        }

        .sidebar-header {
            padding: 28px 24px;
            border-bottom: 1px solid rgba(7, 59, 76, 0.08);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .sidebar-logo-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--white);
        }

        .sidebar-logo-text h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--white);
            margin: 0;
            line-height: 1.2;
        }

        .sidebar-logo-text p {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
            font-weight: 500;
        }

        .sidebar-nav {
            padding: 24px 16px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-color);
            padding: 0 12px 12px;
            margin-top: 24px;
        }

        .nav-section-title:first-child {
            margin-top: 0;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--dark-color);
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
            transform: translateX(4px);
        }

        .nav-link:hover::before {
            transform: scaleY(1);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-light) 0%, rgba(6, 214, 160, 0.15) 100%);
            color: var(--primary-dark);
            font-weight: 600;
        }

        .nav-link.active::before {
            transform: scaleY(1);
        }

        .nav-link i {
            font-size: 18px;
            width: 22px;
            text-align: center;
        }

        .nav-link .fa-angle-down {
            margin-left: auto;
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .nav-link:not(.collapsed) .fa-angle-down {
            transform: rotate(180deg);
        }

        .collapse .nav-link {
            padding: 10px 16px 10px 52px;
            font-size: 13px;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ========== TOP HEADER ========== */
        .top-header {
            height: var(--header-height);
            background: var(--white);
            border-bottom: 1px solid rgba(7, 59, 76, 0.08);
            position: sticky;
            top: 0;
            z-index: 900;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            display: none;
            width: 40px;
            height: 40px;
            border: none;
            background: var(--gray-light);
            border-radius: var(--radius-sm);
            color: var(--dark-color);
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background: var(--primary-light);
            color: var(--primary-color);
        }

        .page-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: var(--gray-color);
        }

        .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-btn {
            width: 44px;
            height: 44px;
            border: none;
            background: var(--gray-light);
            border-radius: var(--radius-sm);
            color: var(--dark-color);
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .header-btn:hover {
            background: var(--primary-light);
            color: var(--primary-color);
        }

        .header-btn .badge {
            position: absolute;
            top: -4px;
            right: -4px;
            font-size: 10px;
            padding: 3px 6px;
            background: var(--danger-color);
            color: var(--white);
            border-radius: 10px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            background: var(--gray-light);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--dark-color);
        }

        .user-menu:hover {
            background: var(--primary-light);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 18px;
        }

        .user-info h6 {
            font-size: 14px;
            font-weight: 600;
            margin: 0;
            color: var(--dark-color);
        }

        .user-info p {
            font-size: 12px;
            color: var(--gray-color);
            margin: 0;
        }

        /* ========== PAGE CONTENT ========== */
        .page-content {
            padding: 32px;
        }

        /* ========== ALERTS ========== */
        .alert {
            border: 0;
            border-radius: var(--radius-md);
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert i {
            font-size: 20px;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(6, 214, 160, 0.1) 0%, rgba(6, 214, 160, 0.05) 100%);
            color: var(--primary-dark);
            border-left: 4px solid var(--primary-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 71, 111, 0.1) 0%, rgba(239, 71, 111, 0.05) 100%);
            color: #c62952;
            border-left: 4px solid var(--danger-color);
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 209, 102, 0.1) 0%, rgba(255, 209, 102, 0.05) 100%);
            color: #d4a843;
            border-left: 4px solid var(--accent-color);
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(17, 138, 178, 0.1) 0%, rgba(17, 138, 178, 0.05) 100%);
            color: #0e6f8f;
            border-left: 4px solid var(--secondary-color);
        }

        /* ========== CARD ========== */
        .card {
            border: 0;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            margin-bottom: 24px;
            background: var(--white);
            border: 1px solid rgba(7, 59, 76, 0.06);
            overflow: hidden;
        }

        .card-header {
            background: var(--white);
            border-bottom: 1px solid rgba(7, 59, 76, 0.08);
            padding: 20px 24px;
            font-weight: 700;
            font-size: 16px;
            color: var(--dark-color);
        }

        .card-body {
            padding: 24px;
        }

        /* ========== FOOTER ========== */
        .footer {
            margin-top: 40px;
            padding: 24px 0;
            border-top: 1px solid rgba(7, 59, 76, 0.08);
            background: var(--white);
        }

        .footer .text-muted {
            color: var(--gray-color) !important;
            font-size: 13px;
        }

        /* ========== DROPDOWN ========== */
        .dropdown-menu {
            border: 0;
            box-shadow: var(--shadow-lg);
            border-radius: var(--radius-md);
            padding: 8px;
            margin-top: 8px;
        }

        .dropdown-item {
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .dropdown-divider {
            margin: 8px 0;
            border-color: rgba(7, 59, 76, 0.08);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .top-header {
                padding: 0 16px;
            }

            .page-content {
                padding: 20px 16px;
            }

            .user-info {
                display: none;
            }

            .page-breadcrumb {
                display: none;
            }
        }

        /* ========== ANIMATIONS ========== */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.6s ease-out;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('kader.dashboard') }}" class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="sidebar-logo-text">
                    <h3>SIPOSYANDU</h3>
                    <p>Panel Kader</p>
                </div>
            </a>
        </div>

        <nav class="sidebar-nav">
            @include('partials.sidebar.kader')
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="page-breadcrumb">
                    <span class="breadcrumb-item">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </span>
                    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
                    <span class="breadcrumb-item active">
                        @yield('page-name', 'Overview')
                    </span>
                </div>
            </div>

            <div class="header-actions">
                <button class="header-btn" title="Notifikasi">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </button>
                <button class="header-btn" title="Pesan">
                    <i class="fas fa-envelope"></i>
                </button>
                <div class="dropdown">
                    <div class="user-menu" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            <i class="fas fa-user-nurse"></i>
                        </div>
                        <div class="user-info">
                            <h6>{{ Auth::user()->profile->full_name ?? Auth::user()->email }}</h6>
                            <p>Kader Posyandu</p>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('kader.profile.index') }}">
                                <i class="fas fa-user me-2"></i>Profil Saya
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ session('warning') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ session('info') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Terjadi kesalahan!</strong>
                        <ul class="mb-0 mt-2" style="padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <!-- Main Content -->
            <div class="container-fluid px-0">
                @yield('content')
            </div>
            
            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-muted">
                            &copy; {{ date('Y') }} Sistem Informasi Posyandu. All rights reserved.
                        </div>
                        <div class="col-md-6 text-end text-muted">
                            <i class="fas fa-heart text-danger"></i> Dibuat dengan semangat untuk kesehatan masyarakat
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Mobile Menu Toggle
            $('#menuToggle').on('click', function() {
                $('#sidebar').toggleClass('show');
            });

            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() <= 768) {
                    if (!$(e.target).closest('#sidebar, #menuToggle').length) {
                        $('#sidebar').removeClass('show');
                    }
                }
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000);
            
            // Keep collapse open for active items
            const activeLinks = document.querySelectorAll('.nav-link.active');
            activeLinks.forEach(link => {
                const parentCollapse = link.closest('.collapse');
                if (parentCollapse) {
                    parentCollapse.classList.add('show');
                    const toggleLink = document.querySelector('[href="#' + parentCollapse.id + '"]');
                    if (toggleLink) {
                        toggleLink.classList.remove('collapsed');
                    }
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>