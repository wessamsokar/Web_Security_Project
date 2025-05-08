@extends('layouts.app')

@section('title', 'Categories Management')

@section('content')
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Categories Management</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bi bi-plus-lg me-2"></i>Add New Category
        </button>
    </div>

    <div class="row g-4">
        @foreach($categories as $category)
            <div class="col-md-4">
                <div class="category-card p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="category-icon text-primary me-3">
                            <i class="bi bi-tags"></i> {{-- ممكن تبدلي الأيقونة حسب نوع التصنيف --}}
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $category->name }}</h5>
                            <small class="text-muted">{{ $category->products_count }} Products</small>
                        </div>
                        <div class="ms-auto">
                            <div class="dropdown">
                                <button class="btn btn-link text-muted" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('categories.show', $category) }}">
                                            <i class="bi bi-eye me-2"></i>View Products</a></li>
                                    <li>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: {{ min(100, $category->products_count * 2) }}%">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <small class="text-muted">Active Products</small>
                        <small class="text-primary">{{ min(100, $category->products_count * 2) }}%</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
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
