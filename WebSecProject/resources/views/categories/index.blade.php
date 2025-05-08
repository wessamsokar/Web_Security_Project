@extends('layouts.app')

@section('title', 'Categories Management')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Categories Management</h1>
        <button class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Add New Category
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="row g-4">
        <!-- Category Card 1 -->
        <div class="col-md-4">
            <div class="category-card p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="category-icon text-primary me-3">
                        <i class="bi bi-dress-front"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Dresses</h5>
                        <small class="text-muted">45 Products</small>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-link text-muted" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: 70%"></div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <small class="text-muted">Active Products</small>
                    <small class="text-primary">70%</small>
                </div>
            </div>
        </div>

        <!-- Category Card 2 -->
        <div class="col-md-4">
            <div class="category-card p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="category-icon text-success me-3">
                        <i class="bi bi-shirt"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Tops</h5>
                        <small class="text-muted">38 Products</small>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-link text-muted" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: 85%"></div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <small class="text-muted">Active Products</small>
                    <small class="text-success">85%</small>
                </div>
            </div>
        </div>

        <!-- Category Card 3 -->
        <div class="col-md-4">
            <div class="category-card p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="category-icon text-info me-3">
                        <i class="bi bi-handbag"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Accessories</h5>
                        <small class="text-muted">52 Products</small>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-link text-muted" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-info" style="width: 60%"></div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <small class="text-muted">Active Products</small>
                    <small class="text-info">60%</small>
                </div>
            </div>
        </div>

        <!-- Category Card 4 -->
        <div class="col-md-4">
            <div class="category-card p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="category-icon text-warning me-3">
                        <i class="bi bi-boot"></i>
                    </div>
                    <div>
                        <h5 class="mb-1">Footwear</h5>
                        <small class="text-muted">29 Products</small>
                    </div>
                    <div class="ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-link text-muted" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: 75%"></div>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <small class="text-muted">Active Products</small>
                    <small class="text-warning">75%</small>
                </div>
            </div>
        </div>
    </div>

    @section('additional_styles')
        .category-icon {
        width: 48px;
        height: 48px;
        background: #f8f9fa;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        }
    @endsection
@endsection
