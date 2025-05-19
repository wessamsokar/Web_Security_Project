@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">My Orders</h2>

    @forelse ($orders as $order)
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <strong>Order #{{ $order->id }}</strong>
                    <span class="ms-3 text-light">Placed on {{ $order->created_at->format('d M Y') }}</span>
                </div>
                <span class="badge bg-light text-dark">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Image</th>
                                <th scope="col">Price</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->purchases as $purchase)
                                <tr>
                                    <td>{{ $purchase->product->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($purchase->product && $purchase->product->image)
                                            <img src="{{ asset('storage/' . $purchase->product->image) }}" alt="{{ $purchase->product->name }}"
                                                 style="width: 50px; height: 50px; object-fit: contain;">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>${{ number_format($purchase->price, 2) }}</td>
                                    <td>{{ $purchase->quantity }}</td>
                                    <td>${{ number_format($purchase->quantity * $purchase->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-end mt-3">
                    <h5><strong>Total:</strong> ${{ number_format($order->purchases->sum(fn($p) => $p->quantity * $p->price), 2) }}</h5>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            You have no past orders.
        </div>
    @endforelse
</div>
@endsection
