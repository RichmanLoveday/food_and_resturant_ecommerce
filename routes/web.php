<?php

use App\Events\RTOrderPlacedNotificationEvent;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Controllers\Frontend\ProfileController as FrontendProfileController;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

/**FRONTEND CONTROLLER */
Route::controller(FrontendController::class)->group(function () {
    /** show home page */
    Route::get('/', 'index')->name('home');

    /** show product */
    Route::get('/product/{slug}', 'showProduct')->name('product.show');

    /**Product Modal Route */
    Route::get('/load-product-moadl/{productId}', 'loadProductModal')->name('load-product-modal');

    /** Coupon Routes */
    Route::post('/apply-coupon', 'applyCoupon')->name('apply-coupon');

    /** Remove Coupon Route */
    Route::get('/destroy-coupon', 'destroyCoupon')->name('destroy-coupon');
});


/**CART CONTROLLER */
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
    });

    /** Profile controller Routes */
    Route::controller(FrontendProfileController::class)->group(function () {
        Route::put('/profile', 'updateProfile')->name('profile.udpate');
        Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
        Route::post('/profile/avatar', 'updateAvatar')->name('profile.avatar.update');
    });

    /*** Checkout Controller Routes */
    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/checkout', 'index')->name('checkout.index');
        Route::get('/checkout/{id}/delivery-cal', 'calculateDeliveryCharge')->name('checkout.delivery.cal');
        Route::post('/checkout', 'checkoutRedirect')->name('checkout.redirect');
    });


    /*** Payment Controller Routes */
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
});

require __DIR__ . '/auth.php';
