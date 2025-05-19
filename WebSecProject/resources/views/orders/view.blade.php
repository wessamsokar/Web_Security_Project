@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">My Orders</h2>

    @forelse ($orders as $order)
        <div class="card mb-4">
            <div class="card-header">
                <strong>Order #{{ $order->id }}</strong> — Placed on {{ $order->created_at->format('d M Y') }}
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach ($order->purchases as $purchase)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $purchase->product->name ?? 'Product' }} (x{{ $purchase->quantity }})
                            <span>${{ number_format($purchase->quantity * $purchase->price, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="text-end mt-2">
                    <strong>Total: ${{ number_format($order->purchases->sum(fn($p) => $p->quantity * $p->price), 2) }}</strong>
                </div>
            </div>
        </div>
    @empty
        <p>You have no past orders.</p>
    @endforelse
</div>
@endsection
