@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- صورة المنتج مع أيقونة المفضلة -->
            <div class="col-md-6 position-relative">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/600x600' }}"
                     class="img-fluid rounded" alt="{{ $product->name }}"
                     style="width: 100%; height: 500px; object-fit: contain; background: #f8f9fa;">

                <!-- زر القلب أعلى الصورة -->
                <button type="button"
                        class="btn position-absolute top-0 end-0 m-3 p-2"
                        onclick="toggleFavorite({{ $product->id }})"
                        id="favBtn"
                        style="background-color: rgba(255,255,255,0.9); border-radius: 50%;">
                    <i class="bi {{ $isFavorited ? 'bi-heart-fill' : 'bi-heart' }}" id="favIcon"></i>
                </button>
            </div>

            <!-- تفاصيل المنتج -->
            <div class="col-md-6">
                <h1 class="h2 mb-4">{{ $product->name }}</h1>
                <h3 class="mb-4 text-primary">${{ number_format($product->price, 2) }}</h3>
                <p class="text-muted mb-5">{{ $product->description }}</p>

                @if($totalStock > 0)
                    <div class="mb-4">
                        <h5 class="mb-3">Sizes:</h5>
                        <div class="d-flex gap-2">
                            @foreach($sizes as $size)
                                @php
                                    $productSize = $product->productSizes->where('size_id', $size->id)->first();
                                @endphp
                                <div>
                                    <input type="radio" class="btn-check" name="size" id="size{{ $size->id }}"
                                           value="{{ $size->id }}" {{ ($productSize && $productSize->quantity > 0) ? '' : 'disabled' }}
                                           autocomplete="off">
                                    <label
                                        class="btn {{ ($productSize && $productSize->quantity > 0) ? 'btn-outline-dark' : 'btn-light text-muted' }} px-4"
                                        for="size{{ $size->id }}" style="min-width: 60px">
                                        {{ $size->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex gap-3 align-items-center">
                            <div style="width: 120px">
                                <input type="number" class="form-control rounded-0" id="quantity" value="1" min="1">
                            </div>
                            <button type="button" class="btn btn-dark rounded-0 px-5" onclick="addToCart()">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                @else
                    <div class="alert alert-secondary rounded-0">
                        This product is currently out of stock
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function addToCart() {
            const sizeId = document.querySelector('input[name="size"]:checked')?.value;
            if (!sizeId) {
                alert('Please select a size');
                return;
            }

            const quantity = parseInt(document.getElementById('quantity').value);

            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: {{ $product->id }},
                    size_id: sizeId,
                    quantity: quantity
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route('cart.index') }}';
                } else {
                    alert('Something went wrong.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error occurred while adding to cart.');
            });
        }

        function toggleFavorite(productId) {
            const icon = document.getElementById("favIcon");

            fetch("/favorites", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(res => {
                if (res.status === 409) {
                    return fetch(`/favorites/${productId}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        icon.classList.remove('bi-heart-fill');
                        icon.classList.add('bi-heart');
                    });
                } else if (res.ok) {
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');
                } else {
                    throw new Error("Unexpected error");
                }
            })
            .catch(err => {
                console.error(err);
                alert("Something went wrong.");
            });
        }

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endsection

