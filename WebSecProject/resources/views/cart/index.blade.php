@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('additional_styles')
    <style>
        .cart-item {
            background: #fff;
            border: 1px solid #eee;
            margin-bottom: 1rem;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .summary-card {
            background: #fff;
            border: 1px solid #eee;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 80px;
        }

        .quantity-input {
            width: 70px !important;
            text-align: center;
        }

        .btn-remove {
            color: #dc3545;
            background: none;
            border: none;
            padding: 5px;
            transition: all 0.3s;
        }

        .btn-remove:hover {
            color: #bd2130;
            transform: scale(1.1);
        }

        .btn-checkout {
            background: #1a237e;
            border: none;
            width: 100%;
            padding: 0.8rem;
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Shopping Cart</h2>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="cart-items">
                    <!-- Cart items will be loaded here -->
                </div>
            </div>
            <div class="col-lg-4">
                <div class="summary-card">
                    <h4 class="mb-4">Order Summary</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal</span>
                        <span class="cart-subtotal">$0.00</span>
                    </div>
                    <hr class="border-secondary">
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                        <strong class="cart-total">$0.00</strong>
                    </div>
                    <button class="btn btn-primary btn-checkout">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            updateCartDisplay();
        });

        function updateCartDisplay() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const cartItems = document.querySelector('.cart-items');
            let total = 0;

            if (cart.length === 0) {
                cartItems.innerHTML = `
                    <div class="text-center p-5">
                        <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                        <h4>Your cart is empty</h4>
                        <p class="text-muted">Add some products to your cart and they will appear here.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
                    </div>`;
                updateTotals(0);
                return;
            }

            let html = '';
            cart.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                html += `
                    <div class="cart-item" style="border-bottom: 1px solid #eee; margin-bottom: 15px; padding-bottom: 15px;">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="${item.image}" alt="${item.name}"
                                     class="img-fluid rounded"
                                     style="width: 60px; height: 60px; object-fit: contain;">
                            </div>
                            <div class="col-md-4">
                                <h5 class="mb-1">${item.name}</h5>
                                <p class="mb-1 text-muted">Size: ${item.size}</p>
                                <p class="mb-0">$${item.price}</p>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(${index}, ${item.quantity - 1})">-</button>
                                    <input type="number" class="form-control form-control-sm mx-2 quantity-input"
                                           value="${item.quantity}" min="1"
                                           onchange="updateQuantity(${index}, this.value)">
                                    <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity(${index}, ${item.quantity + 1})">+</button>
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <p class="mb-0 fw-bold">$${itemTotal.toFixed(2)}</p>
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn-remove" onclick="removeItem(${index})">
                                    <i class="bi bi-trash fs-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>`;
            });

            cartItems.innerHTML = html;
            updateTotals(total);
            updateNavCartBadge();
        }

        function updateTotals(total) {
            document.querySelector('.cart-subtotal').textContent = '$' + total.toFixed(2);
            document.querySelector('.cart-total').textContent = '$' + total.toFixed(2);
        }

        function updateQuantity(index, newQuantity) {
            if (newQuantity < 1) return;
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            cart[index].quantity = parseInt(newQuantity);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartDisplay();
        }

        function removeItem(index) {
            if (confirm('Are you sure you want to remove this item?')) {
                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                cart.splice(index, 1);
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartDisplay();
            }
        }

        function updateNavCartBadge() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            document.querySelector('.cart-icon .badge').textContent = cart.length;
        }
    </script>
@endsection