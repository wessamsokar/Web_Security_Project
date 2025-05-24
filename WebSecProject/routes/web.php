<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\TicketController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (function_exists('emailFromLoginCertificate')) {
        $email = emailFromLoginCertificate();
        if ($email && !auth()->check()) {
            $user = User::where('email', $email)->first();
            if ($user) {
                Auth::login($user);
            }
        }
    }
    return view('auth.login');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register/complete', [AuthController::class, 'showCompleteForm'])->name('register.complete');
Route::post('/register/complete', [AuthController::class, 'completeRegistration']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Orders
    Route::resource('orders', OrdersController::class)->middleware('check.permission:view_orders');
    Route::post('orders/{order}/status', [OrdersController::class, 'updateStatus'])->name('orders.updateStatus')->middleware('check.permission:edit_orders');
    Route::get('view', [OrdersController::class, 'view'])->name('orders.view');

    // Categories
    Route::resource('categories', CategoriesController::class)->middleware('check.permission:view_category');
    Route::post('categories/{category}/remove-product/{product}', [CategoriesController::class, 'removeProduct'])
        ->name('categories.remove-product')->middleware('check.permission:delete_category');

    // Products
    Route::resource('products', ProductController::class);
    Route::put('/products/{product}/category', [CategoriesController::class, 'updateProductCategory'])->name('products.updateCategory');

    // Favorites
    Route::get('/favorites', [ProductController::class, 'favorites'])->name('favorites.index');
    Route::post('/favorites/toggle', [ProductController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::delete('/favorites/{product_id}', [ProductController::class, 'removeFavorite'])->name('favorites.destroy');

    // Cart
    Route::get('/cart', [ProductController::class, 'cartIndex'])->name('products.cartIndex');
    Route::post('/cart/add', [ProductController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/{cartItem}', [ProductController::class, 'updateCartItem'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [ProductController::class, 'removeCartItem'])->name('cart.destroy');
    Route::post('/cart/{cartItem}/favorite', [ProductController::class, 'moveToFavoritesFromCart'])->name('cart.favorite');

    // Checkout
    Route::get('/checkout', [ProductController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout', [ProductController::class, 'processCheckout'])->name('cart.processCheckout');

    // User Management
    Route::resource('users', UserController::class)->middleware('check.permission:view_users');

    // Roles
    Route::resource('roles', RoleController::class)->middleware('check.permission:view_role');
    Route::post('roles/{role}/remove-user', [RoleController::class, 'removeUser'])->name('roles.remove-user')->middleware('check.permission:delete_role');
});

// Customer Service
Route::middleware(['auth', 'check.permission:view_users'])->prefix('customer-service')->name('customer-service.')->group(function () {
    Route::get('/dashboard', [CustomerServiceController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [CustomerServiceController::class, 'userSearch'])->name('user-search');
    Route::get('/users/{user}', [CustomerServiceController::class, 'userDetails'])->name('user-details');
    Route::get('/users/{user}/create-ticket', [CustomerServiceController::class, 'createTicketForUser'])->name('create-ticket');
});

// Ticket System
Route::middleware('auth')->group(function () {
    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
    Route::post('tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');
});

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot_password');
Route::post('/forgot-password', [AuthController::class, 'sendTemporaryPassword'])->name('send_temp_password');

// Social Authentication
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('login_with_google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('login_with_facebook');
Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback'])->name('handleFacebookCallback');
Route::get('/auth/github/redirect', [AuthController::class, 'redirectToGithub'])->name('login_with_github');
Route::get('/auth/github/callback', [AuthController::class, 'handleGithubCallback']);
Route::get('/auth/linkedin', [AuthController::class, 'redirectToLinkedin'])->name('login_with_linkedin');
Route::get('/auth/linkedin/callback', [AuthController::class, 'handleLinkedinCallback']);
Route::post('/login/certificate', [AuthController::class, 'loginWithCertificate'])->name('login.certificate');

// Email verification route (optional)
Route::get('verify', [AuthController::class, 'verify'])->name('verify');
