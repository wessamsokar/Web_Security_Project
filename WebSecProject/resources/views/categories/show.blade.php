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
            <!-- Add Search Form -->
            <form method="GET" action="{{ route('categories.show', $category) }}" class="mb-4">
                <div class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <input type="text" name="search_id" class="form-control" placeholder="Search by ID..."
                            value="{{ request('search_id') }}" pattern="[0-9]*">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">

                            <button class="btn btn-dark" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
                            @if(request()->hasAny(['search', 'search_id']))
                                <a href="{{ route('categories.show', $category) }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

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
                                        @if($category->gender !== 'Unisex')
                                        @can('edit_category')
                                            <form
                                                action="{{ route('categories.remove-product', ['category' => $category->id, 'product' => $product->id]) }}"
                                                method="POST" onsubmit="return confirm('Move this product to Unisex category?');">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        @endif
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
