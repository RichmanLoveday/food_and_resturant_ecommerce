<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\ProfileController as FrontendProfileController;
use Illuminate\Support\Facades\Route;

/**FRONTEND CONTROLLER */
Route::controller(FrontendController::class)->group(function () {
    /** show home page */
    Route::get('/', 'index')->name('home');
    /** show product */
    Route::get('/product/{slug}', 'showProduct')->name('product.show');
    /**Product Modal Route */
    Route::get('/load-product-moadl/{productId}', 'loadProductModal')->name('load-product-modal');
});


/**CART CONTROLLER */
Route::controller(CartController::class)->group(function () {
    Route::post('/add-to-cart', 'addToCart')->name('add-to-cart');
});


Route::middleware(['auth'])->group(function () {
    /** Frontend Dashboard Routes */
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    /** Profile controller Routes */
    Route::controller(FrontendProfileController::class)->group(function () {
        Route::put('/profile', 'updateProfile')->name('profile.udpate');
        Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
        Route::post('/profile/avatar', 'updateAvatar')->name('profile.avatar.update');
    });
});

require __DIR__ . '/auth.php';