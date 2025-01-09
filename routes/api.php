<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('products', ProductController::class);

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

use App\Http\Controllers\Api\CartController;

// API Routes for Cart
//Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('api.cart.add');
    Route::get('/cart', [CartController::class, 'getCart'])->name('api.cart.index');
    Route::post('/cart/update', [CartController::class, 'updateCart'])->name('api.cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('api.cart.remove');
//});
