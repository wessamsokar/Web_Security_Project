@extends('layouts.app')

@section('title', 'Products Management')

@section('content')
    <!-- Page Header -->
    @can('create_products')
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">Products Management</h1>

            <a href="{{ route('products.create') }}" class="btn btn-secondary">
                <i class="bi bi-plus-lg me-2"></i>Add New Product
            </a>
        </div>
        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                placeholder="Search by name...">
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @can('view_products')
                            <div class="col-md-2">
                                <select name="gender" class="form-select">
                                    <option value="">All</option>
                                    <option value="men" {{ request('gender') == 'men' ? 'selected' : '' }}>Men</option>
                                    <option value="women" {{ request('gender') == 'women' ? 'selected' : '' }}>Women</option>
                                    <option value="kids" {{ request('gender') == 'kids' ? 'selected' : '' }}>Kids & Baby</option>
                                    <option value="unisex" {{ request('gender') == 'unisex' ? 'selected' : '' }}>Unisex</option>
                                </select>
                            </div>
                        @endcan

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-search me-2"></i>Search
                            </button>
                            @if(request()->hasAny(['search', 'stock_status', 'category', 'gender']))
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endcan
    <!-- Products List -->
    <div class="row g-4">
        @forelse($products as $product)
            @php
                $totalStock = $product->productSizes ? $product->productSizes->sum('quantity') : 0;
            @endphp
            <div class="col-md-3">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300' }}"
                            class="card-img-top" alt="Product Image"
                            style="height: 250px; object-fit: contain; width: 100%; background: #f8f9fa;">
                        @if($totalStock == 0)
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">Out of Stock</span>
                        @endif
                        @can('buy_product')
                            <form action="{{ route('favorites.toggle') }}" method="POST"
                                class="position-absolute top-0 start-0 m-2">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn btn-light rounded-circle favorite-btn"
                                    style="width: 35px; height: 35px; padding: 0;" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ $product->is_favorited ? 'Remove from Favorites' : 'Add to Favorites' }}">
                                    <i class="bi bi-heart{{ $product->is_favorited ? '-fill' : '' }}"
                                        style="color: {{ $product->is_favorited ? '#ff4081' : '' }}"></i>
                                </button>
                            </form>
                        @endcan
                    </div>
                    <div class="card-body">
                        <h6 class="card-title mb-1">{{ $product->name }}</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="h5 mb-0">${{ number_format($product->price, 2) }}</span>
                            @role('Customer')
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-cart-plus me-1"></i>Buy
                            </a>
                            @endrole
                        </div>

                        <div class="btn-group w-100">
                            @can('edit_products')
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </a>
                            @endcan
                            @can('delete_products')
                                <form method="POST" action="{{ route('products.destroy', $product) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p>No products found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $products->withQueryString()->links() }}
    </div>

    <!-- Cart Sidebar -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Shopping Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="cart-items">
                <!-- Cart items will be loaded here -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Handle favorites toggle
        document.querySelectorAll('.favorite-btn').forEach(button => {
            button.addEventListener('click', function (e) {

            });
        });
    </script>
@endsection
