<?php

use Illuminate\Support\Facades\Route;
use Webkul\PWA\Http\Controllers\StandardController;
use Webkul\PWA\Http\Controllers\SinglePageController;
use Webkul\PWA\Http\Controllers\Shop\CartController;
use Webkul\PWA\Http\Controllers\Shop\ReviewController;
use Webkul\PWA\Http\Controllers\Shop\ProductController;
use Webkul\PWA\Http\Controllers\Shop\InvoiceController;
use Webkul\PWA\Http\Controllers\Shop\AddressController;
use Webkul\PWA\Http\Controllers\Shop\CategoryController;
use Webkul\PWA\Http\Controllers\Shop\ResourceController;
use Webkul\PWA\Http\Controllers\Shop\CheckoutController;
use Webkul\PWA\Http\Controllers\Shop\WishlistController;
use Webkul\PWA\Http\Controllers\Shop\ComparisonController;
use Webkul\PWA\Http\Controllers\Shop\ImageSearchController;
use Webkul\PWA\Http\Controllers\Shop\SmartButtonController;
use Webkul\PWA\Http\Controllers\Shop\API\APIController;
use Webkul\PWA\Http\Controllers\Admin\LayoutController;
use Webkul\PWA\Http\Controllers\Admin\PushNotificationController;

Route::group(['middleware' => ['web']], function () {
    Route::group(['middleware' => ['admin'], 'prefix' => 'admin/pwa'], function () {
        Route::group(PushNotificationController::class)->group(function () {
            Route::get('pushnotification', 'index')
            ->name('pwa.pushnotification.index')
            ->defaults('_config', [
                'view' => 'pwa::admin.push-notification.index'
            ]);

            Route::get('pushnotification/create', 'create')
                ->name('pwa.pushnotification.create')
                ->defaults('_config', [
                    'view' => 'pwa::admin.push-notification.create'
                ]);

            Route::post('pushnotification/store', 'store')
                ->name('pwa.pushnotification.store')
                ->defaults('_config', [
                    'redirect' => 'pwa.pushnotification.index'
                ]);

            Route::get('pushnotification/edit/{id}', 'edit')
                ->name('pwa.pushnotification.edit')
                ->defaults('_config', [
                    'view' => 'pwa::admin.push-notification.edit'
                ]);

            Route::post('pushnotification/update/{id}', 'update')
                ->name('pwa.pushnotification.update')
                ->defaults('_config', [
                    'redirect' => 'pwa.pushnotification.index'
                ]);

            Route::get('pushnotification/delete/{id}', 'destroy')
                ->name('pwa.pushnotification.delete')
                ->defaults('_config', [
                    'redirect' => 'pwa.pushnotification.index'
                ]);

            Route::get('pushnotification/push/{id}', 'pushtofirebase')
                ->name('pwa.pushnotification.pushtofirebase')
                ->defaults('_config', [
                    'redirect' => 'pwa.pushnotification.index'
                ]);
        });

        Route::controller(LayoutController::class)->group(function () {
            Route::get('layout', 'index')
                ->name('pwa.layout')
                ->defaults('_config', [
                    'view' => 'pwa::admin.pwa-layouts.index'
                ]);

            Route::post('layout', 'store')
                ->name('pwa.layout.store')
                ->defaults('_config', [
                    'redirect' => 'pwa.layout'
                ]);
        });
    });

    Route::prefix('pwa/paypal/smart-button')->group(function () {
        Route::controller(SmartButtonController::class)->group(function () {
            Route::get('/create-order', 'createOrder')->name('paypal.smart-button.create-order.pwa');

            Route::post('/capture-order', 'captureOrder')->name('paypal.smart-button.capture-order.pwa');
        });
    });
});

