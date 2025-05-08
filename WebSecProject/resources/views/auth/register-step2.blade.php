<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Profile - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

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
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.3) 100%);
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            letter-spacing: 1px;
        }

        .brand {
            color: #fff;
            font-size: 2.2rem;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
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
            max-width: 480px;
            padding: 2rem;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
            position: relative;
            animation: slideInRight 0.5s ease-out;
        }

        .step-indicator {
            color: #666;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .form-control {
            height: 48px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 2px solid #e8eef3;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            border-color: #1473e6;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(20, 115, 230, 0.1);
        }

        .btn-primary {
            height: 48px;
            background: linear-gradient(135deg, #1473e6 0%, #0d66d0 100%);
            border: none;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0d66d0 0%, #0a4fa3 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(20, 115, 230, 0.2);
        }

        h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2c2c2c;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #4a4a4a;
            margin-bottom: 0.5rem;
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

            .register-container {
                margin: 1rem;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="split-screen">
        <div class="left">
            <div class="logo-box">
                <span class="logo">Be</span>
                <span class="brand">Behance</span>
            </div>
        </div>
        <div class="right">
            <div class="register-container">
                <div class="step-indicator">Step 2 of 2</div>
                <h2>Complete your profile</h2>

                <form method="POST" action="{{ route('register.complete') }}" x-data="{ submitting: false }"
                    @submit.prevent="submitting = true; $el.submit();">
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

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First name</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                autocomplete="given-name" placeholder="Enter your first name">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last name</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                autocomplete="family-name" placeholder="Enter your last name">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="birth_month" class="form-label">Month</label>
                            <select class="form-control @error('birth_month') is-invalid @enderror" id="birth_month"
                                name="birth_month" required>
                                <option value="">Select month</option>
                                <option value="01" {{ old('birth_month') == '01' ? 'selected' : '' }}>January</option>
                                <option value="02" {{ old('birth_month') == '02' ? 'selected' : '' }}>February</option>
                                <option value="03" {{ old('birth_month') == '03' ? 'selected' : '' }}>March</option>
                                <option value="04" {{ old('birth_month') == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ old('birth_month') == '05' ? 'selected' : '' }}>May</option>
                                <option value="06" {{ old('birth_month') == '06' ? 'selected' : '' }}>June</option>
                                <option value="07" {{ old('birth_month') == '07' ? 'selected' : '' }}>July</option>
                                <option value="08" {{ old('birth_month') == '08' ? 'selected' : '' }}>August</option>
                                <option value="09" {{ old('birth_month') == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ old('birth_month') == '10' ? 'selected' : '' }}>October</option>
                                <option value="11" {{ old('birth_month') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ old('birth_month') == '12' ? 'selected' : '' }}>December</option>
                            </select>
                            @error('birth_month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="birth_day" class="form-label">Day</label>
                            <input type="number" class="form-control @error('birth_day') is-invalid @enderror"
                                id="birth_day" name="birth_day" value="{{ old('birth_day') }}" required min="1" max="31"
                                placeholder="DD">
                            @error('birth_day')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="birth_year" class="form-label">Year</label>
                            <input type="number" class="form-control @error('birth_year') is-invalid @enderror"
                                id="birth_year" name="birth_year" value="{{ old('birth_year') }}" required min="1900"
                                max="{{ date('Y') }}" placeholder="YYYY">
                            @error('birth_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="btn btn-primary w-100" :disabled="submitting">
                            <span x-show="!submitting">Complete registration</span>
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
</body>

</html>