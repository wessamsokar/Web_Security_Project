@extends('layouts.app')

@section('title', 'Customer Search')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Customer Search</h1>
        <a href="{{ route('customer-service.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Search Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('customer-service.user-search') }}" method="GET">
                <div class="row g-2">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Search by name, email, or ID..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Card -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Customer Results</h6>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
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
                                    <td>#{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('customer-service.user-details', $user) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            <a href="{{ route('customer-service.create-ticket', $user) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-ticket"></i> Create Ticket
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    @if(request('search'))
                        <i class="bi bi-search fa-3x mb-3 text-gray-300"></i>
                        <p class="text-gray-500">No customers found matching "{{ request('search') }}"</p>
                    @else
                        <i class="bi bi-people fa-3x mb-3 text-gray-300"></i>
                        <p class="text-gray-500">Enter a search term to find customers</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection