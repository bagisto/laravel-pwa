<?php

use Illuminate\Support\Facades\Route;
use Webkul\API\Http\Controllers\Shop\ResourceController;
use Webkul\PWA\Http\Controllers\Shop\AddressController;
use Webkul\PWA\Http\Controllers\Shop\API\APIController;
use Webkul\PWA\Http\Controllers\Shop\CartController;
use Webkul\PWA\Http\Controllers\Shop\CategoryController;
use Webkul\PWA\Http\Controllers\Shop\CheckoutController;
use Webkul\PWA\Http\Controllers\Shop\ComparisonController;
use Webkul\PWA\Http\Controllers\Shop\ImageSearchController;
use Webkul\PWA\Http\Controllers\Shop\InvoiceController;
use Webkul\PWA\Http\Controllers\Shop\ProductController;
use Webkul\PWA\Http\Controllers\Shop\ThemeController;
use Webkul\PWA\Http\Controllers\Shop\ReviewController;
use Webkul\PWA\Http\Controllers\Shop\SmartButtonController;
use Webkul\PWA\Http\Controllers\Shop\WishlistController;
use Webkul\PWA\Http\Controllers\SinglePageController;
use Webkul\PWA\Http\Controllers\StandardController;

Route::group(['middleware' => ['web']], function () {

    Route::prefix('pwa/paypal/smart-button')->group(function () {
        Route::get('/create-order', [SmartButtonController::class, 'createOrder'])->name('paypal.smart-button.create-order.pwa');

        Route::post('/capture-order', [SmartButtonController::class, 'captureOrder'])->name('paypal.smart-button.capture-order.pwa');
    });
});

