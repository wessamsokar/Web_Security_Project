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
                <div class="card shadow-lg border-0 rounded-4 p-4" style="max-width: 480px; width: 100%;">
                    <div class="card-body">
                        <div class="text-muted small mb-2">Step 2 of 2</div>
                        <h2 class="fw-bold mb-2">Complete your profile</h2>

                        <form method="POST" action="{{ route('register.complete') }}">
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
                                    <input type="text"
                                        class="form-control form-control-lg @error('first_name') is-invalid @enderror"
                                        id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                        autocomplete="given-name" placeholder="Enter your first name">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last name</label>
                                    <input type="text"
                                        class="form-control form-control-lg @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                        autocomplete="family-name" placeholder="Enter your last name">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="birth_month" class="form-label">Month</label>
                                    <select
                                        class="form-select form-select-lg @error('birth_month') is-invalid @enderror"
                                        id="birth_month" name="birth_month" required>
                                        <option value="">Select month</option>
                                        <option value="01" {{ old('birth_month') == '01' ? 'selected' : '' }}>January
                                        </option>
                                        <option value="02" {{ old('birth_month') == '02' ? 'selected' : '' }}>February
                                        </option>
                                        <option value="03" {{ old('birth_month') == '03' ? 'selected' : '' }}>March
                                        </option>
                                        <option value="04" {{ old('birth_month') == '04' ? 'selected' : '' }}>April
                                        </option>
                                        <option value="05" {{ old('birth_month') == '05' ? 'selected' : '' }}>May</option>
                                        <option value="06" {{ old('birth_month') == '06' ? 'selected' : '' }}>June
                                        </option>
                                        <option value="07" {{ old('birth_month') == '07' ? 'selected' : '' }}>July
                                        </option>
                                        <option value="08" {{ old('birth_month') == '08' ? 'selected' : '' }}>August
                                        </option>
                                        <option value="09" {{ old('birth_month') == '09' ? 'selected' : '' }}>September
                                        </option>
                                        <option value="10" {{ old('birth_month') == '10' ? 'selected' : '' }}>October
                                        </option>
                                        <option value="11" {{ old('birth_month') == '11' ? 'selected' : '' }}>November
                                        </option>
                                        <option value="12" {{ old('birth_month') == '12' ? 'selected' : '' }}>December
                                        </option>
                                    </select>
                                    @error('birth_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="birth_day" class="form-label">Day</label>
                                    <input type="number"
                                        class="form-control form-control-lg @error('birth_day') is-invalid @enderror"
                                        id="birth_day" name="birth_day" value="{{ old('birth_day') }}" min="1" max="31"
                                        required placeholder="Enter day">
                                    @error('birth_day')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="birth_year" class="form-label">Year</label>
                                    <select class="form-select form-select-lg @error('birth_year') is-invalid @enderror"
                                        id="birth_year" name="birth_year" required>
                                        <option value="">Select year</option>
                                        @for($year = date('Y') - 100; $year <= date('Y') - 13; $year++)
                                            <option value="{{ $year }}" {{ old('birth_year') == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('birth_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-4">
                                Complete Registration
                            </button>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
