@extends('layouts.app')
@section('title', 'Welcome to Fashion Store Dashboard')
@section('content')
    <!-- Welcome Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 mb-3">Welcome to Fashion Store Dashboard</h1>
        <p class="lead text-muted">Manage your fashion store with our powerful dashboard</p>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card p-4">
                <h6 class="text-muted mb-3">Total Orders</h6>
                <h3 class="mb-2">{{ $totalOrders ?? 0 }}</h3>

            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card p-4">
                <h6 class="text-muted mb-3">Total Products</h6>
                <h3 class="mb-2">{{ $totalProducts ?? 0 }}</h3>

            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card p-4">
                <h6 class="text-muted mb-3">Total Revenue</h6>
                <h3 class="mb-2">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Add New Product
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-cart3 me-2"></i>View Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