Route::group(['prefix' => 'api', 'middleware' => ['locale', 'theme', 'currency']], function ($router) {

    Route::get('product-configurable-config/{id}', [ProductController::class, 'configurableConfig']);

    Route::get('invoices/{id}/download', [InvoiceController::class, 'print'])->defaults('_config', [
        'repository'    => 'Webkul\Sales\Repositories\InvoiceRepository',
        'resource'      => 'Webkul\API\Http\Resources\Sales\Invoice',
        'authorization_required' => true
    ]);

    Route::get('wishlist/add/{id}', [WishlistController::class, 'create']);

    Route::post('reviews/{id}/create', [ReviewController::class, 'store']);

    Route::get('advertisements', [APIController::class, 'fetchAdvertisementImages']);

    Route::post('save-address', [AddressController::class, 'store']);

    Route::post('pwa/image-search-upload', [ImageSearchController::class, 'upload']);

    // Checkout routes
    Route::group(['prefix' => 'pwa'], function () {
        Route::group(['prefix' => 'checkout'], function () {
            Route::controller(CartController::class)->group(function () {
                Route::get('cart', 'get');

                Route::post('cart/add/{id}', 'store');
            });

            Route::controller(CheckoutController::class)->group(function () {
                Route::post('save-address', 'saveAddress');

                Route::post('save-order', 'saveOrder');
            });
        });

        Route::group(ComparisonController::class)->group(function () {
            Route::put('/comparison', 'addCompareProduct');

            Route::post('/comparison', 'deleteComparisonProduct');

            Route::get('/comparison/get-products', 'getComparisonList');

            Route::get('/detailed-products', 'getDetailedProducts');
        });

        Route::group(InvoiceController::class)->group(function () {
            Route::get('invoices', 'index')->defaults('_config', [
                'repository'    => 'Webkul\Sales\Repositories\InvoiceRepository',
                'resource'      => 'Webkul\API\Http\Resources\Sales\Invoice',
                'authorization_required' => true
            ]);

            Route::get('invoices/{id}', 'get')->defaults('_config', [
                'repository' => 'Webkul\Sales\Repositories\InvoiceRepository',
                'resource' => 'Webkul\API\Http\Resources\Sales\Invoice',
                'authorization_required' => true
            ]);

            Route::get('products', 'index')->name('api.products');

            Route::get('products/{id}', 'get');
        });

        Route::get('move-to-cart/{id}', [WishlistController::class, 'moveToCart']);

        Route::get('categories', [CategoryController::class, 'index']);

        Route::get('attributes', [APIController::class, 'fetchAttributes']);
    });

    Route::group(['prefix' => 'checkout'], function () {
        Route::controller(CartController::class)->group(function () {
            Route::get('cart/empty', 'destroy');
            
            Route::put('cart/update', 'update');

            Route::get('cart/remove-item/{id}', 'destroyItem');

            Route::get('cart/move-to-wishlist/{id}', 'moveToWishlist');

            Route::post('cart/apply-coupon', 'applyCoupon');
    
            Route::post('cart/remove-coupon', 'removeCoupon');
        });

        Route::controller(CheckoutController::class)->group(function () {
            Route::get('guest-checkout', 'isGuestCheckout');        

            Route::post('save-shipping', 'saveShipping');

            Route::post('save-payment', 'savePayment');
        });
    });

    Route::controller(ResourceController::class)->group(function () {
        Route::get('pwa-reviews/{id}', 'get')->defaults('_config', [
            'repository' => 'Webkul\Product\Repositories\ProductReviewRepository',
            'resource' => 'Webkul\PWA\Http\Resources\Catalog\ProductReview'
        ]);

        Route::delete('reviews/{id}', 'destroy')->defaults('_config', [
            'repository' => 'Webkul\Product\Repositories\ProductReviewRepository',
            'resource' => 'Webkul\PWA\Http\Resources\Catalog\ProductReview',
            'authorization_required' => true
        ]);

        Route::get('downloadable-products', 'index')->defaults('_config', [
            'resource'      => 'Webkul\PWA\Http\Resources\Sales\DownloadableProduct',
            'repository'    => 'Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository',
            'authorization_required' => true
        ]);

        Route::get('pwa-wishlist', 'index')->defaults('_config', [
            'repository' => 'Webkul\Customer\Repositories\WishlistRepository',
            'resource' => 'Webkul\PWA\Http\Resources\Customer\Wishlist',
            'authorization_required' => true
        ]);

        Route::get('pwa-reviews', 'index')->defaults('_config', [
            'repository' => 'Webkul\Product\Repositories\ProductReviewRepository',
            'resource' => 'Webkul\PWA\Http\Resources\Catalog\ProductReview'
        ]);

        Route::get('pwa-layout', 'index')->defaults('_config', [
            'repository'    => 'Webkul\PWA\Repositories\PWALayoutRepository',
            'resource'      => 'Webkul\PWA\Http\Resources\PWA\LayoutResource'
        ]);
    });

    Route::group(['prefix' => 'pwa'], function () {
        Route::controller(ResourceController::class)->group(function () {
            Route::get('orders', 'index')->defaults('_config', [
                'repository' => 'Webkul\Sales\Repositories\OrderRepository',
                'resource' => 'Webkul\PWA\Http\Resources\Sales\Order',
                'authorization_required' => true
            ]);
    
            Route::get('orders/{id}', 'get')->defaults('_config', [
                'repository' => 'Webkul\Sales\Repositories\OrderRepository',
                'resource' => 'Webkul\PWA\Http\Resources\Sales\Order',
                'authorization_required' => true
            ]);
    
            Route::get('sliders', 'index')->defaults('_config', [
                'repository' => 'Webkul\Core\Repositories\SliderRepository',
                'resource' => 'Webkul\PWA\Http\Resources\Core\Slider'
            ]);
        });
    });
});

Route::group(['middleware' => ['web', 'locale', 'currency']], function () {
    Route::controller(SinglePageController::class)->group(function () {
        Route::get('/mobile/{any?}', 'index')->where('any', '.*')->name('mobile.home');
        
        Route::get('/pwa/{any?}', 'index')->where('any', '.*')->name('pwa.home');
    });
});

Route::prefix('paypal/standard')->group(function () {
    Route::controller(StandardController::class)->group(function () {
        Route::get('/pwa/success', 'success')->name('pwa.paypal.standard.success');

        Route::get('/pwa/cancel', 'cancel')->name('pwa.paypal.standard.cancel');
    });
});
