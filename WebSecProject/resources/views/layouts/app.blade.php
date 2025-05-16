@php
    use App\Models\Cart;
    use App\Models\Favorite;
    $cartCount = Cart::where('user_id', Auth::id())->count();
    $favoriteCount = Favorite::where('user_id', Auth::id())->count();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Fashion Store</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            font-family: 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
        }

        /* Top Navbar Styles */
        .top-navbar {
            background: linear-gradient(90deg, #1a237e 0%, #283593 100%);
            height: 60px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 2px 8px rgba(26, 35, 126, 0.2);
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-decoration: none;
            margin-right: 40px;
        }

        .navbar-nav {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            margin: 0 15px;
        }

        .nav-link {
            color: #333;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            padding: 8px 0;
            position: relative;
        }

        .nav-link:hover {
            color: #333;
            background: #f8f9fa;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-icon {
            color: #666;
            font-size: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .navbar-icon:hover {
            color: #333;
            transform: scale(1.1);
        }

        /* Sidebar Styles */
        .sidebar {
            height: calc(100vh - 60px);
            background: linear-gradient(180deg, #1a237e 0%, #283593 100%);
            position: fixed;
            left: -250px;
            top: 60px;
            width: 250px;
            z-index: 1020;
            transition: all 0.3s;
            overflow-y: auto;
        }

        .sidebar.expanded {
            left: 0;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            font-size: 14px;
            border: none;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-weight: 500;
        }

        .sidebar .nav-link i {
            color: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i {
            color: #fff;
        }

        /* Update logo colors for dark sidebar */
        .sidebar .brand {
            color: #fff;
        }

        .sidebar .logo {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* Submenu styles */
        .submenu {
            list-style: none;
            padding: 0;
            margin: 0;
            background: rgba(0, 0, 0, 0.1);
            display: none;
        }

        .submenu.show {
            display: block;
        }

        .submenu .nav-link {
            padding-left: 48px !important;
            font-size: 13px;
            color: rgba(255, 255, 255, 7);
        }

        .has-submenu {
            position: relative;
            border-bottom: none !important;
        }

        .has-submenu::after {
            content: '\F282';
            font-family: 'bootstrap-icons';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            opacity: 0.7;
        }

        .has-submenu.active::after {
            transform: translateY(-50%) rotate(180deg);
            color: #fff;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 0;
            margin-top: 60px;
            padding: 30px;
            transition: all 0.3s;
        }

        .main-content.shifted {
            margin-left: 250px;
        }

        /* Menu Toggle Button */
        .menu-toggle {
            background: none;
            border: none;
            padding: 0;
            margin-right: 20px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 20px;
            height: 16px;
        }

        .menu-toggle span {
            display: block;
            width: 100%;
            height: 2px;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s;
        }

        .menu-toggle:hover span {
            background: #fff;
        }

        .menu-toggle.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .menu-toggle.active span:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* Common Card Styles */
        .settings-card,
        .report-card,
        .category-card,
        .stat-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border: 1px solid #eee;
        }

        .card {
            border-radius: 8px;
            border: 1px solid #eee;
        }

        /* Settings Specific Styles */
        .settings-nav .nav-link {
            color: #333;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        .settings-nav .nav-link:hover {
            background: #f8f9fa;
        }

        .settings-nav .nav-link.active {
            background: #f8f9fa;
            color: #333;
            font-weight: 500;
        }

        /* Logo Styles */
        .logo-box {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .top-navbar .logo-box {
            transform: scale(0.8);
        }

        .top-navbar .logo-box:hover {
            transform: scale(0.85);
        }

        .logo {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-weight: bold;
            font-size: 1.8rem;
            padding: 6px 14px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            letter-spacing: 1px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .brand {
            color: #fff;
            font-size: 1.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Sidebar logo can be bigger */
        .sidebar .logo-box {
            transform: scale(1);
        }

        .sidebar .logo-box:hover {
            transform: scale(1.1);
        }

        /* Add new styles for category nav links */
        .category-nav .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .category-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #fff;
            transition: width 0.3s ease;
        }

        .category-nav .nav-link:hover::after,
        .category-nav .nav-link.active::after {
            width: 100%;
        }

        .category-nav .nav-link:hover,
        .category-nav .nav-link.active {
            background: transparent !important;
        }

        /* Cart and Favorites icons */
        .cart-icon,
        .favorites-icon {
            color: #fff;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            position: relative;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            margin: 0 5px;
        }

        .cart-icon:hover,
        .favorites-icon:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .badge-floating {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 18px;
            height: 18px;
            line-height: 18px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 50%;
            background: #ff4081;
            color: white;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        @yield('additional_styles')
    </style>
</head>

<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="logo-box">
            <span class="logo">Be</span>
            <span class="brand">Behance</span>
        </div>
        <button class="menu-toggle ms-4" id="menuToggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="category-nav ms-4">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('dashboard') }}">Home</a>
                </li>
                @can('buy_product')
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request('gender') == 'Women' ? 'active' : '' }}"
                            href="{{ route('products.index', ['gender' => 'Women']) }}">
                            Women
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request('gender') == 'Men' ? 'active' : '' }}"
                            href="{{ route('products.index', ['gender' => 'Men']) }}">
                            Men
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request('gender') == 'Kids & Baby' ? 'active' : '' }}"
                            href="{{ route('products.index', ['gender' => 'Kids & Baby']) }}">
                            Kids & Baby
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
        <div class="ms-auto d-flex align-items-center">
            @can('buy_product')
                <a href="{{ route('cart.index') }}" class="cart-icon me-3 text-decoration-none">
                    <i class="bi bi-cart3 fs-5"></i>
                    <span class="badge bg-danger badge-floating rounded-circle">{{ $cartCount }}</span>
                </a>
                <a href="{{ route('favorites.index') }}" class="favorites-icon me-4 text-decoration-none">
                    <i class="bi bi-heart fs-5"></i>
                    <span class="badge bg-danger badge-floating rounded-circle">{{ $favoriteCount }}</span>
                </a>
            @endcan
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn text-white border-0">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Dashboard
                </a>
            </li>
            <!-- User Management Section -->
            @can('view_users' || 'view_roles')
                <li class="nav-item">
                    <a class="nav-link has-submenu {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'active' : '' }}"
                        href="#userManagement" data-bs-toggle="collapse">
                        <i class="bi bi-shield-lock me-2"></i>
                        User Management
                    </a>
                    <ul class="submenu collapse {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'show' : '' }}"
                        id="userManagement">
                        @can('view_users')
                            <li>
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                                    href="{{ route('users.index') }}">
                                    <i class="bi bi-person me-2"></i>
                                    Users
                                </a>
                            </li>
                        @endcan
                        @can('view_roles')
                            <li>
                                <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                                    href="{{ route('roles.index') }}">
                                    <i class="bi bi-person-badge me-2"></i>
                                    Roles & Permissions
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
            @can('view_orders')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
                        href="{{ route('orders.index') }}">
                        <i class="bi bi-cart3 me-2"></i>
                        Orders
                    </a>
                </li>
            @endcan
            @can('view_products')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                        href="{{ route('products.index') }}">
                        <i class="bi bi-grid me-2"></i>
                        Products
                    </a>
                </li>
            @endcan
            @can('view_category')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                        href="{{ route('categories.index') }}">
                        <i class="bi bi-tag me-2"></i>
                        Categories
                    </a>
                </li>
            @endcan

        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('#sidebar');
            const mainContent = document.querySelector('#mainContent');
            const menuToggle = document.querySelector('#menuToggle');
            const submenuToggles = document.querySelectorAll('.has-submenu');

            // Initialize sidebar state from localStorage
            const sidebarExpanded = localStorage.getItem('sidebarExpanded') === 'true';
            if (sidebarExpanded) {
                sidebar.classList.add('expanded');
                mainContent.classList.add('shifted');
                menuToggle.classList.add('active');
            }

            if (menuToggle) {
                menuToggle.addEventListener('click', function (e) {
                    e.stopPropagation(); // Prevent event from bubbling up
                    sidebar.classList.toggle('expanded');
                    mainContent.classList.toggle('shifted');
                    menuToggle.classList.toggle('active');
                    // Save state to localStorage
                    localStorage.setItem('sidebarExpanded', sidebar.classList.contains('expanded'));
                });
            }

            // Submenu toggles
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    if (e.target === toggle) {
                        e.preventDefault();
                        toggle.classList.toggle('active');
                    }
                });
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', function (event) {
                const isClickInside = sidebar.contains(event.target) ||
                    menuToggle.contains(event.target);

                if (!isClickInside && sidebar.classList.contains('expanded')) {
                    sidebar.classList.remove('expanded');
                    mainContent.classList.remove('shifted');
                    menuToggle.classList.remove('active');
                    localStorage.setItem('sidebarExpanded', false);
                }
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
