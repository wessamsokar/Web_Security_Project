<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [UsersController::class, 'login'])->name('login');
Route::post('/login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('/register', [UsersController::class, 'register'])->name('register');
Route::post('/register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');

// User routes
Route::get('users', [UsersController::class, 'list'])->name('users_list');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::match( ['post' , 'put'], 'users/save/{user?}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');

// Product routes
Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');


// Role Management Routes
Route::get('/roles', [RolesController::class, 'list'])->name('roles_list');
Route::get('/roles/edit/{role?}', [RolesController::class, 'edit'])->name('roles_edit');
Route::match(['post', 'put'], '/roles/save/{role?}', [RolesController::class, 'save'])->name('roles_save');
Route::delete('/roles/delete/{role}', [RolesController::class, 'delete'])->name('roles_delete');


Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::post('profile/update-password', [UsersController::class, 'updatePassword'])->name('profile.update_password');


// Password reset routes
Route::get('/forgot-password', function () { return view('auth.forgot-password');})->middleware('guest')->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', function ($token) {return view('auth.reset-password', ['token' => $token]);})->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->middleware('guest')->name('password.update');


// login with google
Route::get('/auth/google', [UsersController::class, 'redirectToGoogle'])->name('login_with_google');
Route::get('/auth/google/callback', [UsersController::class, 'handleGoogleCallback']);


// Permissions Management Routes (admin only)
Route::get('/permissions', [PermissionsController::class, 'list'])->name('permissions_list');
Route::get('/permissions/edit/{permission?}', [PermissionsController::class, 'edit'])->name('permissions_edit');
Route::match(['post', 'put'], '/permissions/save/{permission?}', [PermissionsController::class, 'save'])->name('permissions_save');
Route::delete('/permissions/delete/{permission}', [PermissionsController::class, 'delete'])->name('permissions_delete');
