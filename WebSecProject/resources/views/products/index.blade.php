@extends('layouts.app')

@section('title', 'Products Management')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Products Management</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add New Product
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="form-label">Search Products</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Search by name...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">All</option>
                            <option value="men" {{ request('gender') == 'men' ? 'selected' : '' }}>Men</option>
                            <option value="women" {{ request('gender') == 'women' ? 'selected' : '' }}>Women</option>
                            <option value="kids" {{ request('gender') == 'kids' ? 'selected' : '' }}>Kids & Baby</option>
                            <option value="unisex" {{ request('gender') == 'unisex' ? 'selected' : '' }}>Unisex</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="stock_status" class="form-select">
                            <option value="">All Status</option>
                            <option value="in" {{ request('stock_status') == 'in' ? 'selected' : '' }}>In Stock</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-secondary w-100">
                            <i class="bi bi-funnel me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Products List -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-3">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300' }}"
                            class="card-img-top" alt="Product Image"
                            style="height: 250px; object-fit: contain; width: 100%; background: #f8f9fa;">
                        <span
                            class="badge bg-{{ $product->stock == 0 ? 'danger' : ($product->stock < 10 ? 'warning' : 'success') }} position-absolute top-0 end-0 m-2">
                            {{ $product->stock == 0 ? 'Out of Stock' : ($product->stock < 10 ? 'Low Stock' : 'In Stock') }}
                        </span>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title mb-1">{{ $product->name }}</h6>
                        <p class="text-muted small mb-2">Gender: {{ $product->category->gender ?? 'Uncategorized' }}</p>
                        <p class="text-muted small mb-2">Category: {{ $product->category->name ?? 'Uncategorized' }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="h5 mb-0">${{ number_format($product->price, 2) }}</span>
                            <small class="text-muted">Stock: {{ $product->stock }}</small>
                        </div>
                        <div class="progress mb-3" style="height: 6px;">
                            <div class="progress-bar bg-{{ $product->stock == 0 ? 'danger' : ($product->stock < 10 ? 'warning' : 'success') }}"
                                style="width: {{ min(100, $product->stock * 5) }}%">
                            </div>
                        </div>
                        <div class="btn-group w-100">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}"
                                onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </button>
                            </form>
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
@endsection
