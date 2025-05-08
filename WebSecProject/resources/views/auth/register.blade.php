<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - {{ config('app.name') }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        body,
        html {
            height: 100%;
            margin: 0;
            background-color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            overflow-x: hidden;
            color: #2c2c2c;
        }

        .split-screen {
            display: flex;
            height: 100vh;
            position: relative;
            background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);
        }

        .left {
            flex: 1;
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.4)), url('/login.jpg') center/cover no-repeat;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.1);
        }

        .left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.3) 100%);
            backdrop-filter: blur(3px);
        }

        .logo-box {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 12px;
            transform: scale(1.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .logo-box:hover {
            transform: scale(1.3);
        }

        .logo {
            background: #fff;
            color: #000;
            font-weight: bold;
            font-size: 2.2rem;
            padding: 8px 18px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            letter-spacing: 1px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .brand {
            color: #fff;
            font-size: 2.2rem;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            letter-spacing: 1px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .right {
            flex: 1;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
        }

        .register-container {
            width: 100%;
            max-width: 380px;
            padding: 2rem;
            background: #ffffff;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: relative;
            animation: slideInRight 0.5s ease-out;
        }

        .step-indicator {
            color: #666;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        h2 {
            font-size: 1.5rem;
            font-weight: 500;
            color: #2c2c2c;
            margin-bottom: 1.5rem;
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 1.2rem;
            margin: 2rem 0;
        }

        .social-button {
            width: 52px;
            height: 52px;
            border: 2px solid #e8eef3;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            padding: 0;
            transform: scale(1);
        }

        .social-button:hover {
            background-color: #f8f9fa;
            border-color: #1473e6;
            transform: scale(1.15) translateY(-2px);
            box-shadow: 0 5px 15px rgba(20,115,230,0.1);
        }

        .social-button svg {
            width: 24px;
            height: 24px;
        }

        .social-button i {
            font-size: 22px;
        }

        .social-button.google img { width: 20px; }
        .social-button.facebook i { color: #1877f2; }
        .social-button.github i { color: #333; }
        .social-button.linkedin i { color: #0077b5; }

        .divider {
            display: flex;
            align-items: center;
            margin: 1rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e1e1e1;
        }

        .divider span {
            padding: 0 0.5rem;
            font-size: 0.75rem;
            color: #666;
        }

        .auth-section {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .auth-section .fw-semibold {
            font-size: 1rem;
            color: #2c2c2c;
            margin-bottom: 0.375rem;
        }

        .auth-section .text-secondary {
            font-size: 0.875rem;
            color: #666;
        }

        .form-label {
            font-size: 0.938rem;
            color: #2c2c2c;
            margin-bottom: 0.375rem;
        }

        .form-control {
            height: 42px;
            padding: 0.625rem 2.5rem 0.625rem 0.875rem;
            font-size: 1rem;
            border: 1px solid #e1e1e1;
            border-radius: 3px;
            background-color: #fff;
        }

        .form-control:focus {
            border-color: #1473e6;
            box-shadow: 0 0 0 1px rgba(20,115,230,0.2);
        }

        .password-field {
            position: relative;
        }

        .password-field .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            transition: color 0.2s ease;
        }

        .password-field .password-toggle:hover {
            color: #1473e6;
        }

        .password-field .password-toggle i {
            font-size: 20px;
        }

        .btn-primary {
            height: 42px;
            padding: 0 1.25rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 3px;
            background: #1473e6;
            border: none;
        }

        .btn-primary:hover {
            background: #0d66d0;
        }

        .invalid-feedback {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            color: #dc3545;
        }

        .sign-in-link {
            color: #1473e6;
            font-weight: 500;
            text-decoration: none;
            position: relative;
            transition: all 0.3s ease;
        }

        .sign-in-link:hover {
            color: #0d66d0;
        }

        .sign-in-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(to right, #1473e6, #0d66d0);
            transform: scaleX(0);
            transition: transform 0.3s ease;
            transform-origin: left;
        }

        .sign-in-link:hover::after {
            transform: scaleX(1);
        }

        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .spin {
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        .page-transition {
            transition: transform 0.5s ease, opacity 0.5s ease;
        }

        .page-transition.leaving {
            transform: translateX(20px);
            opacity: 0;
        }

        @media (max-width: 768px) {
            .split-screen {
                flex-direction: column;
            }

            .left {
                display: none;
            }

            .right {
                padding: 1.5rem;
            }

            .register-container {
                margin: 1rem;
                max-width: 100%;
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>
    <div class="split-screen" x-data="{ leaving: false }">
        <div class="left">
            <div class="logo-box">
                <span class="logo">Be</span>
                <span class="brand">Behance</span>
            </div>
        </div>
        <div class="right">
            <div class="register-container page-transition" :class="{ 'leaving': leaving }">
                <div class="step-indicator">Step 1 of 2</div>
                <h2>Create an account</h2>

                <div class="social-buttons">
                    <button class="social-button google">
                        <svg viewBox="0 0 24 24" width="24" height="24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                    </button>
                    <button class="social-button facebook">
                        <i class="bi bi-facebook"></i>
                    </button>
                    <button class="social-button github">
                        <i class="bi bi-github"></i>
                    </button>
                    <button class="social-button linkedin">
                        <i class="bi bi-linkedin"></i>
                    </button>
                </div>

                <div class="divider">
                    <span>Or</span>
                </div>

                <div class="auth-section">
                    <div class="fw-semibold">Sign up with email</div>
                    <div class="text-secondary">
                        Already have an account?
                        <a href="{{ route('login') }}" class="sign-in-link" @click.prevent="leaving = true; setTimeout(() => window.location.href = '{{ route('login') }}', 500)">
                            Sign in
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" x-data="{ submitting: false }" @submit.prevent="submitting = true; $el.submit();">
                    @csrf

                    @if($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            placeholder="example@domain.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-field">
                            <input type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Enter your password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="password-field">
                            <input type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Confirm your password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary w-100" :disabled="submitting">
                            <span x-show="!submitting">Continue</span>
                            <span x-show="submitting" x-cloak>
                                <i class="bi bi-arrow-repeat spin"></i> Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = event.currentTarget.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>

</html>
