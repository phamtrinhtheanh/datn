<?php

use App\Http\Controllers\Admin\SelectController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VNPayWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('select/brands', [SelectController::class, 'brands'])->name('select.brands');
Route::get('select/categories', [SelectController::class, 'categories'])->name('select.categories');
Route::post('/vnpay/webhook', [VNPayWebhookController::class, 'handleWebhook']);
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');

// Xử lý khi người dùng quay về từ VNPay
Route::get('/checkout/ipn', [PaymentController::class, 'vnpayIpn']);
