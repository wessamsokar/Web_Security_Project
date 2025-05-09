@extends('layouts.app')

@section('title', $category->name . ' - Products')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ $category->name }}</h1>
            <small class="text-muted">{{ $category->products->count() }} Products</small>
        </div>
        <div>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($category->products as $product)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                </td>
                                <td>#{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form
                                            action="{{ route('categories.remove-product', ['category' => $category->id, 'product' => $product->id]) }}"
                                            method="POST" onsubmit="return confirm('Move this product to Unisex category?');">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="bi bi-box-arrow-right"></i> Move to Unisex
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No products found in this category</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
