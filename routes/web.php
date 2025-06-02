<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileUploadController;
use App\Http\Controllers\ReviewController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'verified', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('products', ProductController::class);
    Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');
    Route::resource('orders', OrderController::class);
    Route::resource('banners', BannerController::class);
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

    // Additional routes for product management
    Route::get('products/{product}/show', [ProductController::class, 'show'])->name('products.show');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Order management
    Route::get('/orders', [App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Promotion management
    Route::resource('promotions', \App\Http\Controllers\Admin\PromotionController::class);
});


Route::get('/cart', [CartController::class, 'view'])->name('cart');
Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.add');
Route::patch('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/delete/{product}', [CartController::class, 'delete'])->name('cart.delete');
// Customer routes
Route::middleware(['auth'])->group(function () {
    // Order management routes
    Route::get('/don-hang', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
    Route::delete('/orders/{order}', [App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');
    Route::put('/orders/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel');
    // Cart routes


// Checkout routes
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/payment', [CheckoutController::class, 'processPayment'])->name('checkout.payment');

// Order routes
    Route::post('/order/create', [OrderController::class, 'create'])->name('order.create');
    Route::get('/vnpay-processing', function () {
        return Inertia::render('VNPayProcess', [
            'paymentUrl' => session('paymentUrl'),
        ]);
    })->name('vnpay.processing');
    Route::get('/return-vnpay', [OrderController::class, 'handleReturn'])->name('payment.return');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/products/{product}/reviews', [ReviewController::class, 'getProductReviews'])->name('reviews.product');
});

Route::get('{text}-p.{id}', [\App\Http\Controllers\ProductController::class, 'product'])
    ->where('id', '[0-9]+')
    ->where('text', '.*');

Route::get('{text}-c.{id}', [\App\Http\Controllers\ProductController::class, 'productByCategory'])
    ->where('id', '[0-9]+')
    ->where('text', '.*');

Route::get('/search', [SearchController::class, 'search'])->name('search');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
