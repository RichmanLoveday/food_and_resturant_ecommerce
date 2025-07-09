<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DeliveryAreaController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductGalleryController;
use App\Http\Controllers\Admin\ProductOptionController;
use App\Http\Controllers\Admin\ProductSizeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
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
        /*** Admin Dashboard Routes */
        Route::controller(AdminDashboardController::class)->group(function () {
            Route::get('dashboard',  'index')->name('dashboard');

            /** Order Notification Routes */
            Route::get('clear-notification', 'clearNotification')->name('clear-notification');
        });


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


        /** Product Size Routes */
        Route::get('product-size/{product}', [ProductSizeController::class, 'index'])->name('product-size.show-index');
        Route::resource('product-size', ProductSizeController::class);


        /** Product Option Routes */
        Route::resource('product-option', ProductOptionController::class);

        /** Coupon Routes */
        Route::resource('coupon', CouponController::class);

        /** Delivery Area Routes */
        Route::resource('delivery-area', DeliveryAreaController::class);

        /** Settings Routes */
        Route::controller(SettingsController::class)->group(function () {
            Route::get('/setting', 'index')->name('settings.index');
            Route::put('/general-setting', 'UpdateGeneralSetting')->name('general-setting.update');
            Route::put('/pusher-setting', 'UpdatePusherSetting')->name('pusher-setting.update');
        });

        /** Payment Gateway Routes */
        Route::controller(PaymentGatewayController::class)->group(function () {
            Route::get('/payment-setting', 'index')->name('payment-setting.index');
            Route::put('/paypal-setting', 'paypalSettingUpdate')->name('paypal-setting.update');
            Route::put('/stripe-setting', 'stripeSettingUpdate')->name('stripe-setting.update');
            Route::put('/razorpay-setting', 'razorpaySettingUpdate')->name('razorpay-setting.update');
        });


        /** Order Routes */
        Route::controller(OrderController::class)->group(function () {
            Route::get('/orders', 'index')->name('orders.index');
            Route::get('/orders/{id}', 'show')->name('order.show');
            Route::get('/orders/status/{id}', 'getOrderStatus')->name('orders.status');
            Route::put('/orders/status-update/{id}', 'orderStatusUpdate')->name('orders.status-update');
            Route::delete('/orders/{id}', 'destroy')->name('orders.destroy');
            Route::get('/pending-orders', 'pendingOrders')->name('orders.pending');
            Route::get('/in-process-orders', 'inProcessOrders')->name('orders.in-process');
            Route::get('/delivered-orders', 'deliveredOrders')->name('orders.delivered');
            Route::get('/declined-orders', 'declinedOrders')->name('orders.declined');
        });


        /** Chat Routes */
        Route::controller(ChatController::class)->group(function () {
            Route::get('/chat', 'index')->name('chat.index');
            Route::get('/get-conversation/{senderId}', 'getConversation')->name('chat.get-conversation');
            Route::post('/chat/send-message', 'sendMessage')->name('chat.send-message');
            Route::get('/chat/conversation/{senderId}', 'getUserConversations')->name('chat.conversation');
            Route::put('/chat/mart-as-read', 'markAllAsRead')->name('chat.mark-as-read');
        });
    });
});