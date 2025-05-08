@extends('layouts.app')

@section('title', 'Products Management')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Products Management</h1>
        <button class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add New Product
        </button>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="form-label">Search Products</label>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Category</label>
                    <select class="form-select">
                        <option>All Categories</option>
                        <option>Dresses</option>
                        <option>Tops</option>
                        <option>Accessories</option>
                        <option>Footwear</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option>All Status</option>
                        <option>In Stock</option>
                        <option>Low Stock</option>
                        <option>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sort By</label>
                    <select class="form-select">
                        <option>Newest First</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Best Selling</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label d-block">&nbsp;</label>
                    <button class="btn btn-secondary w-100">
                        <i class="bi bi-funnel me-2"></i>Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        <!-- Product Card 1 -->
        <div class="col-md-3">
            <div class="card product-card h-100">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/300x300" class="card-img-top" alt="Product Image">
                    <span class="badge bg-success position-absolute top-0 end-0 m-2">In Stock</span>
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-1">Summer Floral Dress</h6>
                    <p class="text-muted small mb-2">Category: Dresses</p>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="h5 mb-0">$89.99</span>
                        <small class="text-success">Stock: 45</small>
                    </div>
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 80%"></div>
                    </div>
                    <div class="btn-group w-100">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Card 2 -->
        <div class="col-md-3">
            <div class="card product-card h-100">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/300x300" class="card-img-top" alt="Product Image">
                    <span class="badge bg-warning position-absolute top-0 end-0 m-2">Low Stock</span>
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-1">Classic White T-Shirt</h6>
                    <p class="text-muted small mb-2">Category: Tops</p>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="h5 mb-0">$29.99</span>
                        <small class="text-warning">Stock: 8</small>
                    </div>
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: 20%"></div>
                    </div>
                    <div class="btn-group w-100">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Card 3 -->
        <div class="col-md-3">
            <div class="card product-card h-100">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/300x300" class="card-img-top" alt="Product Image">
                    <span class="badge bg-success position-absolute top-0 end-0 m-2">In Stock</span>
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-1">Leather Handbag</h6>
                    <p class="text-muted small mb-2">Category: Accessories</p>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="h5 mb-0">$129.99</span>
                        <small class="text-success">Stock: 23</small>
                    </div>
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 60%"></div>
                    </div>
                    <div class="btn-group w-100">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Card 4 -->
        <div class="col-md-3">
            <div class="card product-card h-100">
                <div class="position-relative">
                    <img src="https://via.placeholder.com/300x300" class="card-img-top" alt="Product Image">
                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">Out of Stock</span>
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-1">Running Shoes</h6>
                    <p class="text-muted small mb-2">Category: Footwear</p>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="h5 mb-0">$79.99</span>
                        <small class="text-danger">Stock: 0</small>
                    </div>
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: 0%"></div>
                    </div>
                    <div class="btn-group w-100">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </button>
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>

    @section('additional_styles')
        .product-card {
        transition: transform 0.3s;
        }
        .product-card:hover {
        transform: translateY(-5px);
        }
        .product-card .card-img-top {
        height: 200px;
        object-fit: cover;
        }
    @endsection
@endsection