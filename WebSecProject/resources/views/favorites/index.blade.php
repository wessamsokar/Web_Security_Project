@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">My Favorites</h2>

        <div class="favorites-items">
            <div class="row g-4">
                @forelse($favorites as $fav)
                    <div class="col-md-3">
                        <div class="card h-100">
                            <img src="{{ $fav->product->image ? asset('storage/' . $fav->product->image) : 'https://via.placeholder.com/300x300' }}"
                                 class="card-img-top" alt="{{ $fav->product->name }}"
                                 style="height: 200px; object-fit: contain; padding: 1rem;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $fav->product->name }}</h5>
                                <p class="card-text">${{ number_format($fav->product->price, 2) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('products.show', $fav->product->id) }}" class="btn btn-primary btn-sm">View Details</a>
                                    <form action="{{ route('favorites.destroy', $fav->product->id) }}" method="POST" onsubmit="return confirm('Remove from favorites?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-heart-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-heart fs-1 text-muted mb-3"></i>
                        <h4>Your favorites list is empty</h4>
                        <p class="text-muted">Add items to your favorites list and they will appear here.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
