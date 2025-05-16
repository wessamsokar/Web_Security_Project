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
                            <button class="btn btn-light position-absolute top-0 start-0 m-2 rounded-circle add-to-favorites"
                                data-product-id="{{ $product->id }}" style="width: 35px; height: 35px; padding: 0;">
                                <i class="bi bi-heart"></i>
                            </button>
                        @endcan
                    </div>
                    <div class="card-body">
                        <h6 class="card-title mb-1">{{ $product->name }}</h6>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="h5 mb-0">${{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-cart-plus me-1"></i>Buy
                            </a>
                        </div>

                        <!-- Buy Modal for each product -->
                        <div class="modal fade" id="buyModal{{ $product->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Buy {{ $product->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Size</label>
                                            <select class="form-select" id="size{{ $product->id }}">
                                                @foreach($product->productSizes as $productSize)
                                                    @if($productSize->quantity > 0)
                                                        <option value="{{ $productSize->size_id }}">
                                                            {{ $productSize->size->name }}
                                                            ({{ $productSize->quantity }} available)
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control" id="quantity{{ $product->id }}" value="1"
                                                min="1" max="{{ $totalStock }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-primary" onclick="addToCart({
                                                            id: {{ $product->id }},
                                                            name: '{{ $product->name }}',
                                                            price: {{ $product->price }},
                                                            image: '{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x300' }}',
                                                            size: document.getElementById('size{{ $product->id }}').value,
                                                            quantity: document.getElementById('quantity{{ $product->id }}').value
                                                        })">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
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
@endsection

@section('scripts')
    <script>
        function addToCart(product) {
            // إضافة المنتج للسلة
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            cart.push(product);
            localStorage.setItem('cart', JSON.stringify(cart));

            // تحديث عداد السلة وعرض محتوياتها
            document.querySelector('.cart-icon .badge').textContent = cart.length;

            // إغلاق النافذة المنبثقة
            let modal = bootstrap.Modal.getInstance(document.getElementById('buyModal' + product.id));
            modal.hide();

            // فتح السلة الجانبية
            document.getElementById('cartSidebar').classList.add('expanded');

            // تحديث محتوى السلة
            updateCartItems();
        }

        function updateCartItems() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const cartItems = document.querySelector('.cart-items');

            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-center text-muted">Your cart is empty</p>';
                return;
            }

            let html = '<div class="list-group list-group-flush">';
            cart.forEach((item, index) => {
                html += `
                                <div class="list-group-item">
                                    <div class="d-flex">
                                        <img src="${item.image}" style="width: 50px; height: 50px; object-fit: contain;" class="me-3">
                                        <div>
                                            <h6 class="mb-0">${item.name}</h6>
                                            <small class="text-muted">
                                                $${item.price} - Size: ${item.size}
                                                <br>Quantity: ${item.quantity}
                                            </small>
                                        </div>
                                    </div>
                                </div>`;
            });
            html += '</div>';
            cartItems.innerHTML = html;
        }

        document.querySelectorAll('.add-to-favorites').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.productId;
                this.querySelector('i').classList.toggle('bi-heart');
                this.querySelector('i').classList.toggle('bi-heart-fill');
                this.querySelector('i').style.color = this.querySelector('i').classList.contains('bi-heart-fill') ? '#ff4081' : '';

                // Update favorites count in navbar
                const favoriteBadge = document.querySelector('.favorites-icon .badge');
                let currentCount = parseInt(favoriteBadge.textContent);
                favoriteBadge.textContent = this.querySelector('i').classList.contains('bi-heart-fill') ?
                    currentCount + 1 :
                    currentCount - 1;
            });
        });
    </script>
@endsection
