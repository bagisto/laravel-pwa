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

/**
 * Paypal smart button routes.
 */
Route::group(['middleware' => ['web']], function () {
    Route::controller(SmartButtonController::class)->prefix('pwa/paypal/smart-button')->group(function () {
        Route::get('create-order', 'createOrder')->name('paypal.smart-button.create-order.pwa');

        Route::post('capture-order', 'captureOrder')->name('paypal.smart-button.capture-order.pwa');
    });
});

/**
 * Paypal Standard routes.
 */
Route::controller(StandardController::class)->prefix('paypal/standard/pwa')->group(function () {
    Route::get('success', 'success')->name('pwa.paypal.standard.success');

    Route::get('cancel', 'cancel')->name('pwa.paypal.standard.cancel');
});

Route::group(['middleware' => ['locale', 'theme', 'currency']], function () {
    Route::controller(SinglePageController::class)->group(function () {
        Route::get('/mobile/{any?}', 'index')->where('any', '.*')->name('mobile.home');

        Route::get('/pwa/{any?}', 'index')->where('any', '.*')->name('pwa.home');
    });

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
        Route::controller(ComparisonController::class)->prefix('comparison')->group(function () {
            Route::put('', 'store');

            Route::post('destroy','destroy');

            Route::get('get-products', 'index');
        });

        /**
         * Review routes.
         */
        Route::controller(ReviewController::class)->prefix('comparison/customer/review/')->group(function () {
            Route::get('', 'getAll');

            Route::get('{id}', 'get');
        });

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