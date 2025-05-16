@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Order #ORD-{{ $order->id }}</h1>
            <small class="text-muted">Created at {{ $order->created_at->format('M d, Y g:i A') }}</small>
        </div>
        <div>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Info -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Order Information</h5>
                    <div class="mb-3">
                        <label class="text-muted">Status</label>
                        <div><span class="badge bg-{{ $order->status_color }}">{{ $order->status }}</span></div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Payment Status</label>
                        <div><span class="badge bg-{{ $order->payment_status_color }}">{{ $order->payment_status }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Customer Information</h5>
                    <div class="mb-3">
                        <label class="text-muted">Customer ID</label>
                        <div>#{{ optional($order->user)->id ?? 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Name</label>
                        <div>{{ optional($order->user)->name ?? 'Guest' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Email</label>
                        <div>{{ optional($order->user)->email ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->purchases as $purchase)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $purchase->product->name }}</div>
                                            <small class="text-muted">ID: #{{ $purchase->product->id }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $purchase->product->category->name }}</div>
                                            <small class="text-muted">{{ $purchase->product->category->gender }}</small>
                                        </td>
                                        <td>
                                            @if($purchase->size)
                                                {{ $purchase->size->name }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>${{ number_format($purchase->price, 2) }}</td>
                                        <td>{{ $purchase->quantity }}</td>
                                        <td class="text-end">${{ number_format($purchase->price * $purchase->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold">${{ number_format($order->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Order Timeline</h5>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <div class="fw-bold">Order Created</div>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y g:i A') }}</small>
                            </div>
                        </div>
                        @if($order->status != 'Pending')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <div class="fw-bold">Status Updated to {{ $order->status }}</div>
                                    <small class="text-muted">{{ $order->updated_at->format('M d, Y g:i A') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 1.5rem;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-marker {
            position: absolute;
            left: -0.75rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
        }

        .timeline-content {
            padding-left: 1rem;
            border-left: 2px solid #dee2e6;
        }
    </style>
@endpush
