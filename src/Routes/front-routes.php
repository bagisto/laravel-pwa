<?php

use Illuminate\Support\Facades\Route;
use Webkul\PWA\Http\Controllers\Shop\CheckoutController;
use Webkul\PWA\Http\Controllers\Shop\ComparisonController;
use Webkul\PWA\Http\Controllers\Shop\LayoutController;
use Webkul\PWA\Http\Controllers\Shop\ProductController;
use Webkul\PWA\Http\Controllers\Shop\ReviewController;
use Webkul\PWA\Http\Controllers\Shop\SmartButtonController;
use Webkul\PWA\Http\Controllers\Shop\ThemeController;
use Webkul\PWA\Http\Controllers\Shop\InvoiceController;
use Webkul\PWA\Http\Controllers\SinglePageController;
use Webkul\PWA\Http\Controllers\StandardController;

Route::group(['middleware' => ['web']], function () {
    /**
     * Paypal smart buttton routes.
     */
    Route::prefix('pwa/paypal/smart-button')->group(function () {
        Route::get('/create-order', [SmartButtonController::class, 'createOrder'])->name('paypal.smart-button.create-order.pwa');

        Route::post('/capture-order', [SmartButtonController::class, 'captureOrder'])->name('paypal.smart-button.capture-order.pwa');
    });
});

Route::prefix('paypal/standard')->group(function () {
    Route::get('/pwa/success', [StandardController::class, 'success'])->name('pwa.paypal.standard.success');

    Route::get('/pwa/cancel', [StandardController::class, 'cancel'])->name('pwa.paypal.standard.cancel');
});

Route::group(['middleware' => ['locale', 'theme', 'currency']], function () {
    Route::get('/mobile/{any?}', [SinglePageController::class, 'index'])->where('any', '.*')->name('mobile.home');

    Route::get('/pwa/{any?}', [SinglePageController::class, 'index'])->where('any', '.*')->name('pwa.home');

    Route::group(['prefix' => 'api/pwa'], function () {
         /**
         * Checkout routes.
         */
        Route::group(['middleware' => ['auth:sanctum', 'sanctum.customer']], function () {

            Route::group(['prefix' => 'checkout'], function () {

                Route::post('save-address', [CheckoutController::class, 'saveAddress']);
            });
        });

         /**
         * Comparison routes.
         */
        Route::put('/comparison', [ComparisonController::class, 'store']);

        Route::post('/comparison', [ComparisonController::class, 'destroy']);

        Route::get('/comparison/get-products', [ComparisonController::class, 'index']);

        /**
         * Review routes.
         */
        Route::get('customer/review/{id}', [ReviewController::class, 'get']);

        Route::get('customer/reviews', [ReviewController::class, 'getAll']);

        /**
         * product routes.
         */
        Route::controller(ProductController::class)->prefix('product')->group(function () {
            Route::group(['prefix' => 'downloadable-products'], function () {
                Route::get('', 'getCustomerDownloadAbleProducts');

                Route::group(['middleware' => ['auth:sanctum', 'sanctum.customer']], function () {
                    Route::get('download/{id}', 'download');
                });
            });

            Route::get('{id}/configurable-config', 'configurableConfig');
        });

        Route::get('print/Invoice/{id}', [InvoiceController::class, 'print']);

        Route::get('sliders', [ThemeController::class, 'sliders']);

        Route::get('layout', [LayoutController::class, 'get']);
    });
});
