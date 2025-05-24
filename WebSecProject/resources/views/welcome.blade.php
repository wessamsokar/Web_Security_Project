@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="container py-4">
        <h2 class="mb-4">Welcome Back, {{ Auth::user()->first_name }}!</h2>

        <!-- Profile Summary -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5>Your Profile</h5>
                    <p class="mb-1"><strong>Name:</strong> {{ Auth::user()->full_name }}</p>
                    <p class="mb-0"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                </div>
                <a href="{{ route('profile') }}" class="btn btn-outline-primary">Edit Profile</a>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Orders</h5>
                            <h3>{{ $ordersCount }}</h3>
                        </div>
                        <i class="bi bi-cart4 fs-1"></i>
                    </div>
                    <a href="{{ route('orders.view') }}" class="card-footer text-white text-decoration-none small">
                        View All Orders <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-danger h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Favorites</h5>
                            <h3>{{ $favoritesCount }}</h3>
                        </div>
                        <i class="bi bi-heart-fill fs-1"></i>
                    </div>
                    <a href="{{ route('favorites.index') }}" class="card-footer text-white text-decoration-none small">
                        View Favorites <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-success h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Cart Items</h5>
                            <h3>{{ $cartCount }}</h3>
                        </div>
                        <i class="bi bi-bag-check-fill fs-1"></i>
                    </div>
                    <a href="{{ route('products.cartIndex') }}" class="card-footer text-white text-decoration-none small">
                        Go to Cart <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-white bg-dark h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Spent</h5>
                            <h3>${{ number_format($totalSpent, 2) }}</h3>
                        </div>
                        <i class="bi bi-wallet2 fs-1"></i>
                    </div>
                    <span class="card-footer text-white small">Based on completed orders</span>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <strong>Recent Orders</strong>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    ${{ number_format($order->purchases->sum(fn($p) => $p->quantity * $p->price), 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No recent orders</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Admin Panel Link -->
        @can('view_users')
            <div class="text-end">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                    Go to Admin Panel <i class="bi bi-shield-lock ms-1"></i>
                </a>
            </div>
        @endcan
</div>
@endsection
