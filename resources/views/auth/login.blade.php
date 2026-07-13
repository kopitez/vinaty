<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vinaty Inventory System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #0f1012 0%, #2b2d31 100%);
            --background-gradient: #eef1f5;
            --card-shadow: 0 20px 50px rgba(0, 0, 0, 0.04);
            --font-outfit: 'Outfit', sans-serif;
            --font-inter: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-inter);
            background: var(--background-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
            position: relative;
        }

        /* Premium ambient glowing backgrounds with matching scheme */
        body::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(203, 242, 0, 0.2); /* Neon Lime glow */
            border-radius: 50%;
            top: -100px;
            left: -100px;
            filter: blur(120px);
            z-index: -1;
        }

        body::after {
            content: '';
            position: absolute;
            width: 450px;
            height: 450px;
            background: rgba(80, 139, 252, 0.2); /* Sky Blue glow */
            border-radius: 50%;
            bottom: -150px;
            right: -150px;
            filter: blur(140px);
            z-index: -1;
        }

        .login-container {
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 28px;
            box-shadow: var(--card-shadow);
            padding: 40px;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-4px);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .brand-logo-container {
            background: #0f1012;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }

        .brand-title {
            font-family: var(--font-outfit);
            font-weight: 800;
            color: #0f1012;
            font-size: 26px;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .brand-subtitle {
            color: #8a8f99;
            font-size: 13px;
            font-weight: 500;
            margin-top: 5px;
        }

        .form-label {
            font-weight: 600;
            color: #0f1012;
            font-size: 13.5px;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 22px;
        }

        .input-group-custom i.input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #8a8f99;
            font-size: 16px;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .input-group-custom .form-control {
            padding: 13px 16px 13px 46px;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            background-color: #f8fafc;
            font-size: 14.5px;
            font-weight: 500;
            color: #0f1012;
            transition: all 0.3s ease;
        }

        .input-group-custom .form-control:focus {
            background-color: #ffffff;
            border-color: #508bfc;
            box-shadow: 0 0 0 4px rgba(80, 139, 252, 0.15);
            outline: none;
        }

        .input-group-custom .form-control:focus ~ i.input-icon {
            color: #508bfc;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #8a8f99;
            cursor: pointer;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #508bfc;
        }

        .btn-login {
            background: #0f1012;
            border: none;
            color: white;
            padding: 13px;
            border-radius: 14px;
            font-family: var(--font-outfit);
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #2b2d31;
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 1.5px solid #cbd5e1;
            margin-top: 2.5px;
            border-radius: 5px;
        }

        .form-check-input:checked {
            background-color: #508bfc;
            border-color: #508bfc;
        }

        .form-check-label {
            font-size: 13.5px;
            color: #6e737d;
            font-weight: 500;
            user-select: none;
            padding-left: 4px;
        }

        .alert-custom {
            border-radius: 14px;
            font-size: 14px;
            border: none;
            padding: 14px;
            margin-bottom: 25px;
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            font-size: 12.5px;
            color: #8a8f99;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            
            <div class="brand-header">
                <div class="brand-logo-container">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <h1 class="brand-title">Vinaty Culinary</h1>
                <p class="brand-subtitle">Inventory Management</p>
            </div>

            <!-- Display Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-custom d-flex align-items-center" role="alert">
                    <i class="fa-solid fa-circle-check me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <!-- Display Validation Errors -->
            @if($errors->any())
                <div class="alert alert-danger alert-custom d-flex align-items-center" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2 fs-5"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <div class="mb-1">
                    <label for="email" class="form-label">Email Pengguna</label>
                    <div class="input-group-custom">
                        <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                        <i class="fa-regular fa-envelope input-icon"></i>
                    </div>
                </div>

                <div class="mb-1">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group-custom">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan kata sandi" required>
                        <i class="fa-solid fa-lock input-icon"></i>
                        <i class="fa-regular fa-eye-slash toggle-password" id="togglePassword"></i>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> Masuk ke Sistem
                </button>
            </form>
            
            <div class="footer-text">
                &copy; {{ date('Y') }} Vinaty Culinary. All rights reserved.
            </div>
        </div>
    </div>

    <!-- Bootstrap & Password Toggle Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the icon class
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
