<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PromotionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/promotions/validate', [PromotionController::class, 'validate']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Admin routes
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        Route::apiResource('promotions', \App\Http\Controllers\Admin\PromotionController::class);
    });
});
