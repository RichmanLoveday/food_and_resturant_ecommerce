<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductGalleryController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\WhyChooseUsController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    /** Auth Routes and when admin is not logged in */
    Route::middleware(['guest'])->group(function () {
        Route::controller(AdminAuthController::class)->group(function () {
            Route::get('login', 'index')->name('login');
        });
    });


    //? middleware for when admin is logged in
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        /** Profile Routes */
        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
            Route::get('/', [ProfileController::class, 'index'])->name('index');
            Route::put('/', [ProfileController::class, 'updateProfile'])->name('update');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        });

        /** Slider Routes */
        Route::resource('slider', SliderController::class);

        /** Why Choose Us Routes */
        Route::put('why-choose-us-title-update', [WhyChooseUsController::class, 'updateTitle'])->name('why-choose-us.title.update');
        Route::resource('why-choose-us', WhyChooseUsController::class);

        /** Product Category Routes */
        Route::resource('category', CategoryController::class);

        /** Product Routes */
        Route::resource('product', ProductController::class);

        /** Product Gallery Routes */
        Route::get('product-gallery/{product}', [ProductGalleryController::class, 'index'])->name('product-gallery.show-index');
        Route::resource('product-gallery', ProductGalleryController::class);
    });
});