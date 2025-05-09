<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Password - Clothing</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
        }

        .bg-login {
            background: url('/login.jpg') center center/cover no-repeat;
            min-height: 100vh;
        }

        .overlay {
            background: rgba(60, 40, 30, 0.45);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            background: #232b2b;
            color: #fff;
            font-weight: bold;
            font-size: 2.2rem;
            border-radius: 6px;
            padding: 8px 18px;
            letter-spacing: 2px;
        }

        .brand {
            color: #fff;
            font-size: 2rem;
            font-weight: 500;
            letter-spacing: 1px;
        }

        .user-email {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            background-color: #f8f9fa;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .user-email .email {
            flex-grow: 1;
            font-weight: 500;
        }

        @media (max-width: 991.98px) {

            .bg-login,
            .overlay {
                min-height: 250px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid g-0">
        <div class="row g-0 min-vh-100">
            <!-- Left Section -->
            <div class="col-lg-6 d-none d-lg-block bg-login">
                <div class="overlay w-100 h-100">
                    <div class="logo-box">
                        <span class="logo">Be</span>
                        <span class="brand">Clothing</span>
                    </div>
                </div>
            </div>
            <!-- Right Section -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white">
                <div class="w-100" style="max-width: 400px;">
                    <h2 class="mb-3 fw-bold">Enter your password</h2>

                    <div class="user-email">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span class="email">user@example.com</span>
                        <a href="#" class="text-decoration-none"><i class="bi bi-pencil"></i></a>
                    </div>

                    <form>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    Stay signed in
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </form>

                    <div class="mt-3">
                        <a href="#" class="text-primary text-decoration-none small">Forgot password?</a>
                    </div>

                    <div class="mt-4 text-center text-secondary small">
                        <a href="#" class="text-secondary text-decoration-none">Privacy Policy</a> &nbsp; | &nbsp;
                        <a href="#" class="text-secondary text-decoration-none">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function () {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>
</body>

</html>
