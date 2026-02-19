<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistem Posyandu</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #009688;       /* Teal yang lebih modern */
            --primary-dark: #00796B;
            --primary-light: #B2DFDB;
            --accent: #4DB6AC;
            --text-dark: #37474F;
            --text-gray: #90A4AE;
            --bg-gradient: linear-gradient(135deg, #E0F2F1 0%, #80CBC4 100%);
            --shadow-soft: 0 10px 40px -10px rgba(0, 121, 107, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden; /* Mencegah scroll horizontal */
        }

        /* Background Shapes Animation */
        .bg-shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            z-index: -1;
            animation: float 20s infinite ease-in-out;
        }

        .shape-1 { width: 300px; height: 300px; top: -100px; left: -100px; }
        .shape-2 { width: 400px; height: 400px; bottom: -150px; right: -100px; animation-delay: 5s; }
        .shape-3 { width: 150px; height: 150px; top: 20%; right: 15%; animation-delay: 10s; opacity: 0.2; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(20px, -20px); }
        }

        /* Main Card Container */
        .login-card {
            background: #fff;
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            border-radius: 24px;
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            display: flex;
            position: relative;
            animation: slideUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Left Side: Form */
        .login-form-wrapper {
            flex: 1;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            z-index: 2;
        }

        /* Right Side: Illustration */
        .login-illustration-wrapper {
            flex: 1;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 40px;
            position: relative;
            color: white;
            text-align: center;
            overflow: hidden;
        }

        /* Decorative circles on illustration */
        .circle-deco {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }
        .cd-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
        .cd-2 { width: 200px; height: 200px; bottom: -50px; left: -50px; }

        /* Typography & Components */
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: var(--primary);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 4px 10px rgba(0, 150, 136, 0.3);
        }

        .logo-text h4 {
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            font-size: 20px;
        }

        .logo-text span {
            font-size: 12px;
            color: var(--text-gray);
            letter-spacing: 0.5px;
        }

        .welcome-text h2 {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .welcome-text p {
            color: var(--text-gray);
            font-size: 14px;
            margin-bottom: 30px;
        }

        /* Role Selector Cards */
        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .role-card {
            border: 2px solid #F0F0F0;
            border-radius: 12px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #FAFAFA;
        }

        .role-card:hover {
            border-color: var(--primary-light);
            background: #fff;
            transform: translateY(-2px);
        }

        .role-card.active {
            border-color: var(--primary);
            background: #E0F2F1;
        }

        .role-card i {
            font-size: 20px;
            margin-bottom: 5px;
            color: var(--text-gray);
            transition: color 0.3s;
        }

        .role-card.active i {
            color: var(--primary);
        }

        .role-card span {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Form Inputs */
        .form-floating > .form-control {
            border-radius: 10px;
            border: 2px solid #F0F0F0;
            padding-left: 45px; /* Space for icon */
        }

        .form-floating > .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 150, 136, 0.1);
        }

        .input-group-icon {
            position: absolute;
            left: 15px;
            top: 20px; /* Adjust based on floating label height */
            z-index: 4;
            color: var(--text-gray);
            font-size: 16px;
        }
        
        .form-floating > label {
            padding-left: 45px; /* Adjust label position */
        }

        .btn-primary-custom {
            background: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-weight: 600;
            width: 100%;
            color: white;
            margin-top: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0, 150, 136, 0.3);
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 150, 136, 0.4);
            color: white;
        }

        .btn-primary-custom:disabled {
            background: #CFD8DC;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Lottie Container */
        #lottieAnimation {
            width: 100%;
            max-width: 350px;
            margin-bottom: 20px;
        }

        /* Alert Styling */
        .alert-custom {
            border-radius: 10px;
            font-size: 13px;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-danger { background: #FFEBEE; color: #C62828; }
        .alert-success { background: #E8F5E9; color: #2E7D32; }

        /* Responsive Breakpoints */
        @media (max-width: 992px) {
            .login-card {
                max-width: 600px;
                min-height: auto;
                flex-direction: column; /* Stack vertically if needed, but usually hide illustration */
            }
            
            .login-illustration-wrapper {
                display: none; /* Hide image on tablet/mobile */
            }

            .login-form-wrapper {
                padding: 40px 30px;
            }
        }

        @media (max-width: 576px) {
            .login-form-wrapper {
                padding: 30px 20px;
            }
            
            .role-selector {
                gap: 10px;
            }
            
            .brand-logo {
                justify-content: center;
            }
            
            .welcome-text {
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>

    <div class="login-card">
        
        <div class="login-form-wrapper">
            <div class="brand-logo">
                <div class="logo-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="logo-text">
                    <h4>SIPOSYANDU</h4>
                    <span>Pelayanan Kesehatan Terpadu</span>
                </div>
            </div>

            <div class="welcome-text">
                <h2>Selamat Datang Kembali</h2>
                <p>Silakan pilih metode login Anda untuk masuk.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-custom alert-danger mb-4">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-custom alert-success mb-4">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="role-selector">
                <div class="role-card" onclick="selectRole('email', this)">
                    <i class="fas fa-user-nurse"></i>
                    <span>Bidan / Kader</span>
                </div>
                <div class="role-card" onclick="selectRole('nik', this)">
                    <i class="fas fa-users"></i>
                    <span>Warga</span>
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <div class="form-floating mb-3 position-relative">
                    <i class="fas fa-user input-group-icon" id="loginIcon"></i>
                    <input type="text" 
                           class="form-control" 
                           id="loginInput" 
                           name="login" 
                           placeholder="Masukkan Email / NIK" 
                           value="{{ old('login') }}"
                           required 
                           readonly>
                    <label for="loginInput" id="loginLabel">Pilih metode login di atas</label>
                </div>

                <div class="form-floating mb-4 position-relative">
                    <i class="fas fa-lock input-group-icon"></i>
                    <input type="password" 
                           class="form-control" 
                           id="passwordInput" 
                           name="password" 
                           placeholder="Password" 
                           required>
                    <label for="passwordInput">Password</label>
                    <span class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" 
                          style="cursor: pointer; color: var(--text-gray);"
                          onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted" style="font-size: 13px;" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                    <a href="#" class="text-decoration-none" style="font-size: 13px; color: var(--primary);">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-primary-custom" id="submitBtn" disabled>
                    Masuk Sekarang <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">Belum punya akun? Hubungi Kader setempat.</small>
            </div>
        </div>

        <div class="login-illustration-wrapper">
            <div class="circle-deco cd-1"></div>
            <div class="circle-deco cd-2"></div>
            
            <div id="lottieAnimation"></div>
            
            <h3 class="fw-bold mb-2">Sehat Bersama Kami</h3>
            <p class="text-white-50 px-4">Pantau kesehatan keluarga dengan mudah, cepat, dan terintegrasi melalui sistem digital Posyandu.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.12.2/lottie.min.js"></script>

    <script>
        // Lottie Animation
        lottie.loadAnimation({
            container: document.getElementById('lottieAnimation'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://lottie.host/2f3c8c6c-9c4d-4f5a-8b9a-3f8c6c9c9c9c/8b9a3f8c6c.json'
        });

        const loginInput = document.getElementById('loginInput');
        const loginLabel = document.getElementById('loginLabel');
        const loginIcon = document.getElementById('loginIcon');
        const submitBtn = document.getElementById('submitBtn');

        // Role Selection Logic
        function selectRole(role, element) {
            // Remove active class from all cards
            document.querySelectorAll('.role-card').forEach(card => card.classList.remove('active'));
            // Add active class to clicked card
            element.classList.add('active');

            // Enable input
            loginInput.removeAttribute('readonly');
            submitBtn.removeAttribute('disabled');
            loginInput.value = '';
            loginInput.focus();

            if (role === 'email') {
                loginInput.type = 'email';
                loginLabel.textContent = 'Masukkan Email Anda';
                loginInput.placeholder = 'contoh@posyandu.com';
                loginIcon.className = 'fas fa-envelope input-group-icon';
                // Remove NIK formatter
                loginInput.removeEventListener('input', formatNIK);
            } else {
                loginInput.type = 'text';
                loginLabel.textContent = 'Masukkan 16 Digit NIK';
                loginInput.placeholder = '1234567890123456';
                loginIcon.className = 'fas fa-id-card input-group-icon';
                // Add NIK formatter
                loginInput.addEventListener('input', formatNIK);
            }
        }

        // Format NIK (Only numbers, max 16)
        function formatNIK(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 16) value = value.substring(0, 16);
            e.target.value = value;
        }

        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto Select if Old Input Exists (Laravel specific)
        @if(old('login'))
            window.addEventListener('load', function() {
                const oldVal = "{{ old('login') }}";
                const cards = document.querySelectorAll('.role-card');
                if (oldVal.includes('@')) {
                    selectRole('email', cards[0]);
                } else {
                    selectRole('nik', cards[1]);
                }
                loginInput.value = oldVal;
            });
        @endif
    </script>
</body>
</html>