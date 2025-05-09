@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Users Management</h1>
            @can('create users')
                <a href="{{ route('users.create') }}" class="btn btn-secondary">
                    <i class="bi bi-plus-circle"></i> Create New User
                </a>
            @endcan
        </div>

        <!-- Advanced Search Form -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('users.index') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search by ID..."
                                value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="role" class="form-select">
                                <option value="">Filter by Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark"><i class="bi bi-search me-2"> Search </i>
                            </button>
                            @if(request()->hasAny(['search', 'role']))
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('view users')
                                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                            @can('edit users')
                                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan
                                            @can('delete users')
                                                @if($user->id !== Auth::id() && !($user->roles->contains('name', 'Super Admin') && $user->id === App\Models\User::whereHas('roles', function ($q) {
                                                    $q->where('name', 'Super Admin'); })->orderBy('id')->first()->id))
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $users->withQueryString()->links() }}
    </div>

@endsection
