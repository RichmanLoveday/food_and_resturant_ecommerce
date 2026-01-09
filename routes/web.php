<?php

use App\Events\RTOrderPlacedNotificationEvent;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CustomPageController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ProfileController as FrontendProfileController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;


// Health check route
Route::get('/ping', fn() => response()->json([
    'status' => 'ok',
    'time' => now()->toIso8601String(),
]));


Route::get('/setup/run-migrations', function () {
    if (!app()->environment(['local', 'staging'])) {
        abort(403, 'Unauthorized');
    }

    Artisan::call('migrate:fresh', ['--force' => true]);
    Artisan::call('db:seed', ['--force' => true]);

    return response()->json([
        'status' => 'success',
        'message' => 'Migrations and seeder completed successfully',
        'output' => Artisan::output(),
    ]);
});

Route::get('/setup/run-migration', function () {
    if (!app()->environment(['local', 'staging'])) {
        abort(403, 'Unauthorized');
    }

    Artisan::call('migrate', ['--force' => true]);

    return response()->json([
        'status' => 'success',
        'message' => 'Migrations completed',
        'output' => Artisan::output(),
    ]);
});

Route::get('/setup/storage-link', function () {
    if (!app()->environment(['local', 'staging'])) {
        abort(403, 'Unauthorized');
    }

    Artisan::call('storage:link');

    return response()->json([
        'status' => 'success',
        'message' => 'Storage link created',
        'output' => Artisan::output(),
    ]);
});

/** FRONTEND CONTROLLER */
Route::controller(FrontendController::class)->group(function () {
    /** show home page */
    Route::get('/', 'index')->name('home');
    /** show product */
    Route::get('/product/{slug}', 'showProduct')->name('product.show');

    /** Products Page Route */
    Route::get('/products', 'products')->name('product.index');

    /**Product Modal Route */
    Route::get('/load-product-moadl/{productId}', 'loadProductModal')->name('load-product-modal');

    /** Product Review */
    Route::post('/product-review', 'productReviewStore')->name('product-review.store');
    Route::get('/product-review/{productId}', 'loadMoreReviews')->name('product-review.loadmore');

    /** Coupon Routes */
    Route::post('/apply-coupon', 'applyCoupon')->name('apply-coupon');

    /** Remove Coupon Route */
    Route::get('/destroy-coupon', 'destroyCoupon')->name('destroy-coupon');

    /** Chef page */
    Route::get('/chefs', 'chef')->name('chef');

    /** Testimonial page */
    Route::get('/testimonials', 'testimonial')->name('testimonial');

    /**Blogs page */
    Route::get('/blogs', 'blog')->name('blogs');
    Route::get('/blogs/{slug}', 'blogDetails')->name('blog-details');
    Route::post('/blogs/comment/{blogId}', 'blogCommentStore')->name('blogs.comment.store');
    Route::get('/blogs/comment/{blogId}', 'loadMoreComments')->name('blogs.comment.loadmore');

    /** About Routes */
    Route::get('/about', 'about')->name('about');

    /**Privacy Policy */
    Route::get('/privacy-policy', 'privacyPolicy')->name('privacy-policy');

    /**Terms and condition */
    Route::get('/terms-and-condition', 'termsAndCondition')->name('terms-and-condition');

    /**conatact route */
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'sendContactMessage')->name('contact.send-message');

    /**Reservation Route */
    Route::post('/reservation/store', 'reservation')->name('reservation.store');

    /** News Letter Routes */
    Route::post('/subscribe-newsletter', 'subscribeNewsLetter')->name('subscribe-newsletter');
});

/** Custom Page Route */
Route::get('/page/{slug}', CustomPageController::class);

/** Wishlist Controller */
Route::controller(WishlistController::class)->group(function () {
    Route::post('/wishlist', 'store')->name('wishlist.store');
    Route::delete('/wishlist-product-remove/{rowId}', 'wishlistProductRemove')->name('wishlist-product-remove');
});

/** CART CONTROLLER */
Route::controller(CartController::class)->group(function () {
    Route::post('/add-to-cart', 'addToCart')->name('add-to-cart');
    Route::get('/get-cart-products', 'getCartProducts')->name('get-cart-products');
    Route::get('/cart-product-remove/{rowId}', 'cartProductRemove')->name('cart-product-remove');

    /** Cart Page Route */
    Route::get('/cart', 'index')->name('cart.index');
    Route::post('/cart-update-qty', 'cartQtyUpdate')->name('cart.quantity-update');
    Route::get('/cart-destroy', 'cartDestroy')->name('cart.destroy');
});


Route::middleware(['auth'])->group(function () {
    /** Frontend Dashboard Routes */
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
        Route::post('/address', 'createAddress')->name('address.store');
        Route::put('/address/{id}/edit', 'updateAddress')->name('address.update');
        Route::delete('/address/{id}', 'destroyAddress')->name('address.destroy');
        Route::get('/user-reviews', 'loadMoreUserReviews')->name('user-reviews.loadMore');
    });

    /** Profile controller Routes */
    Route::controller(FrontendProfileController::class)->group(function () {
        Route::put('/profile', 'updateProfile')->name('profile.udpate');
        Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
        Route::post('/profile/avatar', 'updateAvatar')->name('profile.avatar.update');
    });

    /** Checkout Controller Routes */
    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/checkout', 'index')->name('checkout.index');
        Route::get('/checkout/{id}/delivery-cal', 'calculateDeliveryCharge')->name('checkout.delivery.cal');
        Route::post('/checkout', 'checkoutRedirect')->name('checkout.redirect');
    });


    /** Payment Controller Routes */
    Route::controller(PaymentController::class)->group(function () {
        Route::get('/payment', 'index')->name('payment.index');
        Route::post('/make-payment', 'makePayment')->name('make-payement');
        Route::get('/payment-success', 'paymentSuccess')->name('payment.success');
        Route::get('/payment-cancel', 'paymentCancel')->name('payment.cancel');


        /** Paypal Routes */
        Route::get('/paypal/payment', 'paywithPaypal')->name('paypal.payment');
        Route::get('/paypal/success', 'paypalSuccess')->name('paypal.success');
        Route::get('/paypal/cancel', 'paypalCancel')->name('paypal.cancel');


        /** Stripe Routes */
        Route::get('/stripe/payment', 'paywithStripe')->name('stripe.payment');
        Route::get('/stripe/success', 'stripeSuccess')->name('stripe.success');
        Route::get('/stripe/cancel', 'stripeCancel')->name('stripe.cancel');


        /** Razor Routes */
        Route::get('/razorpay-redirect', 'paywithRazorpayRedirect')->name('razorpay-redirect');
        Route::post('/razorpay/payment', 'paywithRazorpay')->name('razorpay.payment');
    });


    /** Chat Controller Routes */
    Route::controller(ChatController::class)->group(function () {
        Route::post('/chat/send-message', 'sendMessage')->name('chat.send-message');
        Route::get('/get-conversation/{senderId}', 'getConversation')->name('chat.get-conversation');
    });
});

require __DIR__ . '/auth.php';
