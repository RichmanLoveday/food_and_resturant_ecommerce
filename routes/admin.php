<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\AppDownloadSectionController;
use App\Http\Controllers\Admin\BannerSliderController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ChefController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomPageBuilderController;
use App\Http\Controllers\Admin\ClearDatabaseController;
use App\Http\Controllers\Admin\DailyOfferController;
use App\Http\Controllers\Admin\DeliveryAreaController;
use App\Http\Controllers\Admin\FooterInfoController;
use App\Http\Controllers\Admin\MenuBuilderController;
use App\Http\Controllers\Admin\NewsLetterController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\PrivacyPolicyController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductGalleryController;
use App\Http\Controllers\Admin\ProductOptionController;
use App\Http\Controllers\Admin\ProductReviewController;
use App\Http\Controllers\Admin\ProductSizeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\ReservationTimeController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\TermsAndConditionController;
use App\Http\Controllers\Admin\TestimonialController;
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

        /*** Product Reviews Route */
        Route::controller(ProductReviewController::class)->group(function () {
            Route::get('/product-review', 'index')->name('product-review.index');
            Route::post('/product-review', 'updateStatus')->name('product-review.update');
            Route::delete('/product-review', 'destroy')->name('product-review.destroy');
        });

        /** Coupon Routes */
        Route::resource('coupon', CouponController::class);

        /** Delivery Area Routes */
        Route::resource('delivery-area', DeliveryAreaController::class);

        /** Settings Routes */
        Route::controller(SettingsController::class)->group(function () {
            Route::get('/setting', 'index')->name('settings.index');
            Route::put('/general-setting', 'UpdateGeneralSetting')->name('general-setting.update');
            Route::put('/pusher-setting', 'UpdatePusherSetting')->name('pusher-setting.update');
            Route::put('/mail-setting', 'updateMailSettings')->name('mail-setting.update');
            Route::put('/logo-setting', 'updateLogoSettings')->name('logo-setting.update');
            Route::put('/appearance-setting', 'updateAppearanceSetting')->name('appearance-setting.update');
            Route::put('/seo-setting', 'updateSeoSetting')->name('seo-setting.update');
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

        /** Daily Offer Routes */
        Route::get('/daily-offer/search-product', [DailyOfferController::class, 'productSearch'])->name('daily-offer.search-product');
        Route::put('/daily-offer-title-update', [DailyOfferController::class, 'updateTitle'])->name('daily-offer.title.update');
        Route::resource('/daily-offer', DailyOfferController::class);


        /** Banner Slider Routes */
        Route::resource('/banner-slider', BannerSliderController::class);

        /** Chefs Routes */
        Route::put('/chefs-title-update', [ChefController::class, 'updateTitle'])->name('chefs.title.update');
        Route::resource('/chef', ChefController::class);

        /** App Download Section Routes */
        Route::controller(AppDownloadSectionController::class)->group(function () {
            Route::get('/app-download', 'index')->name('app-download.index');
            Route::post('/app-download/store', 'store')->name('app-download.store');
        });

        /** Testimonial Routes */
        Route::put('/testimonial-title-update', [TestimonialController::class, 'updateTitle'])->name('testimonial.title.update');
        Route::resource('/testimonial', TestimonialController::class);

        /** Counter Section Routes */
        Route::controller(CounterController::class)->group(function () {
            Route::get('/counter', 'index')->name('counter.index');
            Route::put('/counter/update', 'update')->name('counter.update');
        });

        /** Blog Category Routes */
        Route::resource('/blog-category', BlogCategoryController::class);

        /** Blogs Routes */
        Route::get('/blogs/comments', [BlogController::class, 'blogComment'])->name('blogs.comments.index');
        Route::get('/blogs/comments/{id}', [BlogController::class, 'commentStatusUpdate'])->name('blogs.comment.update');
        Route::delete('/blogs/comments/{id}', [BlogController::class, 'commentDestroy'])->name('blogs.comment.destroy');
        Route::resource('/blogs', BlogController::class);


        /** About Routes */
        Route::controller(AboutController::class)->group(function () {
            Route::get('/about', 'index')->name('about.index');
            Route::put('/about/update', 'update')->name('about.update');
        });


        /** Privacy Policy Routes */
        Route::controller(PrivacyPolicyController::class)->group(function () {
            Route::get('/privacy-policy', 'index')->name('privacy-policy.index');
            Route::put('/privacy-policy/update', 'update')->name('privacy-policy.update');
        });

        /** Privacy Policy Routes */
        Route::controller(TermsAndConditionController::class)->group(function () {
            Route::get('/terms-and-condition', 'index')->name('terms-and-condition.index');
            Route::put('/terms-and-condition/update', 'update')->name('terms-and-condition.update');
        });

        /** Contacts Routes */
        Route::controller(ContactController::class)->group(function () {
            Route::get('/contact', 'index')->name('contact.index');
            Route::put('/contact/update', 'update')->name('contact.update');
        });

        /**Reservation Time Route */
        Route::resource('/reservation-time', ReservationTimeController::class);

        /**Reservation Controller */
        Route::controller(ReservationController::class)->group(function () {
            Route::get('/reservation', 'index')->name('reservation.index');
            Route::post('/reservation', 'update')->name('reservation.update');
            Route::delete('/reservation/{id}', 'destroy')->name('reservation.destroy');
        });


        /**News letter controller */
        Route::controller(NewsLetterController::class)->group(function () {
            Route::get('/news-letter', 'index')->name('news-letter.index');
            Route::post('/news-letter', 'sendNewsLetter')->name('news-letter.send');
        });


        /** Social Links Controller */
        Route::resource('/social-link', SocialLinkController::class);

        /** Footer Info */
        Route::controller(FooterInfoController::class)->group(function () {
            Route::get('/footer-info', 'index')->name('footer-info.index');
            Route::put('/footer-info', 'update')->name('footer-info.update');
        });

        /*** Menu Builder Route */
        Route::controller(MenuBuilderController::class)->group(function () {
            Route::get('/menu-builder', 'index')->name('menu-builder.index');
        });

        /**Custom Page Builder */
        Route::resource('/custom-page-builder', CustomPageBuilderController::class);


        /** Admin Management Routes */
        // Apply the custom middleware only to sensitive routes
        Route::middleware('prevent.supperadmin.edit.delete')->group(function () {
            Route::get('/admin-management/{id}/edit', [AdminManagementController::class, 'edit'])->name('admin-management.edit');
            Route::put('/admin-management/{id}', [AdminManagementController::class, 'update'])->name('admin-management.update');
            Route::delete('/admin-management/{id}', [AdminManagementController::class, 'destroy'])->name('admin-management.destroy');
        });

        Route::resource('/admin-management', AdminManagementController::class);

        Route::controller(ClearDatabaseController::class)->group(function () {
            Route::get('/clear-database', 'index')->name('clear-database.index');
            Route::post('/clear-database', 'clearDB')->name('clear-database.destroy');
        });
    });
});
