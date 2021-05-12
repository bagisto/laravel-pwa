<?php
    Route::group(['middleware' => ['web']], function () {
        Route::prefix('admin')->group(function () {
            Route::group(['middleware' => ['admin']], function () {
                Route::prefix('pwa')->group(function () {
                    Route::namespace('Webkul\PWA\Http\Controllers\Admin')->group(function () {
                        // PushNotifications routes
                        Route::get('pushnotification','PushNotificationController@index')
                            ->name('pwa.pushnotification.index')
                            ->defaults('_config', [
                                'view' => 'pwa::admin.push-notification.index'
                            ]);

                        Route::get('pushnotification/create','PushNotificationController@create')
                            ->name('pwa.pushnotification.create')
                            ->defaults('_config', [
                                'view' => 'pwa::admin.push-notification.create'
                            ]);

                        Route::post('pushnotification/store','PushNotificationController@store')
                            ->name('pwa.pushnotification.store')
                            ->defaults('_config', [
                                'redirect' => 'pwa.pushnotification.index'
                            ]);

                        Route::get('pushnotification/edit/{id}','PushNotificationController@edit')
                            ->name('pwa.pushnotification.edit')
                            ->defaults('_config', [
                                'view' => 'pwa::admin.push-notification.edit'
                            ]);

                        Route::post('pushnotification/update/{id}','PushNotificationController@update')
                            ->name('pwa.pushnotification.update')
                            ->defaults('_config', [
                                'redirect' => 'pwa.pushnotification.index'
                            ]);

                        Route::get('pushnotification/delete/{id}','PushNotificationController@destroy')
                            ->name('pwa.pushnotification.delete')
                            ->defaults('_config', [
                                'redirect' => 'pwa.pushnotification.index'
                            ]);

                        Route::get('pushnotification/push/{id}','PushNotificationController@pushtofirebase')
                            ->name('pwa.pushnotification.pushtofirebase')
                            ->defaults('_config', [
                                'redirect' => 'pwa.pushnotification.index'
                            ]);

                        // layout routes
                        Route::get('layout','LayoutController@index')
                            ->name('pwa.layout')
                            ->defaults('_config', [
                                'view' => 'pwa::admin.pwa-layouts.index'
                            ]);

                        Route::post('layout','LayoutController@store')
                            ->name('pwa.layout.store')
                            ->defaults('_config', [
                                'redirect' => 'pwa.layout'
                            ]);
                    });
                });
            });
        });

        Route::prefix('pwa/paypal/smart-button')->group(function () {
            Route::get('/create-order', 'Webkul\PWA\Http\Controllers\Shop\SmartButtonController@createOrder')->name('paypal.smart-button.create-order.pwa');
    
            Route::post('/capture-order', 'Webkul\PWA\Http\Controllers\Shop\SmartButtonController@captureOrder')->name('paypal.smart-button.capture-order.pwa');
        });
    });

    Route::group(['prefix' => 'api'], function ($router) {
    
        Route::group(['middleware' => ['locale', 'theme', 'currency']], function ($router) {

            Route::namespace('Webkul\PWA\Http\Controllers\Shop')->group(function () {
                Route::get('product-configurable-config/{id}', 'ProductController@configurableConfig');

                Route::get('invoices/{id}/download', 'InvoiceController@print')->defaults('_config', [
                    'repository'    => 'Webkul\Sales\Repositories\InvoiceRepository',
                    'resource'      => 'Webkul\API\Http\Resources\Sales\Invoice',
                    'authorization_required' => true
                ]);

                Route::get('wishlist/add/{id}', 'WishlistController@create');

                Route::post('reviews/{id}/create', 'ReviewController@store');

                Route::get('advertisements', 'API\APIController@fetchAdvertisementImages');

                Route::post('save-address', 'AddressController@store');

                Route::post('pwa/image-search-upload', 'ImageSearchController@upload');
            });

            // Checkout routes
            Route::group(['namespace' => 'Webkul\PWA\Http\Controllers\Shop', 'prefix' => 'pwa'], function ($router) {
                Route::group(['prefix' => 'checkout'], function ($router) {
                    Route::get('cart', 'CartController@get');

                    Route::post('save-address', 'CheckoutController@saveAddress');

                    Route::post('cart/add/{id}', 'CartController@store');

                    Route::post('save-order', 'CheckoutController@saveOrder');
                });

                Route::put('/comparison', 'ComparisonController@addCompareProduct');

                Route::post('/comparison', 'ComparisonController@deleteComparisonProduct');

                Route::get('/comparison/get-products', 'ComparisonController@getComparisonList');

                Route::get('/detailed-products', 'ComparisonController@getDetailedProducts');

                Route::get('invoices', 'InvoiceController@index')->defaults('_config', [
                    'repository'    => 'Webkul\Sales\Repositories\InvoiceRepository',
                    'resource'      => 'Webkul\API\Http\Resources\Sales\Invoice',
                    'authorization_required' => true
                ]);

                Route::get('invoices/{id}', 'InvoiceController@get')->defaults('_config', [
                    'repository' => 'Webkul\Sales\Repositories\InvoiceRepository',
                    'resource' => 'Webkul\API\Http\Resources\Sales\Invoice',
                    'authorization_required' => true
                ]);
                
                Route::get('move-to-cart/{id}', 'WishlistController@moveToCart');

                Route::get('categories', 'CategoryController@index');
                Route::get('attributes', 'API\APIController@fetchAttributes');

                Route::get('products', 'ProductController@index')->name('api.products');
                Route::get('products/{id}', 'ProductController@get');
            });

            Route::group(['namespace' => 'Webkul\PWA\Http\Controllers\Shop', 'prefix' => 'checkout'], function ($router) {

                Route::get('cart/empty', 'CartController@destroy');

                Route::get('guest-checkout', 'CheckoutController@isGuestCheckout');

                Route::put('cart/update', 'CartController@update');

                Route::get('cart/remove-item/{id}', 'CartController@destroyItem');

                Route::get('cart/move-to-wishlist/{id}', 'CartController@moveToWishlist');

                Route::post('save-shipping', 'CheckoutController@saveShipping');

                Route::post('save-payment', 'CheckoutController@savePayment');

                Route::post('cart/apply-coupon', 'CartController@applyCoupon');

                Route::post('cart/remove-coupon', 'CartController@removeCoupon');
            });

            Route::namespace('Webkul\API\Http\Controllers\Shop')->group(function () {
                Route::get('pwa-reviews/{id}', 'ResourceController@get')->defaults('_config', [
                    'repository' => 'Webkul\Product\Repositories\ProductReviewRepository',
                    'resource' => 'Webkul\PWA\Http\Resources\Catalog\ProductReview'
                ]);

                Route::delete('reviews/{id}', 'ResourceController@destroy')->defaults('_config', [
                    'repository' => 'Webkul\Product\Repositories\ProductReviewRepository',
                    'resource' => 'Webkul\PWA\Http\Resources\Catalog\ProductReview',
                    'authorization_required' => true
                ]);

                Route::get('downloadable-products', 'ResourceController@index')->defaults('_config', [
                    'resource'      => 'Webkul\PWA\Http\Resources\Sales\DownloadableProduct',
                    'repository'    => 'Webkul\Sales\Repositories\DownloadableLinkPurchasedRepository',
                    'authorization_required' => true
                ]);

                Route::get('pwa-wishlist', 'ResourceController@index')->defaults('_config', [
                    'repository' => 'Webkul\Customer\Repositories\WishlistRepository',
                    'resource' => 'Webkul\PWA\Http\Resources\Customer\Wishlist',
                    'authorization_required' => true
                ]);

                Route::get('pwa-reviews', 'ResourceController@index')->defaults('_config', [
                    'repository' => 'Webkul\Product\Repositories\ProductReviewRepository',
                    'resource' => 'Webkul\PWA\Http\Resources\Catalog\ProductReview'
                ]);

                Route::get('pwa-layout', 'ResourceController@index')->defaults('_config', [
                    'repository'    => 'Webkul\PWA\Repositories\PWALayoutRepository',
                    'resource'      => 'Webkul\PWA\Http\Resources\PWA\LayoutResource'
                ]);

                Route::group(['prefix' => 'pwa'], function ($router) {
                    Route::get('orders', 'ResourceController@index')->defaults('_config', [
                        'repository' => 'Webkul\Sales\Repositories\OrderRepository',
                        'resource' => 'Webkul\PWA\Http\Resources\Sales\Order',
                        'authorization_required' => true
                    ]);

                    Route::get('orders/{id}', 'ResourceController@get')->defaults('_config', [
                        'repository' => 'Webkul\Sales\Repositories\OrderRepository',
                        'resource' => 'Webkul\PWA\Http\Resources\Sales\Order',
                        'authorization_required' => true
                    ]);
                    
                    // Slider routes
                    Route::get('sliders', 'ResourceController@index')->defaults('_config', [
                        'repository' => 'Webkul\Core\Repositories\SliderRepository',
                        'resource' => 'Webkul\PWA\Http\Resources\Core\Slider'
                    ]);
                });
            });
        });
    });

    Route::group(['middleware' => ['web','locale', 'currency']], function ($router) {
        Route::get('/mobile/{any?}', 'Webkul\PWA\Http\Controllers\SinglePageController@index')->where('any', '.*')->name('mobile.home');
        Route::get('/pwa/{any?}', 'Webkul\PWA\Http\Controllers\SinglePageController@index')->where('any', '.*')->name('pwa.home');
    });

    Route::prefix('paypal/standard')->group(function () {
        Route::get('/pwa/success', 'Webkul\PWA\Http\Controllers\StandardController@success')->name('pwa.paypal.standard.success');

        Route::get('/pwa/cancel', 'Webkul\PWA\Http\Controllers\StandardController@cancel')->name('pwa.paypal.standard.cancel');
    });
?>