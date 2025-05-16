@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('password_success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('password_success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif


            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold mb-0"><i class="bi bi-person-circle me-2"></i>Your Profile</h3>
                            <small class="text-muted">Manage your personal details</small>
                        </div>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </button>
                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            <i class="bi bi-lock-fill me-1"></i> Change Password
                        </button>
                    </div>

                    <div class="row g-4">
                        <div class="col-sm-6">
                            <label class="form-label text-muted">Full Name</label>
                            <div class="fw-semibold">{{ $user->first_name }} {{ $user->last_name }}</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted">Username</label>
                            <div class="fw-semibold">{{ $user->name }}</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted">Email</label>
                            <div class="fw-semibold">{{ $user->email }}</div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label text-muted">Birth Date</label>
                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('profile.update') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
                @csrf
                @method('PUT')
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title" id="editProfileModalLabel"><i class="bi bi-pencil-fill me-2"></i>Edit Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('profile.change-password') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            @method('PUT')
            <div class="modal-header bg-warning text-dark rounded-top-4">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="bi bi-shield-lock-fill me-2"></i>Change Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer bg-light rounded-bottom-4 px-4 py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-lock-fill me-1"></i> Change Password
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
