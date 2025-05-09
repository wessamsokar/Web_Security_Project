@extends('layouts.app')

@section('title', 'Role Details')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Role Details</h1>
            <div>
                @can('edit roles')
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Role
                    </a>
                @endcan
                <a href="{{ route('roles.index') }}" class="btn btn-secondary ms-2">
                    <i class="bi bi-arrow-left"></i> Back to Roles
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Role Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">Role Name</th>
                                        <td>{{ $role->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>{{ $role->created_at->format('F d, Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ $role->updated_at->format('F d, Y H:i:s') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Users with this Role</th>
                                        <td>{{ $role->users->count() }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Role Permissions</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($role->permissions as $permission)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $permission->name }}
                                    <span class="badge bg-primary rounded-pill">
                                        <i class="bi bi-check-lg"></i>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users with this Role -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Users with this Role</h5>
                <span class="badge bg-white text-dark">
                    Total Users: {{ $users->total() }}
                </span>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form action="{{ route('roles.show', $role) }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by ID..."
                            value="{{ $search ?? '' }}">
                        <button class="btn btn-dark" type="submit">
                            <i class="bi bi-search"></i> Search
                        </button>
                        @if(request()->has('search'))
                            <a href="{{ route('roles.show', $role) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>
                </form>

                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @can('edit users')
                                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                            @endcan
                                            @if(!$user->hasRole('Super Admin') && $user->id !== auth()->id())
                                                @can('delete users')
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No users found.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
