<?php


// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\api\AuthController;
use App\Http\Controllers\admin\api\UserController;
use App\Http\Controllers\admin\api\CategoryController;
use App\Http\Controllers\admin\api\ProductController;
use App\Http\Controllers\admin\api\ProductVariantController;
use App\Http\Controllers\admin\api\CartController;
use App\Http\Controllers\admin\api\OrderController;
use App\Http\Controllers\admin\api\AddressController;
use App\Http\Controllers\admin\api\ProductImageController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::post('login', [AuthentController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthentController::class, 'logout']);

// Authentication
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']); // Get all products with optional filters
    Route::get('/{id}', [ProductController::class, 'show']); // Get a specific product by ID
    Route::post('/', [ProductController::class, 'store']); // Create a new product
    Route::put('/{id}', [ProductController::class, 'update']); // Update an existing product
    Route::delete('/{id}', [ProductController::class, 'destroy']); // Delete a product
});


// Routes pour les catégories
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);           // Récupérer toutes les catégories
    Route::post('/', [CategoryController::class, 'store']);          // Créer une nouvelle catégorie
    Route::get('/{category}', [CategoryController::class, 'show']);   // Afficher une catégorie spécifique
    Route::put('/{category}', [CategoryController::class, 'update']); // Mettre à jour une catégorie spécifique
    Route::delete('/{category}', [CategoryController::class, 'destroy']); // Supprimer une catégorie spécifique
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
        ->name('verification.send');

    // User Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [UserController::class, 'profile']);
        Route::put('/', [UserController::class, 'updateProfile']);
        Route::put('/password', [UserController::class, 'updatePassword']);
    });

    // Addresses
    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index']);
        Route::post('/', [AddressController::class, 'store']);
        Route::get('/{address}', [AddressController::class, 'show']);
        Route::put('/{address}', [AddressController::class, 'update']);
        Route::delete('/{address}', [AddressController::class, 'destroy']);
        Route::put('/{address}/make-default', [AddressController::class, 'makeDefault']);
    });

    // Cart
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'addToCart']);
        Route::put('/{cart}', [CartController::class, 'updateQuantity']);
        Route::delete('/{cart}', [CartController::class, 'destroy']);
        Route::post('/clear', [CartController::class, 'clearCart']);
        Route::get('/count', [CartController::class, 'getCartCount']);
        Route::get('/total', [CartController::class, 'getCartTotal']);
    });

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::put('/{order}/cancel', [OrderController::class, 'cancel']);
        Route::get('/{order}/track', [OrderController::class, 'trackOrder']);
        Route::post('/{order}/review', [OrderController::class, 'addReview']);
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    // Categories Management
    Route::prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });

    // Products Management
    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
        Route::post('/{product}/variants', [ProductVariantController::class, 'store']);
        Route::put('/variants/{variant}', [ProductVariantController::class, 'update']);
        Route::delete('/variants/{variant}', [ProductVariantController::class, 'destroy']);
    });

    // Product Images
    Route::prefix('products/{product}/images')->group(function () {
        Route::post('/', [ProductImageController::class, 'store']);
        Route::delete('/{image}', [ProductImageController::class, 'destroy']);
        Route::post('/{image}/make-primary', [ProductImageController::class, 'makePrimary']);
        Route::post('/reorder', [ProductImageController::class, 'reorder']);
    });

    // Orders Management
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'adminIndex']);
        Route::put('/{order}/status', [OrderController::class, 'updateStatus']);
        Route::get('/export', [OrderController::class, 'export']);
        Route::get('/statistics', [OrderController::class, 'getStatistics']);
    });

    // Users Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::put('/{user}', [UserController::class, 'update']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
        Route::put('/{user}/status', [UserController::class, 'updateStatus']);
    });
});

// Webhooks (if needed)
Route::prefix('webhooks')->group(function () {
    Route::post('payment/stripe', [WebhookController::class, 'handleStripeWebhook']);
    Route::post('shipping/update', [WebhookController::class, 'handleShippingUpdate']);
});
