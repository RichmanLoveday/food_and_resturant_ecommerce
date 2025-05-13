<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\ProfileController as FrontendProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'index'])->name('home');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

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
