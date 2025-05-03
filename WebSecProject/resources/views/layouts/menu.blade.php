<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Example</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>

<body>
<nav class="navbar navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <!-- Dashboard Toggle Button -->
        <button class="btn btn-primary d-flex align-items-center gap-2" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#dashboardMenu">
            <i class="bi bi-grid-3x3-gap-fill"></i>
            Dashboard
        </button>
    </div>
</nav>

<!-- Dashboard Offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="dashboardMenu">
    <div class="offcanvas-header">
        
        <h5 class="offcanvas-title">
            <i class="bi bi-speedometer2 me-2"></i>
            Dashboard Menu
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    
    <div class="offcanvas-body p-3">
        <nav class="nav flex-column">
            <small class="text-muted text-uppercase mt-2 mb-1">Main</small>
            
            <a class="nav-link active" href="./">
                <i class="bi bi-house-door me-2"></i>
                Home
            </a>

            @auth
            
            <small class="text-muted text-uppercase mt-3 mb-1">Features</small>
            
            <a class="nav-link" href="{{ route('products_list')}}">
                <i class="bi bi-bag me-2"></i>
                Products
            </a>
            
            <a class="nav-link" href="{{route('users_list') }}">
                <i class="bi bi-people me-2"></i>
                Users
            </a>
            
            @can('manage_roles')
            <a class="nav-link" href="{{ route('roles_list') }}">
                <i class="bi bi-shield-lock me-2"></i>
                Roles Management
            </a>
            @endcan

            @can('manage_roles')
            <a class="nav-link" href="{{ route('permissions_list') }}">
                <i class="bi bi-key me-2"></i>
                Permissions Management
            </a>
            @endcan

            <small class="text-muted text-uppercase mt-3 mb-1">Account</small>
            <a class="nav-link" href="{{ route('profile') }}">
                <i class="bi bi-person-circle me-2"></i>
                {{ auth()->user()->name }}
            </a>
            <a class="nav-link text-danger" href="{{ route('do_logout') }}">
                <i class="bi bi-box-arrow-right me-2"></i>
                Logout
            </a>
            @else
            <small class="text-muted text-uppercase mt-3 mb-1">Account</small>
            <a class="nav-link" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Login
            </a>
            <a class="nav-link" href="{{ route('register') }}">
                <i class="bi bi-person-plus me-2"></i>
                Register
            </a>
            @endauth
        </nav>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>