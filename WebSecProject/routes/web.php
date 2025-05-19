<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------|
| Web Routes                                                               |
|--------------------------------------------------------------------------|
*/

Route::get('/', function () {
    return redirect()->route('login'); // Redirect to login page
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register/complete', [AuthController::class, 'showCompleteForm'])->name('register.complete');
Route::post('/register/complete', [AuthController::class, 'completeRegistration']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile', [ProfileController::class, 'show'])->name('profile')->middleware('auth');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password')->middleware('auth');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Orders
    Route::resource('orders', OrdersController::class)->middleware(['check.permission:view_orders']);
    Route::post('orders/{order}/status', [OrdersController::class, 'updateStatus'])->name('orders.updateStatus')->middleware(['check.permission:edit_orders']);

    // Categories
    Route::resource('categories', CategoriesController::class)->middleware(['check.permission:view_category']);
    Route::post('categories/{category}/remove-product/{product}', [CategoriesController::class, 'removeProduct'])
        ->name('categories.remove-product')->middleware(['check.permission:delete_category']);

    // Products
    Route::resource('products', ProductController::class);
    Route::put('/products/{product}/category', [CategoriesController::class, 'updateProductCategory'])->name('products.updateCategory');

    // User Management
    Route::resource('users', UserController::class)->middleware(['check.permission:view_users']);

    // Roles
    Route::resource('roles', RoleController::class)->middleware(['check.permission:view_role']);
    Route::post('roles/{role}/remove-user', [RoleController::class, 'removeUser'])->name('roles.remove-user')->middleware(['check.permission:delete_role']);

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/{id}/favorite', [CartController::class, 'moveToFavorites'])->name('cart.favorite');

    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
        Route::post('/checkout', [App\Http\Controllers\CartController::class, 'processCheckout'])->name('cart.processCheckout');
    });

    Route::get('view', [OrdersController::class, 'view'])->name('orders.view')->middleware('auth');


    // Favorites
    Route::get('/favorites', [FavoritesController::class, 'index'])->name('favorites.index');
    Route::post('/favorites', [FavoritesController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{product_id}', [FavoritesController::class, 'destroy'])->name('favorites.destroy');
});

// Customer Service Routes
Route::middleware(['auth', 'check.permission:view_users'])->prefix('customer-service')->name('customer-service.')->group(function () {
    Route::get('/dashboard', [CustomerServiceController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [CustomerServiceController::class, 'userSearch'])->name('user-search');
    Route::get('/users/{user}', [CustomerServiceController::class, 'userDetails'])->name('user-details');
    Route::get('/users/{user}/create-ticket', [CustomerServiceController::class, 'createTicketForUser'])->name('create-ticket');
});

// Ticket System Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
    Route::post('tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');
});




Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot_password');
Route::post('/forgot-password', [AuthController::class, 'sendTemporaryPassword'])->name('send_temp_password');


Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('login_with_google');
Route::get('/auth/google/callback',[AuthController::class, 'handleGoogleCallback']);
Route::get('verify', [AuthController::class, 'verify'])->name('verify');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('login_with_google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('login_with_facebook');
Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback'])->name('handleFacebookCallback');
Route::get('/auth/github/redirect', [AuthController::class, 'redirectToGithub'])->name('login_with_github');
Route::get('/auth/github/callback', [AuthController::class, 'handleGithubCallback']);
Route::get('/auth/linkedin', [AuthController::class, 'redirectToLinkedin'])->name('login_with_linkedin');
Route::get('/auth/linkedin/callback', [AuthController::class, 'handleLinkedinCallback']);
Route::post('/login/certificate', [AuthController::class, 'loginWithCertificate'])->name('login.certificate');
