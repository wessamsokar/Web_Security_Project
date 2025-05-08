<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        body, html {
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
            transition: transform 0.3s ease;
        }
        .logo-box:hover {
            transform: scale(1.25);
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
        }
        .brand {
            color: #fff;
            font-size: 2.2rem;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            letter-spacing: 1px;
        }
        .right {
            flex: 1;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            position: relative;
            overflow: hidden;
        }
        .right::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);
            transform: rotate(45deg);
            z-index: 0;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            position: relative;
            z-index: 1;
            animation: slideInRight 0.5s ease-out;
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
        .social-button img {
            width: 24px;
            height: 24px;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: #707070;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }
        .divider span {
            padding: 0 10px;
            font-size: 0.9rem;
        }
        .form-control {
            padding: 0.875rem 1rem;
            border-radius: 8px;
            border: 2px solid #e8eef3;
            transition: all 0.3s ease;
            font-size: 1rem;
            background: #f8f9fa;
        }
        .form-control:focus {
            border-color: #1473e6;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(20,115,230,0.1);
            transform: translateY(-2px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #1473e6 0%, #0d66d0 100%);
            border: none;
            padding: 0.875rem;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: translateY(0);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0d66d0 0%, #0a4fa3 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(20,115,230,0.2);
        }
        .create-account {
            color: #1473e6;
            font-weight: 500;
            text-decoration: none;
            position: relative;
            transition: all 0.3s ease;
        }
        .create-account:hover {
            color: #0d66d0;
        }
        .create-account::after {
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
        .create-account:hover::after {
            transform: scaleX(1);
        }
        .password-field {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #707070;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .password-toggle:hover {
            color: #1473e6;
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
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .spin {
            animation: spin 1s linear infinite;
            display: inline-block;
        }
        h2 {
            font-weight: 700;
            color: #2c2c2c;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        .form-label {
            font-weight: 500;
            color: #4a4a4a;
            margin-bottom: 0.5rem;
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
                padding: 1rem;
            }
        }
        .social-button.google img { width: 20px; }
        .social-button.facebook i { color: #1877f2; }
        .social-button.github i { color: #333; }
        .social-button.linkedin i { color: #0077b5; }
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
            <div class="login-container page-transition" :class="{ 'leaving': leaving }">
                <h2 class="mb-2">Sign in</h2>
                <div class="mb-4">
                    New user?
                    <a href="{{ route('register') }}" class="create-account" @click.prevent="leaving = true; setTimeout(() => window.location.href = '{{ route('register') }}', 500)">
                        Create an account
                    </a>
                </div>

                <form method="POST" action="{{ route('login') }}"
                    x-data="{
                        submitting: false,
                        showPassword: false,
                        email: '{{ old('email') }}',
                        showPasswordField: false
                    }"
                    @submit="submitting = true">
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
                            x-model="email"
                            required
                            autocomplete="email"
                            placeholder="example@domain.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3"
                        x-show="showPasswordField"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-field">
                            <input :type="showPassword ? 'text' : 'password'"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password">
                            <button type="button" class="password-toggle" @click="showPassword = !showPassword">
                                <i class="bi" :class="showPassword ? 'bi-eye-slash' : 'bi-eye'"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="button"
                            class="btn btn-primary w-100"
                            x-show="!showPasswordField"
                            @click="showPasswordField = true">
                            Continue
                        </button>
                        <button type="submit"
                            class="btn btn-primary w-100"
                            x-show="showPasswordField"
                            :disabled="submitting">
                            <span x-show="!submitting">Sign in</span>
                            <span x-show="submitting" x-cloak>
                                <i class="bi bi-arrow-repeat spin"></i> Signing in...
                            </span>
                        </button>
                    </div>
                </form>

                <div class="divider">
                    <span>Or</span>
                </div>

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

                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-muted">Get help signing in</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
