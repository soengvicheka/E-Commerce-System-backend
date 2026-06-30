<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    // Public
    Route::get('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);
    Route::get('/categories/{id}', [\App\Http\Controllers\Api\CategoryController::class, 'show']);
    Route::get('/categories/{id}/products', [\App\Http\Controllers\Api\CategoryController::class, 'products']);

    Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
    Route::get('/products/featured', [\App\Http\Controllers\Api\ProductController::class, 'featured']);
    Route::get('/products/latest', [\App\Http\Controllers\Api\ProductController::class, 'latest']);
    Route::get('/products/{id}', [\App\Http\Controllers\Api\ProductController::class, 'show']);

    Route::get('/products/{productId}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'index']);

    Route::get('/dashboard/stats', [\App\Http\Controllers\Api\DashboardController::class, 'stats']);

    // Auth
    Route::post('/auth/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
    Route::post('/auth/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
        Route::get('/auth/me', [\App\Http\Controllers\Api\AuthController::class, 'me']);

        Route::get('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'show']);
        Route::put('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'update']);
        Route::post('/profile/avatar', [\App\Http\Controllers\Api\ProfileController::class, 'uploadAvatar']);
        Route::post('/profile/password', [\App\Http\Controllers\Api\ProfileController::class, 'changePassword']);

        Route::get('/wishlist', [\App\Http\Controllers\Api\WishlistController::class, 'index']);
        Route::post('/wishlist', [\App\Http\Controllers\Api\WishlistController::class, 'store']);
        Route::post('/wishlist/toggle', [\App\Http\Controllers\Api\WishlistController::class, 'toggle']);
        Route::get('/wishlist/check/{productId}', [\App\Http\Controllers\Api\WishlistController::class, 'check']);
        Route::delete('/wishlist/{id}', [\App\Http\Controllers\Api\WishlistController::class, 'destroy']);

        Route::get('/cart', [\App\Http\Controllers\Api\CartController::class, 'index']);
        Route::post('/cart', [\App\Http\Controllers\Api\CartController::class, 'store']);
        Route::put('/cart/{id}', [\App\Http\Controllers\Api\CartController::class, 'update']);
        Route::delete('/cart/{id}', [\App\Http\Controllers\Api\CartController::class, 'destroy']);
        Route::delete('/cart', [\App\Http\Controllers\Api\CartController::class, 'clear']);

        Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class, 'index']);
        Route::get('/orders/{id}', [\App\Http\Controllers\Api\OrderController::class, 'show']);
        Route::post('/orders', [\App\Http\Controllers\Api\OrderController::class, 'store']);

        Route::post('/products/{productId}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store']);
        Route::delete('/products/{productId}/reviews/{reviewId}', [\App\Http\Controllers\Api\ReviewController::class, 'destroy']);
    });
});
