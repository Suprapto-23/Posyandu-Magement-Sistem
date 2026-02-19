<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Posyandu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --dark-bg: #222831;
            --dark-secondary: #393E46;
            --accent-cyan: #00ADB5;
            --light-gray: #EEEEEE;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--dark-secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before,
        body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: var(--accent-cyan);
            opacity: 0.05;
            animation: float 20s infinite ease-in-out;
        }

        body::before {
            width: 400px;
            height: 400px;
            top: -200px;
            right: -200px;
        }

        body::after {
            width: 500px;
            height: 500px;
            bottom: -250px;
            left: -250px;
            animation-delay: 5s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        /* Header */
        .login-header {
            background: linear-gradient(135deg, var(--dark-bg) 0%, var(--dark-secondary) 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: var(--accent-cyan);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            opacity: 0.1;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: var(--accent-cyan);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(0, 173, 181, 0.3);
            position: relative;
            z-index: 1;
        }

        .logo-icon i {
            font-size: 40px;
            color: white;
        }

        .login-header h1 {
            color: white;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        /* Body */
        .login-body {
            padding: 40px 30px;
        }

        /* Alert Notifications */
        .alert-modern {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: slideDown 0.4s ease-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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

        .alert-modern i {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-modern .alert-content {
            flex: 1;
        }

        .alert-modern strong {
            display: block;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .alert-modern span {
            font-size: 13px;
            opacity: 0.95;
            display: block;
            line-height: 1.5;
        }

        .alert-danger {
            background: #fee;
            color: #c33;
            border-left: 4px solid #c33;
        }

        .alert-success {
            background: #efe;
            color: #2a7;
            border-left: 4px solid #2a7;
        }

        .alert-info {
            background: #e7f6f8;
            color: var(--accent-cyan);
            border-left: 4px solid var(--accent-cyan);
        }

        .alert-warning {
            background: #fff4e5;
            color: #f57c00;
            border-left: 4px solid #f57c00;
        }

        /* Role Selection */
        .role-selector {
            margin-bottom: 28px;
        }

        .role-selector-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 12px;
            display: block;
        }

        .role-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .role-option {
            background: var(--light-gray);
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 20px 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-option:hover {
            background: #f5f5f5;
            transform: translateY(-2px);
        }

        .role-option.active {
            background: rgba(0, 173, 181, 0.1);
            border-color: var(--accent-cyan);
            box-shadow: 0 4px 12px rgba(0, 173, 181, 0.2);
        }

        .role-option i {
            font-size: 28px;
            color: var(--dark-secondary);
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .role-option.active i {
            color: var(--accent-cyan);
            transform: scale(1.1);
        }

        .role-option-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 4px;
        }

        .role-option-subtitle {
            font-size: 11px;
            color: #666;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 16px;
            transition: color 0.3s ease;
            z-index: 1;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 4px rgba(0, 173, 181, 0.1);
        }

        .form-control:focus ~ .input-icon {
            color: var(--accent-cyan);
        }

        .form-control.is-invalid {
            border-color: #c33;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background: #f5f5f5;
            cursor: not-allowed;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 8px;
            transition: color 0.3s ease;
            z-index: 1;
        }

        .password-toggle:hover {
            color: var(--accent-cyan);
        }

        .invalid-feedback {
            display: none;
            font-size: 12px;
            color: #c33;
            margin-top: 6px;
            align-items: center;
            gap: 6px;
        }

        .invalid-feedback.d-block {
            display: flex !important;
        }

        .form-help {
            font-size: 12px;
            color: #666;
            margin-top: 6px;
        }

        /* Checkbox */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--accent-cyan);
            border-color: var(--accent-cyan);
        }

        .form-check-label {
            font-size: 13px;
            color: var(--dark-bg);
            cursor: pointer;
            user-select: none;
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--accent-cyan) 0%, #00d9e6 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0, 173, 181, 0.3);
        }

        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 173, 181, 0.4);
        }

        .btn-login:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-login:disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* Login Info */
        .login-info {
            background: var(--light-gray);
            border-radius: 12px;
            padding: 20px;
            margin-top: 24px;
            border-left: 4px solid var(--accent-cyan);
        }

        .login-info h6 {
            font-size: 13px;
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .login-info ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .login-info ul li {
            font-size: 12px;
            color: #555;
            padding: 6px 0;
            padding-left: 24px;
            position: relative;
        }

        .login-info ul li::before {
            content: '\f058';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            color: var(--accent-cyan);
            font-size: 14px;
        }

        .login-info ul strong {
            color: var(--dark-bg);
        }

        .login-info .small {
            font-size: 11px;
            color: #666;
            margin-top: 8px;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            padding: 24px 30px;
            background: #f8f8f8;
            border-top: 1px solid #e0e0e0;
        }

        .login-footer p {
            font-size: 13px;
            color: #666;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-footer .small {
            font-size: 11px;
            color: #999;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-container {
                max-width: 100%;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-body {
                padding: 30px 20px;
            }

            .logo-icon {
                width: 70px;
                height: 70px;
            }

            .logo-icon i {
                font-size: 35px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            .login-footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="logo-icon">
                    <i class="fas fa-hospital-user"></i>
                </div>
                <h1>SIPOSYANDU</h1>
                <p>Sistem Informasi Posyandu Terpadu</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <!-- Laravel Notifications -->
                @if ($errors->any())
                    <div class="alert-modern alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div class="alert-content">
                            <strong>Login Gagal</strong>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    </div>
                @endif
                
                @if (session('status'))
                    <div class="alert-modern alert-info">
                        <i class="fas fa-info-circle"></i>
                        <div class="alert-content">
                            <strong>Informasi</strong>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif
                
                @if (session('success'))
                    <div class="alert-modern alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div class="alert-content">
                            <strong>Berhasil</strong>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Role Selector -->
                <div class="role-selector">
                    <label class="role-selector-label">
                        <i class="fas fa-user-shield"></i> Pilih Metode Login
                    </label>
                    <div class="role-options">
                        <div class="role-option" data-role="email" onclick="selectRole('email')">
                            <i class="fas fa-envelope"></i>
                            <div class="role-option-title">Email Login</div>
                            <div class="role-option-subtitle">Bidan & Kader</div>
                        </div>
                        <div class="role-option" data-role="nik" onclick="selectRole('nik')">
                            <i class="fas fa-id-card"></i>
                            <div class="role-option-title">NIK Login</div>
                            <div class="role-option-subtitle">Warga</div>
                        </div>
                    </div>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="login" class="form-label" id="loginLabel">
                            <i class="fas fa-user"></i> Email atau NIK
                        </label>
                        <div class="input-wrapper">
                            <input type="text" 
                                   class="form-control @error('login') is-invalid @enderror" 
                                   id="login" 
                                   name="login" 
                                   value="{{ old('login') }}" 
                                   placeholder="Pilih metode login terlebih dahulu" 
                                   required 
                                   autofocus
                                   readonly>
                            <i class="input-icon fas fa-at" id="loginIcon"></i>
                        </div>
                        <small class="form-help" id="loginHelp">
                            Pilih metode login di atas untuk mengaktifkan input
                        </small>
                        @error('login')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <div class="input-wrapper">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan password" 
                                   required>
                            <i class="input-icon fas fa-key"></i>
                            <button class="password-toggle" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Ingat saya selama 30 hari
                        </label>
                    </div>
                    
                    <button type="submit" class="btn-login" id="loginButton" disabled>
                        <i class="fas fa-sign-in-alt"></i> Masuk ke Sistem
                    </button>
                </form>
                
                <div class="login-info">
                    <h6><i class="fas fa-info-circle"></i> Petunjuk Login</h6>
                    <ul>
                        <li><strong>Admin:</strong> Gunakan email (admin@posyandu.com)</li>
                        <li><strong>Bidan/Kader:</strong> Login dengan email terdaftar</li>
                        <li><strong>Warga:</strong> Login dengan NIK (16 digit)</li>
                    </ul>
                    <div class="mt-2">
                        <p class="small mb-1"><i class="fas fa-key"></i> Password default:</p>
                        <ul class="small mb-0">
                            <li><strong>User baru:</strong> Password digenerate sistem</li>
                            <li><strong>Reset password:</strong> Hubungi admin</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <p>
                    <i class="fas fa-phone-alt"></i>
                    <span>Bantuan: <strong>0812-3456-7890</strong></span>
                </p>
                <p class="small text-muted">
                    &copy; {{ date('Y') }} SIPOSYANDU v2.0 - Sistem Informasi Posyandu
                </p>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let selectedRole = null;

        // Role selection
        function selectRole(role) {
            selectedRole = role;
            
            document.querySelectorAll('.role-option').forEach(el => {
                el.classList.remove('active');
            });
            event.target.closest('.role-option').classList.add('active');
            
            const loginInput = document.getElementById('login');
            const loginIcon = document.getElementById('loginIcon');
            const loginLabel = document.getElementById('loginLabel');
            const loginHelp = document.getElementById('loginHelp');
            const loginButton = document.getElementById('loginButton');
            
            loginInput.removeAttribute('readonly');
            loginButton.disabled = false;
            
            if (role === 'email') {
                loginInput.placeholder = "contoh: bidan@posyandu.com";
                loginIcon.className = "input-icon fas fa-envelope";
                loginLabel.innerHTML = '<i class="fas fa-envelope"></i> Email';
                loginHelp.textContent = "Masukkan email yang terdaftar";
                loginInput.type = 'email';
            } else {
                loginInput.placeholder = "16 digit NIK (contoh: 1234567890123456)";
                loginIcon.className = "input-icon fas fa-id-card";
                loginLabel.innerHTML = '<i class="fas fa-id-card"></i> NIK';
                loginHelp.textContent = "Masukkan 16 digit NIK tanpa spasi";
                loginInput.type = 'text';
                loginInput.addEventListener('input', formatNIK);
            }
            
            loginInput.focus();
        }

        // Format NIK
        function formatNIK(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 16) {
                value = value.substring(0, 16);
            }
            e.target.value = value;
        }

        // Toggle password
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Auto-dismiss alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-modern');
            alerts.forEach(alert => {
                alert.style.animation = 'slideDown 0.4s ease-out reverse';
                setTimeout(() => alert.remove(), 400);
            });
        }, 5000);

        // Pre-select role if old input exists
        @if(old('login'))
            window.addEventListener('load', function() {
                const oldLogin = "{{ old('login') }}";
                if (oldLogin.includes('@')) {
                    const emailOption = document.querySelector('[data-role="email"]');
                    emailOption.click();
                } else if (/^\d+$/.test(oldLogin)) {
                    const nikOption = document.querySelector('[data-role="nik"]');
                    nikOption.click();
                }
            });
        @endif
    </script>
</body>
</html>