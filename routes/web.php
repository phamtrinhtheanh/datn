<?php

use App\Http\Controllers\BuildController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SelectController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\OrderController as CustomerOrderController;
use App\Http\Controllers\Admin\BannerController;

// Customer routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::resource('categories', CategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('products', ProductController::class);
        Route::resource('orders', OrderController::class);
        Route::resource('banners', BannerController::class);



        Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    });
    Route::get('minicart', [CartController::class, 'minicart'])->name('minicart');
});

Route::get('{text}-p.{id}', [HomeController::class, 'product'])
    ->where('id', '[0-9]+')
    ->where('text', '.*');

Route::get('{text}-c.{id}', [HomeController::class, 'productByCategory'])
    ->where('id', '[0-9]+')
    ->where('text', '.*');

Route::get('cart', [HomeController::class, 'cart'])->name('cart');
Route::get('buildpc', [BuildController::class, 'build'])->name('build');
Route::post('add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
Route::put('/cart/item/{id}', [CartController::class, 'update'])->name('cart.item.update');

Route::middleware('auth')->group(function () {
    Route::post('/orders', [CustomerOrderController::class, 'create'])->name('checkout.card');  // Create an order
    Route::put('/orders/{order}', [CustomerOrderController::class, 'cancel'])->name('order.cancel');  // Cancel an order
});

Route::post('/checkout/preview', [PaymentController::class, 'checkout'])->name('checkout');

Route::post('/checkout', [PaymentController::class, 'order'])->name('ordercreate');
Route::get('/return-vnpay', [PaymentController::class, 'handleReturn'])->name('payment.return');


Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/build/part', [BuildController::class, 'part'])->name('build.part');

Route::get('/select/categories', [SelectController::class, 'categories'])->name('select.categories');
Route::get('/select/brands', [SelectController::class, 'brands'])->name('select.brands');

Route::get('don-hang', [CustomerOrderController::class, 'getUserOrders'])->name('order.index');

Route::get('/vnpay-processing', function () {
    return Inertia::render('VNPayProcess', [
        'paymentUrl' => session('paymentUrl'),
    ]);
})->name('vnpay.processing');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