Route::group(['prefix' => 'api'], function ($router) {

    Route::group(['middleware' => ['locale', 'theme', 'currency']], function ($router) {

        Route::get('product-configurable-config/{id}', [ProductController::class, 'configurableConfig']);

        Route::get('invoices/{id}/download', [InvoiceController::class, 'print'])->defaults('_config', [
            'repository'             => 'Webkul\Sales\Repositories\InvoiceRepository',
            'resource'               => 'Webkul\API\Http\Resources\Sales\Invoice',
            'authorization_required' => true,
        ]);

        Route::get('wishlist/add/{id}', [WishlistController::class, 'create']);

        Route::post('reviews/{id}/create', [ReviewController::class, 'store']);

        // removed
        Route::get('advertisements', [APIController::class, 'fetchAdvertisementImages']);

        Route::post('save-address', [AddressController::class, 'store']);

        Route::post('pwa/image-search-upload', [ImageSearchController::class, 'upload']);

        // Checkout routes
        Route::group(['prefix' => 'pwa'], function ($router) {
            Route::group(['prefix' => 'checkout'], function ($router) {
                Route::get('cart', [CartController::class, 'get']);

                Route::post('save-address', [CheckoutController::class, 'saveAddress']);

                Route::post('cart/add/{id}', [CartController::class, 'store']);

                Route::post('save-order', [CheckoutController::class, 'saveOrder']);
            });

            Route::put('/comparison', [ComparisonController::class, 'addCompareProduct']);

            Route::post('/comparison', [ComparisonController::class, 'deleteComparisonProduct']);

            Route::get('/comparison/get-products', [ComparisonController::class, 'getComparisonList']);

            Route::get('/detailed-products', [ComparisonController::class, 'getDetailedProducts']);

            Route::get('invoices', [InvoiceController::class, 'index'])->defaults('_config', [
                'repository'             => 'Webkul\Sales\Repositories\InvoiceRepository',
                'resource'               => 'Webkul\API\Http\Resources\Sales\Invoice',
                'authorization_required' => true,
            ]);

            Route::get('invoices/{id}', [InvoiceController::class, 'get'])->defaults('_config', [
                'repository'             => 'Webkul\Sales\Repositories\InvoiceRepository',
                'resource'               => 'Webkul\API\Http\Resources\Sales\Invoice',
                'authorization_required' => true,
            ]);

            Route::get('move-to-cart/{id}', [WishlistController::class, 'moveToCart']);

            // no need
            // Route::get('categories', [CategoryController::class, 'index']);

            Route::get('attributes', [APIController::class, 'fetchAttributes']);

            // no need
            // Route::get('products', [ProductController::class, 'index'])->name('api.products');

            Route::get('products/{id}', [ProductController::class, 'get']);

            Route::get('sliders', [ThemeController::class, 'sliders']);
        });

        Route::group(['prefix' => 'checkout'], function ($router) {

            Route::get('cart/empty', [CartController::class, 'destroy']);

            Route::get('guest-checkout', [CheckoutController::class, 'isGuestCheckout']);

            Route::put('cart/update', [CartController::class, 'update']);

            Route::get('cart/remove-item/{id}', [CartController::class, 'destroyItem']);

            Route::get('cart/move-to-wishlist/{id}', [CartController::class, 'moveToWishlist']);

            Route::post('save-shipping', [CheckoutController::class, 'saveShipping']);

            Route::post('save-payment', [CheckoutController::class, 'savePayment']);

            Route::post('cart/apply-coupon', [CartController::class, 'applyCoupon']);

            Route::post('cart/remove-coupon', [CartController::class, 'removeCoupon']);
        });

        Route::get('pwa-reviews/{id}', [ResourceController::class, 'get'])->defaults('_config', [
            'repository'             => 'Webkul\Product\Repositories\ProductReviewRepository',
            'resource'               => 'Webkul\PWA\Http\Resources\Catalog\ProductReview',
            'authorization_required' => true,
        ]);

        Route::delete('reviews/{id}', [ResourceController::class, 'destroy'])->defaults('_config', [
            'repository'             => 'Webkul\Product\Repositories\ProductReviewRepository',
            'resource'               => 'Webkul\PWA\Http\Resources\Catalog\ProductReview',
            'authorization_required' => true,
        ]);

        Route::get('downloadable-products', [ResourceController::class, 'index'])->defaults('_config', [
            'resource'               => 'Webkul\PWA\Http\Resources\Sales\DownloadableProduct',
            'repository'             => 'Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository',
            'authorization_required' => true,
        ]);

        Route::get('pwa-wishlist', [ResourceController::class, 'index'])->defaults('_config', [
            'repository'             => 'Webkul\Customer\Repositories\WishlistRepository',
            'resource'               => 'Webkul\PWA\Http\Resources\Customer\Wishlist',
            'authorization_required' => true,
        ]);

        Route::get('pwa-reviews', [ResourceController::class, 'index'])->defaults('_config', [
            'repository'             => 'Webkul\Product\Repositories\ProductReviewRepository',
            'resource'               => 'Webkul\PWA\Http\Resources\Catalog\ProductReview',
            'authorization_required' => true,
        ]);

        Route::get('pwa-layout', [ResourceController::class, 'index'])->defaults('_config', [
            'repository'    => 'Webkul\PWA\Repositories\PWALayoutRepository',
            'resource'      => 'Webkul\PWA\Http\Resources\PWA\LayoutResource',
        ]);

        Route::group(['prefix' => 'pwa'], function ($router) {
            Route::get('orders', [ResourceController::class, 'index'])->defaults('_config', [
                'repository'             => 'Webkul\Sales\Repositories\OrderRepository',
                'resource'               => 'Webkul\PWA\Http\Resources\Sales\Order',
                'authorization_required' => true,
            ]);

            Route::get('orders/{id}', [ResourceController::class, 'get'])->defaults('_config', [
                'repository'             => 'Webkul\Sales\Repositories\OrderRepository',
                'resource'               => 'Webkul\PWA\Http\Resources\Sales\Order',
                'authorization_required' => true,
            ]);

            // Slider routes
        });
    });
});

Route::group(['middleware' => ['locale', 'theme', 'currency']], function ($router) {
    Route::get('/mobile/{any?}', [SinglePageController::class, 'index'])->where('any', '.*')->name('mobile.home');

    Route::get('/pwa/{any?}', [SinglePageController::class, 'index'])->where('any', '.*')->name('pwa.home');
});

Route::prefix('paypal/standard')->group(function () {
    Route::get('/pwa/success', [StandardController::class, 'success'])->name('pwa.paypal.standard.success');

    Route::get('/pwa/cancel', [StandardController::class, 'cancel'])->name('pwa.paypal.standard.cancel');
});
