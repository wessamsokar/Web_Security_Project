<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login'); // redirect بدل عرض الصفحة على طول
});

// Authentication Routes - لا تحتاج تسجيل دخول
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register/complete', [AuthController::class, 'showCompleteForm'])->name('register.complete');
Route::post('/register/complete', [AuthController::class, 'completeRegistration']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes - لا تحتاج تسجيل دخول
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Orders
    Route::resource('orders', OrderController::class);

    // Products
    Route::resource('products', ProductController::class);

    // Categories
    Route::get('/categories', function () {
        return view('categories.index');
    })->name('categories.index');

    // User Management
    Route::resource('users', UserController::class);

    // Roles
    Route::resource('roles', RoleController::class);
    Route::post('roles/{role}/remove-user', [RoleController::class, 'removeUser'])->name('roles.remove-user');
});
