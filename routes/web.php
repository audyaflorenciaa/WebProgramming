<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// ----------------------------------------------------------------------
// PUBLIC ROUTES
// ----------------------------------------------------------------------

// Homepage - Show products
Route::get('/', [ProductController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes (Standard Laravel Auth)
Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.store');

// Public product filtering route
Route::get('/products', [ProductController::class, 'index'])->name('products.index');


// ----------------------------------------------------------------------
// PROTECTED ROUTES (require authentication)
// ----------------------------------------------------------------------

Route::middleware(['auth'])->group(function () {
    // 1. SELL & MY ITEMS (Specific Routes)
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/my-products', [ProductController::class, 'myProducts'])->name('products.my-products');
    
    // Product Management (Edit/Delete)
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// ----------------------------------------------------------------------
// CATCH-ALL VARIABLE ROUTE (MUST BE LAST TO AVOID CONFLICTS)
// ----------------------------------------------------------------------
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');