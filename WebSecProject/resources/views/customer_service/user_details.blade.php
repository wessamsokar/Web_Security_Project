@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Customer Details</h1>
        <div>
            <a href="{{ route('customer-service.create-ticket', $user) }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> Create Ticket
            </a>
            <a href="{{ route('customer-service.user-search') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Search
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-circle" style="font-size: 5rem; color: #e0e0e0;"></i>
                        <h4 class="mt-3">{{ $user->name }}</h4>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                    </div>
                    
                    <div class="customer-info mt-4">
                        <div class="row mb-3">
                            <div class="col-5 text-muted">Customer ID:</div>
                            <div class="col-7 text-dark">#{{ $user->id }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 text-muted">First Name:</div>
                            <div class="col-7 text-dark">{{ $user->first_name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 text-muted">Last Name:</div>
                            <div class="col-7 text-dark">{{ $user->last_name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 text-muted">Birth Date:</div>
                            <div class="col-7 text-dark">{{ $user->birth_date ? $user->birth_date->format('M d, Y') : 'Not provided' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5 text-muted">Joined:</div>
                            <div class="col-7 text-dark">{{ $user->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Overview</h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-cart3 text-primary me-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="text-xs text-muted">Total Orders</div>
                                    <div class="font-weight-bold">{{ $orders->count() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-ticket text-warning me-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="text-xs text-muted">Total Tickets</div>
                                    <div class="font-weight-bold">{{ $tickets->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-currency-dollar text-success me-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="text-xs text-muted">Total Spent</div>
                                    <div class="font-weight-bold">${{ $orders->sum('total') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-check text-info me-2" style="font-size: 1.5rem;"></i>
                                <div>
                                    <div class="text-xs text-muted">Last Order</div>
                                    <div class="font-weight-bold">
                                        {{ $orders->count() > 0 ? $orders->first()->created_at->format('M d, Y') : 'Never' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Tickets Tab Panel -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="customerTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tickets-tab" data-bs-toggle="tab" data-bs-target="#tickets" type="button">
                                <i class="bi bi-ticket me-1"></i>
                                Support Tickets
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button">
                                <i class="bi bi-cart3 me-1"></i>
                                Order History
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="customerTabContent">
                        <!-- Tickets Tab -->
                        <div class="tab-pane fade show active" id="tickets" role="tabpanel" aria-labelledby="tickets-tab">
                            @if($tickets->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tickets as $ticket)
                                                <tr>
                                                    <td>#{{ $ticket->id }}</td>
                                                    <td>{{ Str::limit($ticket->subject, 40) }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $ticket->status_color }}">
                                                            {{ $ticket->status }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div>{{ $ticket->created_at->format('M d, Y') }}</div>
                                                        <small class="text-muted">{{ $ticket->created_at->format('g:i A') }}</small>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-info">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-ticket fa-3x mb-3 text-gray-300"></i>
                                    <p class="text-gray-500 mb-0">No support tickets found</p>
                                    <a href="{{ route('customer-service.create-ticket', $user) }}" class="btn btn-primary mt-3">
                                        Create New Ticket
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Orders Tab -->
                        <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                            @if($orders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Date</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td>#ORD-{{ $order->id }}</td>
                                                    <td>
                                                        <div>{{ $order->created_at->format('M d, Y') }}</div>
                                                        <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
                                                    </td>
                                                    <td>${{ $order->total }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $order->status === 'Accept' ? 'success' : 'secondary' }}">
                                                            {{ $order->status }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-cart-x fa-3x mb-3 text-gray-300"></i>
                                    <p class="text-gray-500">No orders found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection