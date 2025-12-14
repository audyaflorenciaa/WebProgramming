<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;

// ----------------------------------------------------------------------
// PUBLIC ROUTES
// ----------------------------------------------------------------------

// Homepage - Show products
Route::get('/', [ProductController::class, 'index'])->name('home');

// Cart Routes (Public access is fine for viewing/adding to cart)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index'); // Cart View
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add'); // Add item to cart
Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove'); // Remove item from cart

// Public product filtering route
Route::get('/products', [ProductController::class, 'index'])->name('products.index');


// ----------------------------------------------------------------------
// PROTECTED ROUTES (require authentication)
// ----------------------------------------------------------------------

Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
    
    // Checkout route (must be protected by auth)
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index'); 
});

// ----------------------------------------------------------------------
// CATCH-ALL VARIABLE ROUTE (MUST BE LAST TO AVOID CONFLICTS)
// ----------------------------------------------------------------------
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
