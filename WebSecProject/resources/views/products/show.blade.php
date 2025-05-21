@extends('layouts.app')

@section('title', $product->name)

@section('content')
    <div class="container py-4">
        <div class="mb-3">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Back to Products
            </a>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row gx-md-5">
            <div class="col-md-6">
                <div class="position-relative mb-4 text-center">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/600x600?text=No+Image' }}"
                        class="img-fluid rounded shadow-sm" alt="{{ $product->name }}"
                        style="max-height: 500px; object-fit: contain; width: 100%; background: #f8f9fa;" loading="lazy">

                    @can('buy_product')
                        <form action="{{ route('favorites.toggle') }}" method="POST"
                            class="position-absolute top-0 start-0 m-3 z-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-light rounded-circle shadow-sm favorite-btn"
                                style="width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center;"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ $product->is_favorited ? 'Remove from Favorites' : 'Add to Favorites' }}">
                                <i id="favIcon" class="bi bi-heart{{ $product->is_favorited ? '-fill' : '' }}"
                                    style="color: {{ $product->is_favorited ? '#ff4081' : '' }}; font-size: 1.2rem;"></i>
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="mb-2 display-6">{{ $product->name }}</h1>
                <p class="text-muted fs-5 mb-3">{{ $product->category->name ?? 'Uncategorized' }}</p>
                <h2 class="text-primary mb-4 display-5">${{ number_format($product->price, 2) }}</h2>

                <p class="mb-4 pb-3 border-bottom">{{ $product->description }}</p>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div class="mb-4">
                        <label class="form-label d-block">Size</label>
                        @if($sizes->count() > 0)
                            @foreach($sizes as $size)
                                @php
                                    $productSize = $product->productSizes->where('size_id', $size->id)->first();
                                    $quantity = $productSize ? $productSize->quantity : 0;
                                    $isDisabled = $quantity == 0;
                                @endphp
                                <div class="form-check form-check-inline me-3">
                                    <input class="form-check-input" type="radio" name="size_id" id="size{{ $size->id }}" value="{{ $size->id }}"
                                        {{ $isDisabled ? 'disabled' : '' }} {{ ($loop->first && !$isDisabled) ? 'checked' : '' }} required>
                                    <label class="form-check-label {{ $isDisabled ? 'text-muted' : '' }}" for="size{{ $size->id }}">
                                        {{ $size->name }}
                                        @if($isDisabled)
                                            <span class="text-danger">(Out of Stock)</span>
                                        @else
                                            ({{ $quantity }} available)
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No sizes defined for this product type.</p>
                        @endif
                        @error('size_id')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control w-auto" id="quantity" name="quantity" value="1" min="1"
                                max="{{ $totalStock > 0 ? $totalStock : 1 }}" {{ $totalStock == 0 ? 'disabled' : '' }} required>
                        @error('quantity')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                         @if($totalStock > 0)
                            <span class="text-success ms-2">In Stock</span>
                        @else
                            <span class="text-danger ms-2">Out of Stock</span>
                        @endif
                    </div>

                    @if($totalStock > 0)
                        <button type="submit" class="btn btn-primary btn-lg mt-3">
                            <i class="bi bi-cart-plus me-2"></i>Add to Cart
                        </button>
                    @else
                        <button type="button" class="btn btn-secondary btn-lg mt-3" disabled>Out of Stock</button>
                    @endif

                </form>

                <div class="mt-5 pt-4 border-top">
                    @can('edit_products')
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-secondary me-2">Edit Product</a>
                    @endcan
                    @can('delete_products')
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Delete Product</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
