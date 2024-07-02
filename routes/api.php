<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WishlistController;




//////////////////////////////////////////
///           Auth  For User           ///
//////////////////////////////////////////

Route::group(['prefix' => 'user'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

///////////////////////////////////////////
///          For All User              ///
/////////////////////////////////////////

Route::middleware(['jwt.auth', 'api'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'getUser']);
});

///////////////////////////////////////////
///        Auth  For Admin             ///
/////////////////////////////////////////


Route::middleware(['admin', 'api'])->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::post('register', [AuthController::class, 'adminRegister']);
        Route::post('login', [AuthController::class, 'adminLogin']);
    });
});
///////////////////////////////////////////
///            For Coupons             ///
/////////////////////////////////////////
Route::middleware('jwt.auth', 'api')->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/coupons', [CouponController::class, 'index']);
        Route::post('/coupons', [CouponController::class, 'store']);
        Route::get('/coupons/{id}', [CouponController::class, 'show']);
        Route::put('/coupons/{id}', [CouponController::class, 'update']);
        Route::delete('/coupons/{id}', [CouponController::class, 'destroy']);
    });
});

///////////////////////////////////////////
///            For wishlist            ///
/////////////////////////////////////////

Route::middleware(['jwt.auth', 'admin', 'api'])->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('wishlist', [WishlistController::class, 'index']);
        Route::post('wishlist', [WishlistController::class, 'store']);
        Route::delete('wishlist/{id}', [WishlistController::class, 'destroy']);
    });
});


///////////////////////////////////////////
///          For Categories            ///
/////////////////////////////////////////
Route::middleware(['jwt.auth', 'admin', 'api'])->group(function () {

    Route::group(['prefix' => 'admin'], function () {
        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');
        Route::post('/categories', [CategoriesController::class, 'store'])->name('categories');
        Route::get('/categories/show/{id}', [CategoriesController::class, 'show'])->name('categories/show');
        Route::put('/categories/edit/{id}', [CategoriesController::class, 'update'])->name('categories/update');
        Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])->name('categories/destroy');
    });
});


///////////////////////////////////////////
///          For Products              ///
/////////////////////////////////////////

Route::middleware(['jwt.auth', 'admin', 'api'])->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products');
        Route::post('/products/create', [ProductController::class, 'create'])->name('products/create');
        Route::put('/products/edit/{id}', [ProductController::class, 'update'])->name('products/update');
        Route::post('/products/{id}/image', [ProductController::class, 'updateImage'])->name('products/updateImage');
        Route::get('/products/show/{id}', [ProductController::class, 'show'])->name('products/show');
        Route::delete('/products/delete/{id}', [ProductController::class, 'softdelete'])->name('products/delete');
        Route::post('/products/restore/{id}', [ProductController::class, 'restore'])->name('products/restore');
        Route::delete('/products/forceDelete/{id}', [ProductController::class, 'forceDelete'])->name('products/forceDelete');
    });
});

///////////////////////////////////////////
///            For orders              ///
/////////////////////////////////////////

Route::middleware(['jwt.auth', 'api'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store'])->name('orders/store');
    Route::put('/orders/restore/{order}', [OrderController::class, 'restore'])->name('orders/restore');
});

///////////////////////////////////////////
///            For review              ///
/////////////////////////////////////////

Route::middleware('jwt.auth', 'api')->group(function () {
    Route::post('/reviews/store', [ReviewController::class, 'store'])->name('reviews/store');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
    Route::get('/reviews/{id}', [ReviewController::class, 'show'])->name('reviews/show');
    Route::put('/reviews/{reviewId}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews/destroy');
});
