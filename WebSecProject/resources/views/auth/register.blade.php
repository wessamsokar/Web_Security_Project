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

</head>

<body class="bg-light">
    <div class="container-fluid min-vh-100">
        <div class="row min-vh-100">
            <!-- Left Side - Image Section -->
            <div class="col-md-6 d-none d-md-block p-0">
                <div class="position-relative h-100"
                    style="background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.4)), url('/login.jpg') center/cover no-repeat;">
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-white text-dark fw-bold fs-1 px-4 py-2 rounded-3 shadow">Be</span>
                            <span class="text-white fw-bold fs-1">Behance</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Registration Form -->
            <div class="col-md-6 d-flex align-items-center justify-content-center p-4">
                <div class="card shadow-lg border-0 rounded-4 p-4" style="max-width: 400px; width: 100%;">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Step 1 of 2</div>
                        <h2 class="fw-bold mb-2">Create your account</h2>
                        <p class="mb-4">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-decoration-none text-primary">Sign in</a>
                        </p>

                        <form method="POST" action="{{ route('register') }}">
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
                                    class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" required autocomplete="email"
                                    placeholder="example@domain.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        id="password" name="password" required autocomplete="new-password"
                                        placeholder="Create a password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                                Continue
                            </button>

                            <div class="text-center mb-4">
                                <span class="text-muted">Or</span>
                            </div>

                            <div class="d-flex justify-content-center gap-3 mb-4">
                                <a href="{{ route('login_with_google') }}" class="btn btn-outline-light border">
                                    <i class="bi bi-google"></i>
                                </a>
                                <a href="{{ route('login_with_facebook') }}" class="btn btn-outline-light border">
                                    <i class="bi bi-facebook text-primary"></i>
                                </a>
                                <a href="{{ route('login_with_github') }}" class="btn btn-outline-light border">
                                    <i class="bi bi-github"></i>
                                </a>
                                <a href="{{ route('login_with_linkedin') }}" class="btn btn-outline-light border">
                                    <i class="bi bi-linkedin text-primary"></i>
                                </a>
                            </div>

                            <div class="text-center small text-muted">
                                By continuing, you agree to our
                                <a href="#" class="text-decoration-none">Terms of Service</a>
                                and
                                <a href="#" class="text-decoration-none">Privacy Policy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        // Simple password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>
</body>

</html>
