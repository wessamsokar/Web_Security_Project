@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Shopping Cart</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="cart-items">
                    @forelse ($cartItems as $item)
                        <div class="cart-item"
                            style="border-bottom: 1px solid #eee; margin-bottom: 15px; padding-bottom: 15px;">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img src="{{ $item->product->image ? Storage::url($item->product->image) : asset('images/default.png') }}"
                                        alt="{{ $item->product->name ?? 'Product' }}" class="img-fluid rounded"
                                        style="width: 60px; height: 60px; object-fit: contain;">
                                </div>
                                <div class="col-md-4">
                                    <h5 class="mb-1">{{ $item->product->name ?? 'Unnamed Product' }}</h5>
                                    <p class="mb-0">${{ number_format($item->product->price, 2) }}</p>
                                    @if($item->size)
                                        <small class="text-muted">Size: {{ $item->size->name }}</small>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                            class="form-control me-2" style="width: 80px;">
                                        <button class="btn btn-sm btn-outline-primary">Update</button>
                                    </form>
                                    <form method="POST" action="{{ route('cart.favorite', $item->id) }}">
                                        @csrf
                                        <button class="btn btn-outline-warning btn-sm mt-2" type="submit">
                                            <i class="bi bi-heart"></i> Favorite
                                        </button>
                                    </form>

                                </div>
                                <div class="col-md-3 text-end">
                                    <p class="mb-1 fw-bold">${{ number_format($item->quantity * $item->product->price, 2) }}</p>
                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Remove this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger mt-1">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-5">
                            <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                            <h4>Your cart is empty</h4>
                            <p class="text-muted">Add some products to your cart and they will appear here.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-4">
                <div class="summary-card">
                    <h4 class="mb-4">Order Summary</h4>

                    @php
                        $total = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
                    @endphp

                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <span class="cart-subtotal">${{ number_format($total, 2) }}</span>
                    </div>
                    <hr class="border-secondary">
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                        <strong class="cart-total">${{ number_format($total, 2) }}</strong>
                    </div>

                    <a href="{{ route('cart.checkout') }}" class="btn btn-primary btn-checkout w-100">
                        Proceed to Checkout
                    </a>

                </div>
            </div>
        </div>
    </div>
@endsection