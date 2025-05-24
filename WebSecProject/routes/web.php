<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\TicketController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    //  $email = emailFromLoginCertificate();
    //  if ($email && !auth()->user()) {

    //     $user = User::where( 'email' , $email)->first();
    //     if ($user) Auth::login($user);
    //  }
    return redirect()->route('login'); // Redirect to login page
});


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register/complete', [AuthController::class, 'showCompleteForm'])->name('register.complete');
Route::post('/register/complete', [AuthController::class, 'completeRegistration']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');


// Routes that require authentication
Route::middleware(['auth'])->group(function () {
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
    Route::resource('products', ProductController::class)->middleware(['check.permission:view_products']);

    // User Management
    Route::resource('users', UserController::class)->middleware(['check.permission:view_users']);

    // Roles
    Route::resource('roles', RoleController::class)->middleware(['check.permission:view_role']);
    Route::post('roles/{role}/remove-user', [RoleController::class, 'removeUser'])->name('roles.remove-user')
        ->middleware(['check.permission:delete_role']);
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

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('login_with_google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/auth/github', [AuthController::class, 'redirectToGithub'])->name('login_with_github');
Route::get('/auth/github/callback', [AuthController::class, 'handleGithubCallback']);

Route::get('/auth/microsoft', [AuthController::class, 'redirectToMicrosoft'])->name('login_with_microsoft');
Route::get('/auth/microsoft/callback', [AuthController::class, 'handleMicrosoftCallback']);


// Route::get('/auth/redirect', function () {
//     return view('auth.redirect');
// })->name('auth.redirect');