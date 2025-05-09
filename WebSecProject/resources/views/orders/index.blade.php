@extends('layouts.app')

@section('title', 'Orders Management')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Orders Management</h1>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('orders.index') }}" method="GET">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="form-label">Search Orders</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Order ID, Customer...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            @foreach(['Accept', 'Reject'] as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date Range</label>
                        <select name="date_range" class="form-select">
                            <option value="">All Time</option>
                            <option value="last_7_days" {{ request('date_range') == 'last_7_days' ? 'selected' : '' }}>Last 7
                                Days</option>
                            <option value="last_30_days" {{ request('date_range') == 'last_30_days' ? 'selected' : '' }}>Last
                                30 Days</option>
                            <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This
                                Month</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-search me-2"></i>Search
                        </button>
                        @if(request()->hasAny(['search', 'status', 'date_range']))
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#ORD-{{ $order->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">#{{ optional($order->user)->id ?? 'N/A' }}</div>
                                        <div>
                                            <div class="fw-bold">{{ optional($order->user)->name ?? 'Guest' }}</div>
                                            <small class="text-muted">{{ optional($order->user)->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $order->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                                </td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                @can('manage_orders')
                                <td>
                                    @if (!in_array($order->status, ['Accept', 'Reject']))
                                        <form action="{{ route('orders.updateStatus', $order) }}" method="POST"
                                            class="d-flex gap-1">
                                            @csrf
                                            <input type="hidden" name="status" value="">
                                            <button type="submit" class="btn btn-sm btn-success"
                                                onclick="event.preventDefault(); this.closest('form').status.value='Accept'; this.closest('form').submit();">
                                                Accept
                                            </button>
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="event.preventDefault(); this.closest('form').status.value='Reject'; this.closest('form').submit();">
                                                Reject
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="badge bg-{{ $order->status === 'Accept' ? 'success' : 'danger' }}">{{ $order->status }}</span>
                                    @endif
                                </td>
                                @endcan


                                <td><span class="badge bg-success"></span></td>
                                <td>
                                    <div class="btn-group">
                                        @can('view_orders')
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @endcan

                                        @can('delete_orders')
                                        <form action="{{ route('orders.destroy', $order) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->withQueryString()->links() }}
    </div>
@endsection
