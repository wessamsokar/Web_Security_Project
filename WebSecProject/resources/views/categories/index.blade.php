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

    <!-- Search & Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('categories.index') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Search Categories</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                            placeholder="Search by name...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Filter by Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">All Genders</option>
                            <option value="Men" {{ request('gender') == 'Men' ? 'selected' : '' }}>Men</option>
                            <option value="Women" {{ request('gender') == 'Women' ? 'selected' : '' }}>Women</option>
                            <option value="Kids & Baby" {{ request('gender') == 'Kids & Baby' ? 'selected' : '' }}>Kids & Baby
                            </option>
                            <option value="Unisex" {{ request('gender') == 'Unisex' ? 'selected' : '' }}>Unisex</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            @if(request()->hasAny(['search', 'gender']))
                                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @foreach($categories as $category)
            <div class="col-md-4">
                <div class="category-card p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="category-icon text-primary me-3">
                            <i class="bi bi-tags"></i>
                        </div>
                        <div>
                            <small class="text-muted mb-1 d-block">
                                <i
                                    class="bi {{ $category->gender == 'Men' ? 'bi-gender-male' : ($category->gender == 'Women' ? 'bi-gender-female' : 'bi-gender-ambiguous') }}"></i>
                                {{ ucfirst($category->gender) }}
                            </small>
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
                                        <form action="{{ route('categories.destroy', ['category' => $category->id]) }}"
                                            method="POST" onsubmit="return confirm('Are you sure?');">
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
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="Men">Men</option>
                                <option value="Women">Women</option>
                                <option value="Kids & Baby">Kids & Baby</option>
                                <option value="Unisex">Unisex</option>
                            </select>
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
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $categories->links() }}
    </div>
@endsection
