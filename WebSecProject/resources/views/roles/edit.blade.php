@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Role: {{ $role->name }}</h5>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back to Roles
                    </a>
                </div>


                <div class="card-body">
                    @if($role->name === 'Super Admin')
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            The Super Admin role cannot be modified for security reasons.
                        </div>
                    @else
                        <form method="POST" action="{{ route('roles.update', $role) }}">
                            @csrf
                            @method('PUT')

                            <!-- Role Name (Read Only) -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">Role Name</label>
                                <input type="text" class="form-control bg-light"
                                       id="name" value="{{ $role->name }}" readonly disabled>
                                <input type="hidden" name="name" value="{{ $role->name }}">
                                <small class="text-muted">Role name cannot be modified for security reasons</small>
                            </div>

                            <!-- Permissions -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Permissions</label>
                                <div class="row g-3">
                                    @foreach($permissions as $permission)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input"
                                                       name="permissions[]" value="{{ $permission->id }}"
                                                       id="permission_{{ $permission->id }}"
                                                       {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('permissions')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Save Changes
                                </button>
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Users Section -->
            <div class="card mt-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Users with this Role</h5>
                    <span class="badge bg-white text-dark">
                        Total Users: {{ $users->total() }}
                    </span>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <!-- Search Form -->
                        <form action="{{ route('roles.edit', $role) }}" method="GET" class="mb-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search by ID..." value="{{ $search ?? '' }}">
                                <button class="btn btn-dark" type="submit">
                                    <i class="bi bi-search"></i> Search
                                </button>
                                @if(request()->has('search'))
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-secondary">
                                        <i class="bi bi-x-circle"></i> Clear
                                    </a>
                                @endif
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Join Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-person-circle me-2"></i>
                                                    {{ $user->name }}
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                @if($role->name === 'Super Admin' && $user->id === 1)
                                                    <span class="badge bg-warning">Cannot remove the first Super Admin</span>
                                                @elseif($role->name !== 'Super Admin' || auth()->id() !== $user->id)
                                                    <form action="{{ route('roles.remove-user', $role) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to remove this user from the role? They will be assigned the Customer role if they have no other roles.')">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bi bi-person-x-fill"></i> Remove from Role
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-warning">Cannot remove yourself from this role</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            No users currently have this role.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
    // Auto-hide toast after 3 seconds
    setTimeout(function() {
        $('.toast').toast('hide');
    }, 3000);
</script>
@endsection
