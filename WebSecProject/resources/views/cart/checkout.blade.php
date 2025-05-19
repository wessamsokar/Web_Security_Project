@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Checkout</h2>

    <div class="card p-4">
        <h5 class="mb-3">Order Summary</h5>
        <ul class="list-group mb-3">
            @foreach($order->purchases as $item)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                    <strong>${{ number_format($item->quantity * $item->price, 2) }}</strong>
                </li>
            @endforeach
        </ul>
        <div class="d-flex justify-content-between mb-3">
            <strong>Total:</strong>
            <strong>${{ number_format($total, 2) }}</strong>
        </div>

        <form method="POST" action="{{ route('cart.processCheckout') }}">
            @csrf
            <button type="submit" class="btn btn-success w-100">Confirm & Place Order</button>
        </form>
    </div>
</div>
@endsection
